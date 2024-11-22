<?php
  session_start();
  include("../php/config.php");

if (!isset($_SESSION['id_admin'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GadgetAR Admin</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- box icon -->
    <link
      href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css"
      rel="stylesheet"
    />

    <!-- css -->
    <style>
      <?php include "admin.css" ?>
      <?php include "../style.css" ?>
    </style>
    <link rel="stylesheet" href="../style.css" />
  </head>
  <body>
    <?php
      if(isset($_GET['type'])) {
          $type = $_GET['type'];
          if($type == 'dashboard') {
              include 'dashboard.php'; 
          } elseif($type == 'user') {
              include 'user.php';
          } elseif($type == 'produk') {
              include 'produk.php';
          } elseif($type == 'pesanan') {
              include 'pesanan.php';
          } elseif($type == 'ekspedisi') {
              include 'ekspedisi.php';
          } elseif($type == 'kontak') {
              include 'kontak.php';
          }
      }else {
          include 'dashboard.php';
      }
    ?>

    <!-- sidebar -->
    <section id="menu">
      <div class="sidebar">
        <div class="logo-details">
          <!-- <i class="bx icon"></i> -->
          <div class="logo_name">Gadget<span>AR</span></div>
          <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul class="nav-list">
          <li>
            <a href="index.php?type=dashboard">
              <i class="bx bx-grid-alt"></i>
              <span class="links_name">Dashboard</span>
            </a>
          </li>
          <li>
            <a href="index.php?type=user">
              <i class="bx bx-user"></i>
              <span class="links_name">User</span>
            </a>
          </li>
          <li>
            <a href="index.php?type=produk">
              <i class="bx bx-pie-chart-alt-2"></i>
              <span class="links_name">Produk</span>
            </a>
          </li>
          <li>
            <a href="index.php?type=pesanan">
              <i class="bx bx-cart-alt"></i>
              <span class="links_name">Pesanan</span>
            </a>
          </li>
          <li>
            <a href="index.php?type=ekspedisi">
              <i class="bx bx-package"></i>
              <span class="links_name">Jasa Ekspedisi</span>
            </a>
          </li>
          <li>
            <a href="index.php?type=kontak">
              <i class='bx bx-envelope'></i>
              <span class="links_name">Kontak</span>
            </a>
          </li>
          <li class="profile">
            <?php
            $id = $_SESSION['id_admin'];
            $query = mysqli_query($con,"SELECT * FROM `admin` WHERE id=$id");
            while($result = mysqli_fetch_assoc($query)){
                $res_email = $result['email'];
                $res_username = $result['username'];
            }
            ?>
            <div class="profile-details">
              <img src="asset/img/admin-img.jpg" alt="profileImg" />
              <div class="name_job">
                <div class="name"><?php echo htmlspecialchars($res_username); ?></div>
                <div class="job">Admin</div>
              </div>
            </div>
            <a href="logout.php"><i class="bx bx-log-out" id="log_out"></i></a>
          </li>
        </ul>
      </div>
    </section>

    <!-- Feather Icons -->
    <script>
      feather.replace();
    </script>

    <!-- javaScript -->
    <script src="admin.js"></script>
  </body>
</html>
