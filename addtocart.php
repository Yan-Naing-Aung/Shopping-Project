<?php
session_start();
require 'config/config.php';

if($_POST){
	$id = $_POST['id'];
	$qty = $_POST['qty'];

	$stmt = $pdo->prepare("SELECT quantity FROM products WHERE id=".$id);
	$stmt->execute();
	$result = $stmt->fetch();

	if($qty > $result['quantity']){
		echo "<script>alert('Not Enough Stock!');window.location.href='product_detail.php?id=$id';</script>";
	}else{
		if(isset($_SESSION['cart']['id'.$id])){
			$_SESSION['cart']['id'.$id] += $qty;
		}else{
			$_SESSION['cart']['id'.$id] = $qty;
		}
		header("Location: product_detail.php?id=".$id);
	}	
}elseif($_GET){
	$id = $_GET['id'];
	$qty = 1;

	if(isset($_GET['cat_id'])){ 					//to get back to selected category in index.php
		$cat_id = $_GET['cat_id']; 
	}

	$stmt = $pdo->prepare("SELECT quantity FROM products WHERE id=".$id);
	$stmt->execute();
	$result = $stmt->fetch();

	if($qty > $result['quantity']){
		echo "<script>alert('Not Enough Stock!');window.location.href='index.php';</script>";
	}else{
		if(isset($_SESSION['cart']['id'.$id])){
			$_SESSION['cart']['id'.$id] += $qty;
		}else{
			$_SESSION['cart']['id'.$id] = $qty;
		}
		
		if(isset($cat_id)){
			header("Location: index.php?cat_id=".$cat_id);
		}else{
			header("Location: index.php");
		}
		
	}
}


