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
        $id = $_POST['id'];
        

        $stmt = $pdo->prepare("UPDATE categories SET name=:name,description=:description WHERE id=:id");
        $result = $stmt->execute(
          array(
            ":name"=>$name,
            ":description"=>$descript,
            ":id"=>$id
          )
        );
        if($result){
          echo "<script>alert('Category Updated.');window.location.href='category.php';</script>";
        }
    }

  }

  $stmt = $pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php
  include 'header.html';
?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                 <form action="" method="post" >
                  <input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
                  <input type="hidden" name="id" value="<?=$result['id']; ?>">
                  <div class="form-group">
                    <label for="">Name</label> <?= empty($nameError)?'':$nameError;?>
                    <input type="text" class="form-control" name="name" value="<?= escape($result['name'])?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Description</label><?= empty($desError)?'':$desError;?>
                    <textarea class="form-control" name="description" rows="8" cols="80" required><?= escape($result['description']) ?></textarea>
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
