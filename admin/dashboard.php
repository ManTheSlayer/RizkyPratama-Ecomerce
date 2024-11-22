<?php
include("../php/config.php");

// ========================= menghitung pesanan masuk ==========================
$query_pesanan = mysqli_query($con, "SELECT COUNT(*) as total FROM pesanan WHERE status_pesanan = 'diproses'");
$pesanan = mysqli_fetch_assoc($query_pesanan);
$pesanan_masuk = $pesanan['total'];

// =================== menghitung total produk ===============================
// Query untuk menghitung jumlah item di tabel produk
$query_produk = mysqli_query($con, "SELECT COUNT(*) as total FROM produk");
$row_produk = mysqli_fetch_assoc($query_produk);
$total_produk = $row_produk['total'];

// ==================== menghitung total user ===================
$query_user = mysqli_query($con, "SELECT COUNT(*) as total FROM user");
$user = mysqli_fetch_assoc($query_user);
$total_user = $user['total'];

// ==================== mengitung grand total pendapatan ==================
$query_pendapatan = mysqli_query($con, "SELECT SUM(grand_total) as total_pendapatan FROM pesanan WHERE status_pesanan = 'selesai'");
$row = mysqli_fetch_assoc($query_pendapatan);
$total_pendapatan = $row['total_pendapatan'];
?>

<!-- Dasboard section -->
<section class="home-section">
<div class="dasboard">
  <h1>Dashboard</h1>
  <!-- Insights -->
  <ul class="insights">
    <li>
      <i class="bx bx-calendar-check"></i>
      <span class="info">
        <h3><?= $pesanan_masuk ?></h3>
        <p>Pesanan Masuk</p>
      </span>
    </li>
    <li>
      <i class="bx bx-pie-chart-alt-2"></i>
      <span class="info">
        <h3><?= $total_produk ?></h3>
        <p>Total Produk</p>
      </span>
    </li>
    <li>
      <i class="bx bx-user"></i>
      <span class="info">
        <h3><?= $total_user ?></h3>
        <p>Total User</p>
      </span>
    </li>
    <li>
      <i class="bx bx-money"></i>
      <span class="info">
        <h3>Rp<?= number_format($total_pendapatan, 0, ',', '.'); ?></h3>
        <p>Total Pendapatan</p>
      </span>
    </li>
  </ul>

  <!-- Tentang kami -->
  <div class="tentang">
    <div class="tentang-box">
      <div class="tentang-item">
        <div class="item">
          <h1>Tentang Kami</h1>
          <img src="asset/img/gadgetar.png" alt="GadgetAR" />
          <p>
            GatgetAR adalah toko yang menjual aksesoris iPhone mulai dari casing, charger, dan aksesoris lainnya. 
            Kami menyediakan produk berkualitas tinggi yang dirancang khusus untuk memenuhi kebutuhan Anda. 
            Dengan pilihan aksesoris yang selalu up-to-date dan dalam kondisi terbaik, kami memastikan Anda mendapatkan 
            produk yang tahan lama dan stylish. GatgetAR berkomitmen memberikan pengalaman berbelanja yang menyenangkan, 
            baik untuk keperluan pribadi atau hadiah. Percayakan kebutuhan aksesoris iPhone Anda kepada kami dan rasakan 
            kemudahan serta kenyamanan berbelanja bersama GatgetAR.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
</section>