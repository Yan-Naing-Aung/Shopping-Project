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
                <h3 class="card-title">Royal Customers</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                <br>
               
                <?php
                  $netAmount = 3000;

                  $pdostatement = $pdo->prepare("SELECT sum(total_price) as total_amount,customer_id FROM sale_order GROUP BY customer_id HAVING sum(total_price) > $netAmount");
                  $pdostatement->execute();
                  $result = $pdostatement->fetchAll();

                ?>

                <table  id="d-table">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User Name</th>
                      <th>Email</th>
                      <th>Phone Number</th>
                      <th>Total Amount</th>
                    </tr>
                  </thead>
                  <tbody>

                     <?php

                      if($result){
                        $i=1;
                        foreach ($result as $value) {
                            $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['customer_id']);
                            $userStmt->execute();
                            $userResult = $userStmt->fetch();
                    ?>
                        <tr>
                          <td><?= $i ?></td>
                          <td><?= escape($userResult['name']) ?></td>
                          <td><?= escape($userResult['email']) ?></td>
                          <td><?= escape($userResult['phone_num']) ?></td>
                          <td>$<?= escape($value['total_amount']) ?></td>
                        </tr>
                    <?
                            $i++;
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