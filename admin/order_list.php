<?php
  require '../config/config.php';
  require '../config/common.php';

  if((empty($_SESSION['id']) && empty($_SESSION['logged_in'])) || $_SESSION['role']==0){
    header("location: login.php");
  }
  $user_id=$_SESSION['id'];
  
?>

<?php
  include 'header.php';
?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Order Listings</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body"> 
               
                <!-- php script -->
                <?php
                  if(!empty($_GET['pageno'])){
                    $pageno = $_GET['pageno'];
                  }else{
                    $pageno = 1;
                  }
                  $numOfRecs = 5;
                  $offset = ($pageno - 1) * $numOfRecs;

                    $pdostatement = $pdo->prepare("SELECT * FROM sale_order ORDER BY id DESC");
                    $pdostatement->execute();
                    $result = $pdostatement->fetchAll();
                    $total_pages = ceil(count($result)/$numOfRecs);
                    
                       $pdostatement = $pdo->prepare("SELECT * FROM sale_order ORDER BY id DESC LIMIT $offset,$numOfRecs");
                       $pdostatement->execute();
                       $orders = $pdostatement->fetchAll();
                    
                  
                ?>

                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User</th>
                      <th>Total Price</th>
                      <th>Order Date</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                     <?php

                      if($orders){
                        $i=1;
                        foreach ($orders as $order) {

                          $userStmt = $pdo->prepare("SELECT name,id FROM users WHERE id=".$order['customer_id']);
                          $userStmt->execute();
                          $users = $userStmt->fetch();
                    ?>
                        <tr>
                          <td><?= $i ?></td>
                          <td><?= escape($users['name']) ?></td>
                          <td><?= escape($order['total_price']) ?></td>
                          <td><?= date("d-M-Y",strtotime($order['order_date'])) ?></td>
                          <td>
                            <div class="btn-group">
                              <div class="container">
                                <a href="order_detail.php?id=<?= $order['id'] ?>" type="button" class="btn btn-default" >View</a>
                              </div>
                            </div>
                          </td>
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
