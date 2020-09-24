<?
session_start();

unset($_SESSION['cart']['id'.$_GET['cid']]);

header("Location:cart.php");
?>