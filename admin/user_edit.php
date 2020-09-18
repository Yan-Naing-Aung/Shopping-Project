<?php
  
  require '../config/config.php';
  require '../config/common.php';

  if((empty($_SESSION['id']) && empty($_SESSION['logged_in'])) || $_SESSION['role']==0){
    header("location: login.php");
  }
  
  if(isset($_POST['submit'])){


    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['phno']) || empty($_POST['address'])
      || (!is_numeric($_POST['phno'])) || strlen($_POST['phno'])<6 ){
      if(empty($_POST['name'])){
        $nameError = "<p style='color:red'>*Name cannot be null</p>";
      }
      if(empty($_POST['email'])){
        $emailError = "<p style='color:red'>*Email cannot be null</p>";
      }
      if(empty($_POST['phno'])){
        $phnoError = "<span class='errorMsg'>*Phone Number cannot be null</span>";
      }
      if(!is_numeric($_POST['phno'])){
        $phnoError = "<span class='errorMsg'>*Phone Number cannot be string</span>";
      }elseif(strlen($_POST['phno'])<6){
        $phnoError = "<span class='errorMsg'>*Invalid Phone number</span>";
      }
      if(empty($_POST['address'])){
        $addrError = "<span class='errorMsg'>*Address cannot be null</span>";
      }

    }elseif(!empty($_POST['pass']) && strlen($_POST['pass'])<4){
      $passError = "<p style='color:red'>*Password should be at least 4 characters</p>";
    }else{
      $name = $_POST['name'];
      $email = $_POST['email'];
      $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
      $id = $_POST['id'];
      $phno = $_POST['phno'];
      $address = $_POST['address'];

      if(!empty($_POST['admin'])){
        $admincheck = 1;
      }else{
        $admincheck = 0;
      }

      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
      $stmt->execute(array(':email'=>$email,':id'=>$id));
      $user = $stmt->fetchAll();
      
      if($user){
        echo "<script>alert('Email Duplicated!');</script>";
      }else{
        if(empty($_POST['pass'])){
           $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',phone_num=$phno,address='$address',role='$admincheck' WHERE id='$id'");
        }else{
           $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',phone_num=$phno,address='$address',role='$admincheck',password='$pass' WHERE id='$id'");
        }
        $result = $stmt->execute();
        if($result){
          echo "<script>alert('Successfully Updated.');window.location.href='users.php';</script>";
        }
      }
    }
     
  }
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

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
              <div class="card-body">
                 <form action="" method="post">
                  <input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
                  <input type="hidden" name="id" value="<?= $result['id']?>">
                  <div class="form-group">
                    <label for="">Name</label><?= empty($nameError)?'':$nameError;?>
                    <input type="text" class="form-control" name="name" value="<?= escape($result['name'])?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Email</label><?= empty($emailError)?'':$emailError;?>
                    <input type="email" class="form-control" name="email" value="<?= escape($result['email'])?>" required>
                  </div>  
                  <div class="form-group">
                    <label for="">Password</label><?= empty($passError)?'':$passError;?>
                    <span>User already has a password</span>
                    <input type="Password" class="form-control" name="pass" >
                  </div>
                  <div class="form-group">
                    <label for="">Phone Number</label><?= empty($phnoError)?'':$phnoError;?>
                    <input type="number" class="form-control" name="phno" value="<?= escape($result['phone_num'])?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Address</label><?= empty($addrError)?'':$addrError;?>
                    <input type="text" class="form-control" name="address" value="<?= escape($result['address'])?>" required>
                  </div>
                  <div class="form-group" style="margin-bottom: 1.5rem">
                    <label>Role</label>
                    <div class="form-check" style="display: inline;margin-left: 7px">
                      <input class="form-check-input position-static" type="checkbox" name="admin" id="blankCheckbox" value="admin" aria-label="..." <?php echo ($result['role']==1)?'checked':'';?>  >
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                    <a href="users.php" type="button" class="btn btn-warning">BACK</a>
                  </div>
                </form>
              </div>              
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
