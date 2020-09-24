
<?php
require 'config/config.php';
require 'config/common.php';

if($_POST){


	if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['phno']) || empty($_POST['address'])){
      if(empty($_POST['name'])){
        $nameError = "<span class='errorMsg'>*Name cannot be null</span>";
      }
      if(empty($_POST['email'])){
        $emailError = "<span class='errorMsg'>*Email cannot be null</span>";
      }
      if(empty($_POST['password'])){
        $passError = "<span class='errorMsg'>*Password cannot be null</span>";
      }
      if(empty($_POST['phno'])){
        $phnoError = "<span class='errorMsg'>*Phone Number cannot be null</span>";
      }
      if(empty($_POST['address'])){
        $addrError = "<span class='errorMsg'>*Address cannot be null</span>";
      }
    }elseif(!is_numeric($_POST['phno']) || strlen($_POST['phno'])<6 || strlen($_POST['password'])<4){
    	if(strlen($_POST['password'])<4){
        	$passError = "<span class='errorMsg'>*Password should be at least 4 characters</span>";
      	}
    	if(!is_numeric($_POST['phno'])){
    		$phnoError = "<span class='errorMsg'>*Phone Number cannot be string</span>";
    	}
    	if(strlen($_POST['phno'])<6){
    		$phnoError = "<span class='errorMsg'>*Invalid Phone number</span>";
    	}
	}else{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$phno = $_POST['phno'];
		$address = $_POST['address'];

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
		          ":role"=>0
		        )
	      	);
		    if($result){
		      echo "<script>alert('Account has been added. You can now login :)');window.location.href='login.php'</script>";
		    }
		}
		
	}

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
	<link rel="stylesheet" href="css/ErrorMsgPractice.css">
</head>

<body>

	<!-- Start Header Area -->
	
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb" style="padding-top:0px !important; ">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-start">
				<div class="col-first">
					<h1 style="color:black"><img src="img/fav.png" alt=""> YN Shopping </h1>
					<nav class="d-flex align-items-center" style="margin-left: 3rem;font-size: 20px">
						<a href="login.php" style="color:brown">Login<span class="lnr lnr-arrow-left"></span></a>
						<a href="register.php" style="color:brown">Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap" style="padding-top:50px" >
		<div class="container">
			<div class="row  justify-content-center">
				
				<div class="col-lg-6">
					<div class="login_form_inner" style="padding: 50px 0 70px 0 !important;">
						<h3>Register To New Account</h3>
						<form class="row login_form" action="register.php" method="post" id="contactForm" novalidate="novalidate">
							<input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" name="name" placeholder="Username" 
								style="<?= empty($nameError)? '':'border:1px solid red;'?>" 
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'">
							</div>
							<div class="col-md-12 form-group">
								<input type="email" class="form-control" name="email" placeholder="Email" 
								style="<?= empty($emailError)? '':'border:1px solid red;'?>"
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
							</div>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" name="password" placeholder="Password" 
								style="<?= empty($passError)? '':'border:1px solid red;'?>"
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
								<?= empty($passError)?'':$passError;?>
							</div>
							<div class="col-md-12 form-group">
								<input type="number" class="form-control" name="phno" placeholder="Phone Number" 
								style="<?= empty($phnoError)? '':'border:1px solid red;'?>"
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone Number'">
								<?= empty($phnoError)?'':$phnoError;?>
							</div>
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" name="address" placeholder="Address" 
								style="<?= empty($addrError)? '':'border:1px solid red;'?>"
								onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'">
							</div>
							<div class="col-md-12 form-group mt-3">
								<button type="submit" value="submit" class="primary-btn">Register</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<footer class="footer-area section_gap">
		
			
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved 
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
</p>
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