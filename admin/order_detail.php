<?php
  require '../config/config.php';
  require '../config/common.php';

  if((empty($_SESSION['id']) && empty($_SESSION['logged_in'])) || $_SESSION['role']==0){
    header("location: login.php");
  }
  $user_id=$_SESSION['id'];

  if(isset($_GET['id'])){
    setcookie('id', $_GET['id'], time() + (86400 * 30), "/");
  }else{
    if(empty($_GET['pageno'])){
      unset($_COOKIE['id']); 
      setcookie('id', null, -1, '/'); 
    }
  }

?>

<?php
  include 'header.php';
?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-md-12">
             <a href="order_list.php" type="button" class="btn btn-default">BACK</a><br><br>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sale Order Detail</h3>
              </div>
              <!-- /.card-header -->
              <?php
                 $id = (isset($_GET['id']))?$_GET['id']:$_COOKIE['id'];

                 $pdostatement = $pdo->prepare("SELECT sale_order.*,users.name FROM sale_order,users WHERE users.id=sale_order.customer_id AND sale_order.id=$id");
                 $pdostatement->execute();
                 $orderInfo = $pdostatement->fetch(PDO::FETCH_ASSOC);
              ?>
              <div class="card-body">
                
                  <div style="margin-right: 30px; ">
                    <p style="text-align:left;display:inline;float:right;">
                      <b>Name: </b><?= $orderInfo['name']?> <br>
                      <b>Order ID: </b><?= $orderInfo['id']?><br>
                      <b>Order Date: </b><?= date("d M Y",strtotime($orderInfo['order_date']))?>
                    </p>
                    
                  </div>
                
               
                <!-- php script -->
                <?php
                  if(!empty($_GET['pageno'])){
                    $pageno = $_GET['pageno'];
                  }else{
                    $pageno = 1;
                  }
                  $numOfRecs = 5;
                  $offset = ($pageno - 1) * $numOfRecs;

                    $pdostatement = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=$id");
                    $pdostatement->execute();
                    $result = $pdostatement->fetchAll();
                    $total_pages = ceil(count($result)/$numOfRecs);
                    
                       $pdostatement = $pdo->prepare("SELECT sod.*,p.name,p.price as unitPrice FROM sale_order_detail as sod,products as p WHERE sod.product_id=p.id AND sod.sale_order_id=$id LIMIT $offset,$numOfRecs");
                       $pdostatement->execute();
                       $saleDetails = $pdostatement->fetchAll();
                  
                ?>

                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product</th>
                      <th>Unit Price</th>
                      <th>Quantity</th>
                      <th>Line Total</th>
                    </tr>
                  </thead>
                  <tbody>

                     <?php

                      if($saleDetails){
                        $i=1;
                        foreach ($saleDetails as $sdetail) {

                    ?>
                        <tr>
                          <td><?= $i ?></td>
                          <td><?= escape($sdetail['name']) ?></td>
                          <td><?= escape($sdetail['unitPrice']) ?></td>
                          <td><?= escape($sdetail['quantity']) ?></td>
                          <td><?= escape($sdetail['price']) ?></td>
                        </tr>
                    <?
                          $i++;
                        }
                      }
                    ?>
                    
                  </tbody>
                </table>
                <br>
                <nav aria-label="Page navigation example" style="float: right;">
                   <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                    <li class="page-item <?php if($pageno<=1){echo 'disabled';} ?>">
                      <a class="page-link" href="<?php if($pageno<=1){echo '#';}else{echo '?pageno='.($pageno-1);}?>">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
                    <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                      <a class="page-link " href="<?php if($pageno >= $total_pages){echo '#';}else{echo '?pageno='.($pageno+1);}?>">Next</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages?>">Last</a></li>
                  </ul>
                </nav>
              </div>
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->


            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->

<?php 
  include 'footer.html';
?>
