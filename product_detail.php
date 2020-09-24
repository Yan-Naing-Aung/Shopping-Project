<?
require 'config/config.php';
require 'config/common.php';

	if(empty($_SESSION['userid']) && empty($_SESSION['logged_in'])){
		header("location: login.php");
	}

 include 'header.php'; 

?>

<?
	if(isset($_GET['id'])){
		$stmt = $pdo->prepare("SELECT products.*,categories.name as cat_name FROM products,categories WHERE products.cat_id=categories.id AND products.id=".$_GET['id']);
		$stmt->execute();
		$product = $stmt->fetch(PDO::FETCH_ASSOC);
	}

?>


<!--==========s======Single Product Area =================-->
	<div style="padding-bottom: 100px;">
		<div class="container">
			<div class="row s_product_inner">
				<div class="col-lg-6">
						<div class="single-prd-item">
							<img class="img-fluid" src="img/<?= $product['image']?>" width="500" alt="">
						</div>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<div class="s_product_text">
						<h3><?= escape($product['name']) ?></h3>
						<h2>$<?= escape($product['price']) ?></h2>
						<ul class="list">
							<li><a class="active" href="#"><span>Category</span> :  <?= escape($product['cat_name']) ?></a></li>
							<li><a href="#"><span>Availibility</span> :  <?= ($product['quantity']>10)?'In Stock':'Sold Out';?></a></li>
						</ul>
						<p><?= escape($product['description'])?></p>
						<form action="addtocart.php" method="post">
							<input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
							<input type="hidden" name="id" value="<?=$product['id'];?>">
							<div class="product_count">
								<label for="qty">Quantity:</label>
								<input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
								<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
								 class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
								<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
								 class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
							</div>
							<div class="card_area d-flex align-items-center">
								<button class="primary-btn" style="border:none">Add to Cart</button> 
								<a class="btn btn-warnings" href="index.php">Back</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--================End Single Product Area =================-->

	<br><br>

<?php include 'footer.html'; ?>