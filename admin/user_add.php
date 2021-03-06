<?php
 
  require '../config/config.php';
  require '../config/common.php';

  if((empty($_SESSION['id']) && empty($_SESSION['logged_in'])) || $_SESSION['role']==0){
    header("location: login.php");
  }
  
  if(isset($_POST['submit'])){

     if(empty($_POST['name']) || empty($_POST['email']) ||empty($_POST['pass']) || empty($_POST['phno']) || empty($_POST['address'])){
      if(empty($_POST['name'])){
        $nameError = "<p style='color:red'>*Name cannot be null</p>";
      }
      if(empty($_POST['email'])){
        $emailError = "<p style='color:red'>*Email cannot be null</p>";
      }
      if(empty($_POST['pass'])){
        $passError = "<p style='color:red'>*Password cannot be null</p>";
      }
      if(empty($_POST['phno'])){
        $phnoError = "<span class='errorMsg'>*Phone Number cannot be null</span>";
      }
      if(empty($_POST['address'])){
        $addrError = "<span class='errorMsg'>*Address cannot be null</span>";
      }
    }elseif(!is_numeric($_POST['phno']) || strlen($_POST['phno'])<6 || strlen($_POST['pass'])<4){
      if(strlen($_POST['pass'])<4){
          $passError = "<span class='errorMsg'>*Password should be at least 4 characters</span>";
        }
      if(!is_numeric($_POST['phno'])){
        $phnoError = "<span class='errorMsg'>*Phone Number cannot be string</span>";
      }elseif(strlen($_POST['phno'])<6){
        $phnoError = "<span class='errorMsg'>*Invalid Phone number</span>";
      }
   }else{
      $name = $_POST['name'];
      $email = $_POST['email'];
      $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
      $phno = $_POST['phno'];
      $address = $_POST['address'];
      
      if(!empty($_POST['admin'])){
        $admincheck = 1;
      }else{
         $admincheck = 0;
      }

      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->bindValue(":email",$email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if($user){
        echo "<script>alert('Email has already exist.');</script>";
      }else{

        $stmt = $pdo->prepare("INSERT INTO users(name,email,password,phone_num,address,role) VALUES (:name,:email,:pass,:phno,:address,:role)");
        $result = $stmt->execute(
          array(
            ":name"=>$name,
            ":email"=>$email,
            ":pass"=>$pass,
            ":phno"=>$phno,
            ":address"=>$address,
            ":role"=>$admincheck
          )
        );
        if($result){
          echo "<script>alert('New account is added.');window.location.href='users.php'</script>";
        }
      }

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
              <div class="card-body">
                 <form action="user_add.php" method="post" >
                  <input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <label for="">Name</label><?= empty($nameError)?'':$nameError;?>
                    <input type="text" class="form-control" name="name" required>
                  </div>
                  <div class="form-group">
                    <label for="">Email</label><?= empty($emailError)?'':$emailError;?>
                    <input type="email" class="form-control" name="email" required>
                  </div>  
                  <div class="form-group">
                    <label for="">Password</label><?= empty($passError)?'':$passError;?>
                    <input type="Password" class="form-control" name="pass" required>
                  </div>
                  <div class="form-group">
                    <label for="">Phone Number</label><?= empty($phnoError)?'':$phnoError;?>
                    <input type="number" class="form-control" name="phno" required>
                  </div>
                  <div class="form-group">
                    <label for="">Address</label><?= empty($addrError)?'':$addrError;?>
                    <input type="text" class="form-control" name="address" required>
                  </div>
                  <div class="form-group" style="margin-bottom: 1.5rem">
                    Admin
                    <div class="form-check" style="display: inline;margin-left: 7px">
                      <input class="form-check-input position-static" type="checkbox" name="admin" id="blankCheckbox" value="admin" aria-label="...">
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
