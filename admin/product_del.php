<?php
	require '../config/config.php';

	$stmt = $pdo->prepare("SELECT image FROM products WHERE id=".$_GET['id']);
	$stmt->execute();
	$image = $stmt->fetch();

	unlink("../img/".$image['image']);
	
	$stmt = $pdo->prepare("DELETE FROM products WHERE id=".$_GET['id']);
	$stmt->execute();

	header("location:index.php");

?>