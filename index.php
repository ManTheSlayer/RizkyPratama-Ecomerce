<?php
session_start();
include("php/config.php");
$loggedIn = isset($_SESSION['id_user']);
// if (!isset($_SESSION['valid'])) {
//   header('Location: index.php');
//   exit();
// }
// if (!isset($_SESSION['id_user'])) {
//     header("Location: index.php");
//     exit();
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
  <title>Percetakan - Rizky Pratama </title>
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
    <a href="index.php" class="navbar-logo">Rizky<span>Pratama</span></a>

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

  <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="content">
      <div class="text">
        <h1>
          Percetakan Digital Print, Backdrop - Baliho - Banner, Print A3+, Print & Cut Stiker, ID Card, Pin & Mug,
          Kaos, Plakat, Stempel
        </h1>
        <a href="kategori/banner.php" class="cta">Lihat Produk</a>
      </div>
      <div class="image">
        <img src="asset/img/order-kami.png" alt="Illustration" class="title-image">
      </div>
    </div>
  </section>



  <!-- Langkah Pemesanan -->
  <section class="rekom" id="rekom">
    <div class="rekom-lanj">
      <img src="asset/img/print-cara.png" alt="Illustration" class="title-image">
      <div class="cara">
        <h2 class="rekom-title">Cara Memesan:</h2><br>
        <div class="step">
          <span class="icon">💬</span>
          <div>
            <h3>Konsultasi</h3>
            <p>Hubungi kami untuk mendiskusikan kebutuhan percetakan Anda.</p>
          </div>
        </div>
        <div class="step">
          <span class="icon">🎨</span>
          <div>
            <h3>Desain</h3>
            <p>Kirimkan desain Anda atau mintalah bantuan dari tim desainer kami.</p>
          </div>
        </div>
        <div class="step">
          <span class="icon">🖨️</span>
          <div>
            <h3>Cetak</h3>
            <p>Proses cetak dimulai setelah desain disetujui.</p>
          </div>
        </div>
        <div class="step">
          <span class="icon">🚚</span>
          <div>
            <h3>Kirim / Ambil</h3>
            <p>Kami akan mengirimkan hasil cetak ke lokasi Anda dengan cepat dan aman, atau Anda dapat mengambilnya
              langsung di tempat kami.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- WhatsApp Floating Button -->
  <a href="https://wa.me/6289643706996?text=Halo%20CV.%20Rizky%20Pratama%20Percetakan,%0ASaya%20ingin%20bertanya%20tentang%20produk%20percetakan%20Anda.%0ABerikut%20rincian%20permintaan%20saya:%0A1.%20Produk:%20[isi%20nama%20produk]%0A2.%20Jumlah%20pesanan:%20[isi%20jumlah]%0A3.%20Ukuran:%20[isi%20ukuran]%0A4.%20File%20desain:%20[sudah%20siap/ingin%20dibuatkan]%0A5.%20Waktu%20pengerjaan:%20[sebutkan]%0A%0AMohon%20informasi%20estimasi%20biaya%20dan%20lama%20pengerjaan.%0ATerima%20kasih!"
    id="whatsapp-button" target="_blank">
    <img src="asset/img/logo-wa.png" alt="WhatsApp" />
    CONTACT ME
  </a>



  <!-- footer -->
  <footer>
    <div class="footer" id="footer">
      <div class="footer-content">
        <div class="jelajah">
          <h4>Jelajahi Kami</h4>
          <div class="tentang">
            <a href="kategori/banner.php"><i data-feather="chevron-right" width="16" height="16"></i> Banner</a>
            <a href="kategori/stiker.php"><i data-feather="chevron-right" width="16" height="16"></i> Print Stiker</a>
            <a href="kategori/kartunama.php"><i data-feather="chevron-right" width="16" height="16"></i> Kartu Nama</a>
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
      <p><a href="">Lurida<span>InnovationssA</span></a> | &copy; 2024.</p>
    </div>
  </footer>

  <!-- Feather Icons -->
  <script>
    feather.replace();
  </script>

  <!-- javaScript -->
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