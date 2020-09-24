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
                <h3 class="card-title">Weekly Reports</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                
                <br>
               
                <?php
                  $currentTime = date("Y-m-d");
                  $fromdate = date("Y-m-d",strtotime($currentTime."+1 day"));
                  $todate = date("Y-m-d",strtotime($currentTime."-7 day"));

                  $pdostatement = $pdo->prepare("SELECT * FROM sale_order WHERE order_date>=:todate AND order_date<:fromdate");
                  $pdostatement->execute(
                    array(":todate"=>$todate,":fromdate"=>$fromdate)
                  );
                  $result = $pdostatement->fetchAll();
                ?>

                <table  id="d-table">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>UserId</th>
                      <th>Total Amount</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>

                     <?php

                      if($result){
                        $i=1;
                        foreach ($result as $value) {

                          $userStmt = $pdo->prepare("SELECT name FROM users WHERE id=".$value['customer_id']);
                          $userStmt->execute();
                          $userResult = $userStmt->fetch();
                    ?>
                        <tr>
                          <td><?= $i ?></td>
                          <td><?= escape($userResult['name']) ?></td>
                          <td><?= escape($value['total_price']) ?></td>
                          <td><?= escape(date("Y-m-d",strtotime($value['order_date']))) ?></td>
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