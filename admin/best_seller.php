<?php
  require '../config/config.php';
  require '../config/common.php';

  if((empty($_SESSION['id']) && empty($_SESSION['logged_in'])) || $_SESSION['role']==0){
    header("location: login.php");
  }
  $user_id=$_SESSION['id'];

  if(isset($_POST['search'])){
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
  }else{
    if(empty($_GET['pageno'])){
      unset($_COOKIE['search']); 
      setcookie('search', null, -1, '/'); 
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
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Best Seller Items</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                <br>
               
                <?php
                  $best_selling_quantity = 10;

                  $pdostatement = $pdo->prepare("SELECT sum(quantity) as quantity,product_id FROM sale_order_detail GROUP BY product_id ");
                  $pdostatement->execute();
                  $result = $pdostatement->fetchAll();
                ?>

                <table  id="d-table">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product </th>
                      <th>Price</th>
                      <th>Quantity</th>
                    </tr>
                  </thead>
                  <tbody>

                     <?php

                      if($result){
                        $i=1;
                        foreach ($result as $value) {
                          if($value['quantity'] > $best_selling_quantity):
                            $pStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                            $pStmt->execute();
                            $pResult = $pStmt->fetch();
                    ?>
                        <tr>
                          <td><?= $i ?></td>
                          <td><img src="../img/<?= escape($pResult['image']) ?>" width="80px">  <?= escape($pResult['name']) ?></td>
                          <td><?= escape($pResult['price']) ?></td>
                          <td><?= escape($value['quantity']) ?></td>
                        </tr>
                    <?
                          $i++;
                        endif;
                        }
                      }
                    ?>
                    
                  </tbody>
                </table>
                
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

<script type="text/javascript">
      $(document).ready(function() {
          $('#d-table').DataTable();
      } );
</script>