<?php
session_start();
include("php/config.php");
$loggedIn = isset($_SESSION['id_user']);
// if (!isset($_SESSION['valid'])) {
//   header('Location: index.php');
//   exit();
// }

// Periksa apakah 'id_user' telah diatur dalam sesi
if (isset($_SESSION['id_user'])) {
  $id_user = $_SESSION['id_user'];

  // Menghitung jumlah item dalam keranjang
  $query_count = mysqli_query($con, "SELECT COUNT(*) as total FROM keranjang WHERE id_user = '$id_user'");
  $row = mysqli_fetch_assoc($query_count);
  $total_items = $row['total'];
} else {
  // Jika 'id_user' tidak diatur, set total_items ke 0 atau berikan nilai default lainnya
  $total_items = 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GadgetAR</title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet" />

  <!-- Feather Icons -->
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>


  <!-- css -->
  <!-- <link rel="stylesheet" href="style.css" /> -->
  <style>
    <?php include "style.css" ?>
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar">
    <a href="#" class="navbar-logo">Gadget<span>AR</span></a>

    <div class="navbar-nav">
      <a href="#">Beranda</a>
      <div class="dropdown">
        <a href="#" class="kategori-nav">Kategori</a>
        <div class="dropdown-menu">
          <a href="kategori/banner.php">Banner</a>
          <a href="kategori/stiker.php">Print Stiker</a>
          <a href="kategori/kartunama.php">Kartu Nama</a>
        </div>
      </div>
      <a href="tentang-kami.php">Tentang Kami</a>
    </div>

    <div class="navbar-extra">
      <a href="#" id="search-button"><i data-feather="search"></i></a>
      <a href="<?php echo $loggedIn ? 'keranjang.php' : 'login.php'; ?>" id="shopping-cart-button">
        <i data-feather="shopping-cart"></i>(
        <?= $total_items ?>)
      </a>
      <a href="<?php echo $loggedIn ? 'akun/akun.php' : 'login.php'; ?>" id="user-button">
        <i data-feather="user"></i>|
        <?php echo $loggedIn ? 'Akun' : 'Masuk'; ?>
      </a>
      <a href="signup.php" id="daftar" style="font-size: 1.2rem;">Daftar</a>
      <p href="#" id="batas" style="font-size: 1.2rem;">|</p>
      <a href="login.php" id="login" style="font-size: 1.2rem;">Masuk</a>
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>

    <!-- search box -->
    <form action="search.php" method="post" class="search-form" id="search-form">
      <input type="text" name="keyword" id="search-box" placeholder="Cari disini..." autocomplete="off" />
      <!-- <label for="search-box" name="cari" id="search-label"><i data-feather="search"></i></label> -->
      <button type="submit" name="cari" id="search-button-box" class="disabled" disabled><i
          data-feather="search"></i></button>
    </form>
  </nav>

  <!-- tentang -->
  <section class="tentang-kami" id="tentang-kami">
    <div class="container">
      <div class="judul">
        <h1>Rizky<span>Pratama</span> Percetakan</h1>
      </div>
      <div class="item">
        <p>Berdiri sejak tahun 2005, percetakan kami yang terletak di Jl. Raya Hankam Jl. Melati Raya, RT.001/RW.2,
          Jatiwarna, Kec. Pondok Melati, Kota Bekasi, Jawa Barat, telah menjadi pilihan utama dalam melayani kebutuhan
          cetak berbagai jenis dokumen dan media. Di bawah kepemilikan Bapak Suwandi, kami berkomitmen untuk memberikan
          layanan cetak berkualitas tinggi dengan harga yang kompetitif.
          Dengan pengalaman lebih dari 19 tahun, kami memahami betapa pentingnya ketepatan waktu, kualitas, dan kepuasan
          pelanggan. Tim kami yang profesional dan berpengalaman siap membantu berbagai proyek cetak, mulai dari
          kebutuhan pribadi hingga kebutuhan bisnis, termasuk cetak brosur, kartu nama, banner, undangan, dan banyak
          lagi.
          Kami bangga menjadi mitra tepercaya bagi banyak pelanggan setia yang selalu mengandalkan kami dalam memenuhi
          kebutuhan cetaknya. Dengan teknologi terbaru dan layanan yang cepat serta efisien, kami siap membantu
          mewujudkan ide-ide Anda ke dalam bentuk nyata.
        </p>
      </div>
    </div>
    <img src="asset/img/order-kami.png" alt="">
  </section>


  <!-- Lokasi Kami -->
  <section class="lokasi-kami" id="lokasi-kami">
    <div class="container">
      <div class="judul">
        <h2>Lokasi Kami</h2>
      </div>
      <div class="konten">
        <div class="deskripsi">
          <h3>Deskripsi Lokasi</h3><br>
          <p>
            Kunjungi kami di lokasi strategis yang mudah dijangkau:
            <br>
            Jl. Raya Hankam Jl. Melati Raya, RT.001/RW.2, Jatiwarna, Kec. Pondok Melati, Kota Bekasi, Jawa Barat.
          </p>
          <br>
          <p>
            Kami buka setiap hari Senin hingga Sabtu, mulai pukul <strong>08:00</strong> hingga <strong>20:00</strong>.
            Hubungi kami untuk informasi lebih lanjut!
          </p>
          <p>
            <strong>Koordinat:</strong> Latitude: <span> -6.241648 </span>, Longitude: <span> 106.933379 </span>
          </p>
        </div>
        <div class="map">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d247.85339416501088!2d106.92382760345937!3d-6.309065550984872!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6992ba7f694d1d%3A0x96260ec813682c3b!2sRIZKY%20PRATAMA%20PERCETAKAN!5e0!3m2!1sid!2sid!4v1732207617106!5m2!1sid!2sid"
            width="800" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </section>



  <!-- footer -->
  <footer>
    <div class="footer" id="footer">
      <div class="footer-content">
        <div class="jelajah">
          <h4>Jelajahi Kami</h4>
          <div class="tentang">
            <a href="kategori/case.php"><i data-feather="chevron-right" width="16" height="16"></i> Soft Case</a>
            <a href="kategori/charger.php"><i data-feather="chevron-right" width="16" height="16"></i> Charger</a>
            <a href="kategori/aksesoris.php"><i data-feather="chevron-right" width="16" height="16"></i> Aksesoris</a>
            <a href="tentang-kami.php"><i data-feather="chevron-right" width="16" height="16"></i> Tentang Kami</a>
          </div>
        </div>
        <div class="social-media">
          <h4>Ikuti Kami</h4>
          <div class="ig"><a href="https://www.instagram.com/gadgetarn?igsh=N3p3ZzJhcGlkNndm"><i
                data-feather="instagram"></i> Instagram</a></div>
          <div class="fb"><a href="https://www.facebook.com/profile.php?id=61563441818623&mibextid=ZbWKwL"><i
                data-feather="facebook"></i> Facebook</a></div>
        </div>
        <div class="pembayaran">
          <h4>Pembayaran</h4>
          <img src="asset/img/pembayaran/Mandiri.jpeg" alt="Mandiri" class="mandiri">
          <img src="asset/img/pembayaran/Dana.jpeg" alt="Dana" class="dana">
        </div>
      </div>
    </div>
    <div class="credit">
      <p><a href="">Gadget<span>AR</span></a> | &copy; 2024.</p>
    </div>
  </footer>

  <!-- Feather Icons -->
  <script>
    feather.replace();
  </script>

  <!-- javaScript -->
  <!-- <script src="script.js"></script> -->
  <script>
    <?php include "script.js"; ?>
  </script>

  <!-- login -->
  <script>
    var loggedIn = <?php echo json_encode($loggedIn); ?>;
    // Menyembunyikan tombol keranjang jika pengguna belum login
    if (!loggedIn) {
      const searchButton = document.getElementById("search-button");
      const shoppingCartButton = document.getElementById("shopping-cart-button");
      const userButton = document.getElementById("user-button");

      if (searchButton) {
        searchButton.style.display = 'none';
      }
      if (shoppingCartButton) {
        shoppingCartButton.style.display = 'none';
      }
      if (userButton) {
        userButton.style.display = 'none';
      }
    }

    if (loggedIn) {
      const daftarButton = document.getElementById("daftar")
      const batas = document.getElementById("batas")
      const loginButton = document.getElementById("login")

      if (daftarButton) {
        daftarButton.style.display = 'none';
      }
      if (batas) {
        batas.style.display = 'none';
      }
      if (loginButton) {
        loginButton.style.display = 'none';
      }
    }
  </script>

  <!-- search box button -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const searchBox = document.getElementById('search-box');
      const searchButton = document.getElementById('search-button-box');

      function checkSearchButton() {
        const keyword = searchBox.value.trim();
        if (keyword !== '') {
          searchButton.classList.remove('disabled');
          searchButton.classList.add('enabled');
          searchButton.disabled = false;
        } else {
          searchButton.classList.add('disabled');
          searchButton.classList.remove('enabled');
          searchButton.disabled = true;
        }
      }

      searchBox.addEventListener('input', checkSearchButton);

      // Panggil fungsi saat halaman selesai dimuat untuk memastikan tombol dalam status yang benar
      checkSearchButton();
    });
  </script>
</body>

</html>