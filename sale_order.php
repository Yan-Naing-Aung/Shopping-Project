<?php
require 'config/config.php';
require 'config/common.php';

if(empty($_SESSION['userid']) && empty($_SESSION['logged_in'])){
	header("location: login.php");
}

	$user_id = $_SESSION['userid'];

	$MESSAGE = "";

	if(!empty($_SESSION['cart'])){

		$subTotal = 0;
		$subPrice = 0;
		$order_date = date('Y-m-d H:i:s');
		foreach ($_SESSION['cart'] as $id => $qty) {
	        $id = str_replace("id", "", $id);

	        $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$id);
	        $stmt->execute();
	        $product = $stmt->fetch(PDO::FETCH_ASSOC);

	        $subPrice = $product['price'] * $qty;
	        $subTotal += $subPrice; 
	    }

	    //insert into sale_order
	    $stmt = $pdo->prepare("INSERT INTO sale_order(customer_id,total_price,order_date) VALUES (:customer_id,:total_price,:order_date);");
	    $result = $stmt->execute(
	    	array(
	    		":customer_id"=>$user_id,
	    		":total_price"=>$subTotal,
	    		":order_date"=>$order_date
	    	)
	    );

	    if($result){
	    	$saleOrderId = $pdo->lastInsertId();
	    	foreach ($_SESSION['cart'] as $id => $qty) {
		        $id = str_replace("id", "", $id);
		        $subPrice = $product['price'] * $qty;

		        $sdStmt = $pdo->prepare("INSERT INTO sale_order_detail(sale_order_id,product_id,quantity,price) VALUES (:sale_order_id,:product_id,:quantity,:price)");
			    $sdResult = $sdStmt->execute(
			    	array(
			    		":sale_order_id"=>$saleOrderId,
			    		":product_id"=>$id,
			    		":price"=>$subPrice,
			    		":quantity"=>$qty
			    	)
			    );

			    if($sdResult){
			    	$qStmt = $pdo->prepare("SELECT quantity FROM products WHERE id=".$id);
			    	$qStmt->execute();
			    	$qResult = $qStmt->fetch();

			    	$updateQty = $qResult['quantity'] - $qty;

			    	$updateStmt = $pdo->prepare("UPDATE products SET quantity=:upd_qty WHERE id=:id");
			    	$upResult = $updateStmt->execute(
			    		array(":upd_qty"=>$updateQty,":id"=>$id)
			    	);

			    	if($upResult){
			    		unset($_SESSION['cart']);
			    		$MESSAGE = "Thank you. Your order has been received.";
			    	}

			    }
		        
		    }
	    }
	}else{
		$MESSAGE = "Transaction Not Succeed.";
	}

	
?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>YN Shopping</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
		<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.php"><img src="img/fav.png" alt="">
					 <span style="font-weight: 500">YN</span> Shopping</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						<ul class="nav navbar-nav menu_nav ml-auto">
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li class="nav-item"><a href="cart.php" class="cart"><span class="ti-bag">
								<?=(!empty($cart))?$cart:'';?></span></a></li>
							<li class="nav-item">
                                <button class="search"><a href="logout.php" class="tag" title="Logout"><span class="ti-shift-right "></span></a></button>
                            </li>
						</ul>
					</div>
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>

	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Confirmation</h1>
					
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Order Details Area =================-->
	<section class="order_details section_gap">
		<div class="container">
			<h3 class="title_confirmation"> <?=$MESSAGE;?></h3>
			<p style="text-align: center"><a href="index.php"><span class="lnr lnr-arrow-left"></span> Back To Shopping</a></p>
		</div>
	</section>
	<!--================End Order Details Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
</p>
			</div>
		</div>
	</footer>
	<!-- End footer Area -->




	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>
