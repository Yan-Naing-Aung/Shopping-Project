<?php
  
  require '../config/config.php';
  require '../config/common.php';


  if((empty($_SESSION['id']) && empty($_SESSION['logged_in'])) || $_SESSION['role']==0){
    header("location: login.php");
  }
  
  if(isset($_POST['submit'])){

    if(empty($_POST['name']) || empty($_POST['description'])){
      if(empty($_POST['name'])){
        $nameError = "<p style='color:red'>*Title cannot be null</p>";
      }
      if(empty($_POST['description'])){
        $desError = "<p style='color:red'>*Content cannot be null</p>";
      }

    }else{

        $name = $_POST['name'];
        $descript = $_POST['description'];

        $user_id=$_SESSION['id'];

        $stmt = $pdo->prepare("INSERT INTO categories(name,description) VALUES (:name,:descript)");
        $result = $stmt->execute(
          array(
            ":name"=>$name,
            ":descript"=>$descript
          )
        );
        if($result){
          echo "<script>alert('Category Added.');window.location.href='category.php';</script>";
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
                 <form action="cat_add.php" method="post" >
                  <input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
                  <div class="form-group">
                    <label for="">Name</label> <?= empty($nameError)?'':$nameError;?>
                    <input type="text" class="form-control" name="name" required>
                  </div>
                  <div class="form-group">
                    <label for="">Description</label><?= empty($desError)?'':$desError;?>
                    <textarea class="form-control" name="description" rows="8" cols="80" required></textarea>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                    <a href="category.php" type="button" class="btn btn-warning">BACK</a>
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
