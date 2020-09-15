<?php
  
  require '../config/config.php';
  require '../config/common.php';


  if((empty($_SESSION['id']) && empty($_SESSION['logged_in'])) || $_SESSION['role']==0){
    header("location: login.php");
  }
  
  if(isset($_POST['submit'])){
    echo is_numeric($_POST['price']);

    if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category']) || empty($_POST['price']) || empty($_POST['qty']) || empty($_FILES['image'])){

      if(empty($_POST['name'])){
        $nameError = "<p style='color:red'>*Title cannot be null</p>";
      }
      if(empty($_POST['description'])){
        $desError = "<p style='color:red'>*Content cannot be null</p>";
      }
      if(empty($_POST['category'])){
        $catError = "<p style='color:red'>*Category cannot be null</p>";
      }
      if(empty($_POST['price'])){
        $priceError = "<p style='color:red'>*Price cannot be null</p>";
      }elseif(!is_numeric($_POST['price'])){
        $priceError = "<p style='color:red'>*Price cannot be alphanumeric.</p>";
      }
      if(empty($_POST['qty'])){
        $qtyError = "<p style='color:red'>*Quantity cannot be null</p>";
      }elseif(!is_numeric($_POST['qty'])){
        $qtyError = "<p style='color:red'>*Quantity cannot be alphanumeric.</p>";
      }
      if(empty($_FILES['image'])){
        $imageError = "<p style='color:red'>*Image is not inserted</p>";
      }
    }elseif((!is_numeric($_POST['price'])) || (!is_numeric($_POST['qty']) || $_POST['price']<=0 || $_POST['qty']<=0)){
      //checking numeric and non zero value
      
          if(!is_numeric($_POST['price'])){
            $priceError = "<p style='color:red'>*Price cannot be alphanumeric.</p>";
          }elseif($_POST['price']<=0){
            $priceError = "<p style='color:red'>*Invalid price value added.</p>";
          }

          if(!is_numeric($_POST['qty'])){
            $qtyError = "<p style='color:red'>*Quantity cannot be alphanumeric.</p>";
          }elseif($_POST['qty']<=0){
            $qtyError = "<p style='color:red'>*Invalid quantity value added.</p>";
          }

    }else{

          $name = $_POST['name'];
          $descript = $_POST['description'];
          $cat = (int)$_POST['category'];
          $price = (int)$_POST['price'];
          $qty = (int)$_POST['qty'];
          $updated_at = date('Y-m-d H:i:s');
          $id = $_POST['id'];

      if($_FILES['image']['name']!=null){

        $type = $_FILES['image']['type'];

        if($type!="image/png" && $type!="image/jpg" && $type!="image/jpeg")
        {
          echo "<script>alert('Image type must be png,jpg,jpeg! ')</script>";
        }
        else
        {

          $imgname = $_FILES['image']['name'];
          $tmp_name = $_FILES['image']['tmp_name'];

          move_uploaded_file($tmp_name, "../img/$imgname");

          $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:descript,price=:price,image=:image,quantity=:quantity,cat_id=:cat_id,updated_at=:updated_at WHERE id=:id");
          $result = $stmt->execute(
            array(
              ":name"=>$name,
              ":descript"=>$descript,
              ":price"=>$price,
              ":quantity"=>$qty,
              ":image"=>$imgname,
              ":cat_id"=>$cat,
              ":updated_at"=>$updated_at,
              ":id"=>$id
            )
          );
          if($result){
            echo "<script>alert('Product Updated.');window.location.href='index.php';</script>";
          }

        }
      }else{

         $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:descript,price=:price,quantity=:quantity,cat_id=:cat_id,updated_at=:updated_at WHERE id=:id");
          $result = $stmt->execute(
            array(
              ":name"=>$name,
              ":descript"=>$descript,
              ":price"=>$price,
              ":quantity"=>$qty,
              ":cat_id"=>$cat,
              ":updated_at"=>$updated_at,
              ":id"=>$id
            )
          );
          if($result){
            echo "<script>alert('Product Updated.');window.location.href='index.php';</script>";
          }
      }

    }

  }
      
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
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

                 <?php 
                  $catStmt = $pdo->prepare("SELECT id,name FROM categories");
                  $catStmt->execute();
                  $catResult = $catStmt->fetchAll();
                  ?>
                 <form action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
                  <input type="hidden" name="id" value="<?= escape($result['id'])?>">
                  <div class="form-group">
                    <label for="">Name</label> <?= empty($nameError)?'':$nameError;?>
                    <input type="text" class="form-control" name="name" value="<?= escape($result['name'])?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Description</label><?= empty($desError)?'':$desError;?>
                    <textarea class="form-control" name="description" rows="8" cols="80"><?= escape($result['description'])?></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Categories</label> <?= empty($catError)?'':$catError;?>
                    <select class="form-control" name="category">
                      <option value="">SELECT CATEGORIES</option>
                      <?
                        foreach ($catResult as $cat) {
                      ?>
                        <option value="<?= $cat['id']?>" <?=($cat['id']==$result['cat_id'])?"selected":""?> >
                          <?= $cat['name'] ?></option>
                      <?
                        }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="">Price</label> <?= empty($priceError)?'':$priceError;?>
                    <input type="number" class="form-control" name="price" value="<?= escape($result['price'])?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Quantity</label> <?= empty($qtyError)?'':$qtyError;?>
                    <input type="number" class="form-control" name="qty" value="<?= escape($result['quantity'])?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Image</label> <?= empty($imageError)?'':$imageError;?><br>
                    <img src="../img/<?= $result['image'] ?>" width="500px"><br>
                    <input type="file" name="image" >
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                    <a href="index.php" type="button" class="btn btn-warning">BACK</a>
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
