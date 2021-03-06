<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>AdminPanel</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
     
    </ul>
    <?php
      $url = $_SERVER['PHP_SELF'];
      $link_array = explode('/',$url);
      $page = end($link_array);

      // switch ($page) {
      //   case 'category.php':
      //     $pageA = 'category.php';
      //   break;
      //   case 'users.php':
      //     $pageA = 'users.php';
      //   break;
      //   default:
      //     $pageA = 'index.php';
      //   break;
      // }
     ?>

    <!-- SEARCH FORM -->
    <?php if($page=="index.php" || $page=="users.php" || $page=="category.php"): ?>
    <form class="form-inline ml-3" method="post" action="<?= $page ?>">
      <input type="hidden" name="_token" value="<?= $_SESSION['_token']; ?>">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" name="search" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
  <?php endif; ?>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->

      <!-- Notifications Dropdown Menu -->

      
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="../img/fav.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">YN Shopping</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= $_SESSION['username'] ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
          <li class="nav-item">
            <a href="index.php" class="nav-link <?=($page=='index.php')?'active':'';?>">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Products
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="category.php" class="nav-link <?=($page=='category.php')?'active':'';?>">
              <i class="nav-icon fas fa-list-alt"></i>
              <p>
                Category
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="users.php" class="nav-link <?=($page=='users.php')?'active':'';?>">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="order_list.php" class="nav-link <?=($page=='order_list.php')?'active':'';?>">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Order
              </p>
            </a>
          </li>

           <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Reports
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="weekly_report.php" class="nav-link <?=($page=='weekly_report.php')?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Weekly Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="monthly_report.php" class="nav-link <?=($page=='monthly_report.php')?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Monthly Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="royal_cus.php" class="nav-link <?=($page=='royal_cus.php')?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Royal Customer</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="best_seller.php" class="nav-link <?=($page=='best_seller.php')?'active':'';?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Best Seller Items</p>
                </a>
              </li>
            </ul>
          </li>

        </ul>
         <!-- Sidebar Menu -->
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
     
    </div>
    <!-- /.content-header -->