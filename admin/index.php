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
                <h3 class="card-title">Product Listings</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div>
                  <h6 style="display: inline;margin-right:5px">New Product</h6>
                  <a href="product_add.php" type="button" class="btn btn-success">Create +</a>
                </div>
                <br>
               
                 <?php
                  if(!empty($_GET['pageno'])){
                    $pageno = $_GET['pageno'];
                  }else{
                    $pageno = 1;
                  }
                  $numOfRecs = 5;
                  $offset = ($pageno - 1) * $numOfRecs;

                  if(empty($_POST['search']) && empty($_COOKIE['search'])){
                    $pdostatement = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
                    $pdostatement->execute();
                    $result = $pdostatement->fetchAll();
                    $total_pages = ceil(count($result)/$numOfRecs);
                    
                       $pdostatement = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfRecs");
                       $pdostatement->execute();
                       $products = $pdostatement->fetchAll();
                    
                  }else{
                    $searchkey = isset($_POST['search'])?$_POST['search']:$_COOKIE['search'];
                    $pdostatement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchkey%' ORDER BY id DESC");
                    $pdostatement->execute();
                    $result = $pdostatement->fetchAll();
                    $total_pages = ceil(count($result)/$numOfRecs);
                    
                       $pdostatement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchkey%' ORDER BY id DESC LIMIT $offset,$numOfRecs");
                       $pdostatement->execute();
                       $products = $pdostatement->fetchAll();
                    
                  }
                ?>

                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Category</th>
                      <th>In Stock</th>
                      <th>Price</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>

                     <?php

                      if($products){
                        $i=1;
                        foreach ($products as $product) {

                          $catStmt = $pdo->prepare("SELECT name FROM categories WHERE id=".$product['cat_id']);
                          $catStmt->execute();
                          $catResult = $catStmt->fetch();
                    ?>
                        <tr>
                          <td><?= $i ?></td>
                          <td><?= escape($product['name']) ?></td>
                          <td><?= escape(substr($product['description'],0,30))."..." ?></td>
                          <td><?= escape($catResult['name']) ?></td>
                          <td><?= escape($product['quantity']) ?></td>
                          <td><?= escape($product['price']) ?></td>
                          <td>
                            <div class="btn-group">
                              <div class="container">
                                <a href="product_edit.php?id=<?= $product['id'] ?>" type="button" class="btn btn-warning" >Edit</a>
                              </div>
                              <div class="container">
                                <a href="product_del.php?id=<?= $product['id'] ?>" 
                                  onclick="return confirm('Are you sure you want to delete this product?')" 
                                  type="button" class="btn btn-danger" >Delete</a>
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
