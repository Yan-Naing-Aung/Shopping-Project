
			<?php
				
				require 'config/config.php';
  				require 'config/common.php';

  				if(empty($_SESSION['userid']) && empty($_SESSION['logged_in'])){
				  header("location: login.php");
				}

  				if(isset($_POST['search'])){
			      setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
			    }else{
			      if(empty($_GET['index'])){
			        unset($_COOKIE['search']); 
			        setcookie('search', null, -1, '/'); 
			      }
			    }

                 if(!empty($_GET['pageno'])){
                    $pageno = $_GET['pageno'];
                  }else{
                    $pageno = 1;
                  }
                  if(!empty($_GET['index'])){
                    $index = $_GET['index'];
                  }else{
                    $index = 1;
                  }
                  if(!empty($_GET['data'])){
                    $data = $_GET['data'];
                  }

                  $cat_id = 1;
                  if(isset($_GET['cat_id'])){
                  	setcookie('cat_id', $_GET['cat_id'], time() + (86400 * 30), "/");
                  	$cat_id = $_GET['cat_id'];
                  }elseif(isset($_COOKIE['cat_id'])){
                  	$cat_id = $_COOKIE['cat_id'];
                  }

                  $numOfRecs = 2;
                  $offset = ($pageno - 1) * $numOfRecs;


                  if(empty($_POST['search']) && empty($_COOKIE['search'])){
                    $pdostatement = $pdo->prepare("SELECT * FROM products WHERE cat_id=$cat_id ORDER BY id DESC");
                    $pdostatement->execute();
                    $result = $pdostatement->fetchAll();
                    $total_pages = ceil(count($result)/$numOfRecs);
                    
                    
                       $pdostatement = $pdo->prepare("SELECT * FROM products WHERE cat_id=$cat_id ORDER BY id DESC LIMIT $offset,$numOfRecs");
                       $pdostatement->execute();
                       $products = $pdostatement->fetchAll();
                    
                  }else{
                    $searchkey = isset($_POST['search'])?$_POST['search']:$_COOKIE['search'];
                    $pdostatement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchkey%' AND cat_id=$cat_id ORDER BY id DESC");
                    $pdostatement->execute();
                    $result = $pdostatement->fetchAll();
                    $total_pages = ceil(count($result)/$numOfRecs);
                    
                       $pdostatement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchkey%' AND cat_id=$cat_id ORDER BY id DESC LIMIT $offset,$numOfRecs");
                       $pdostatement->execute();
                       $products = $pdostatement->fetchAll();
                    
                  }
              ?>

<?php include('header.php'); ?>

            <div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Browse Categories</div>
					<ul class="main-categories">
						
							<?
								$pdostatement = $pdo->prepare("SELECT * FROM categories");
                    			$pdostatement->execute();
                    			$catResult = $pdostatement->fetchAll();

                    			foreach($catResult as $cat){
                    		?>
                    		<li class="main-nav-list">

                    			<a href="index.php?cat_id=<?=$cat['id']?>" style="<?=($_GET['cat_id']==$cat['id'])?'color:#ffba00;':'';?>">
                    				<span class="lnr lnr-arrow-right"></span><?= escape($cat['name']) ?></a>
                    		</li>
                    		<?
                    			}
							?>
					</ul>
				</div>
			</div>

		<div class="col-xl-9 col-lg-8 col-md-7">

			<div class="filter-bar d-flex flex-wrap align-items-center">

					<div class="pagination">
						<?
							switch ($index) {
								case 'first':
									$leftno =1; $centerno=2; $rightno=3;
									break;
								case '1':
									$leftno = $pageno; $centerno=$pageno+1; $rightno=$pageno+2;
									break;
								case '2':
									$leftno = $pageno-1; $centerno=$pageno; $rightno=$pageno+1;
									break;
								case '3':
									$leftno = $pageno-2; $centerno=$pageno-1; $rightno=$pageno;
									break;
								case 'last':
									$leftno =$total_pages-2; $centerno=$total_pages-1; $rightno=$total_pages;
									break;
								case '10':
									$leftno=$data-1; $centerno=$data; $rightno=$data+1;
								break;
								default:
									$pageno=1; 
									break;
							}
						?>
						<a href="?pageno=1&index=<? $index='first'; echo $index; ?>">First</a>

						<a href="<?php if($leftno<=1){echo '#';}else{echo '?index=10&data='.$leftno;}?>"  
						class="prev-arrow" style="<?=($leftno<= 1)?'pointer-events: none':''?>">
							<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
						</a>

						<a href="?pageno=<?= $leftno ?>&index=<? $index=1;echo $index; ?>"><?= $leftno; ?></a>

						<a href="?pageno=<?= $centerno ?>&index=<? $index=2;echo $index; ?>" 
							style="<?=($total_pages<2)?'pointer-events: none;color:#dddddd;':''?>"><?= $centerno; ?></a>

						<a href="?pageno=<?= $rightno ?>&index=<? $index=3;echo $index; ?>" 
							style="<?=($total_pages<3)?'pointer-events: none;color:#dddddd;':''?>" ><?= $rightno ?></a>

						<a href="<?php if($rightno>= $total_pages){echo '#';}else{echo '?index=10&data='.$rightno;}?>" 
						class="next-arrow" style="<?=($rightno>= $total_pages)?'pointer-events: none':''?>" >
							<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
						</a>

						<a href="?pageno=<?=$total_pages?>&index=<? ($total_pages<3)?$index='first':$index='last'; echo $index ?>">Last</a>
					</div>
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
				<!-- Start Filter Bar -->
				
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list">
					<div class="row">

						<?
							if($products){
								foreach ($products as $product) {
						?>
							<!-- single product -->
						<div class="col-lg-4 col-md-6">
							<div class="single-product">
								<img class="img-fluid" src="img/<?= escape($product['image'])?>" alt="">
								<div class="product-details">
									<h6><?=escape($product['name'])?> </h6>
									<div class="price">
										<h6>$<?=escape($product['price'])?></h6>
									</div>
									<div class="prd-bottom">

										<a href="" class="social-info">
											<span class="ti-bag"></span>
											<p class="hover-text">add to bag</p>
										</a>
										
										<a href="product_detail.php?id=<?=$product['id']?>" class="social-info">
											<span class="lnr lnr-move"></span>
											<p class="hover-text">view more</p>
										</a>
									</div>
								</div>
							</div>
						</div>

						<?		
								}
							}else{ 
						?>
								<div class="text-align:center;margin:60px 0;font-size:30px;">There is no product on this categories</div>
						<?	
							}
						?>
						
					
					</div>
				</section>
				<!-- End Best Seller -->
				
				<!-- End Filter Bar -->
			</div>
		</div>


<?php include 'footer.html'; ?>	