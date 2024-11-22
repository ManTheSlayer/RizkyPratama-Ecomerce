<?php
session_start();
include("php/config.php");
$loggedIn = isset($_SESSION['id_user']);
$id_user = $_SESSION['id_user'];

// Menghitung jumlah item dalam keranjang
$query_count = mysqli_query($con, "SELECT COUNT(*) as total FROM keranjang WHERE id_user = '$id_user'");
$row = mysqli_fetch_assoc($query_count);
$total_items = $row['total'];

$keyword = ''; // Inisialisasi variabel $keyword
$rows = []; // Inisialisasi variabel $rows

if (isset($_POST["cari"])) {
  $keyword = $_POST["keyword"];
  $keyword = mysqli_real_escape_string($con, $keyword); // Menghindari SQL Injection

  // Query untuk mencari data berdasarkan keyword di tiga tabel
  $query = "
        SELECT 'produk' AS table_name, id, nama, model, kategori, gambar, harga, berat FROM produk 
        WHERE nama LIKE '%$keyword%' OR model LIKE '%$keyword%' OR kategori LIKE '%$keyword%'
    ";

  $result = mysqli_query($con, $query);
  if (!$result) {
    echo "Error: " . mysqli_error($con);
  } else {
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
      $rows[] = $row;
    }

    // Simpan hasil pencarian di session
    $_SESSION['search_results'] = $rows;
    $_SESSION['search_keyword'] = $keyword;

    // Redirect untuk menghindari pengiriman ulang form
    header("Location: search.php");
    exit();
  }
}

// Ambil hasil pencarian dari session jika ada
$keyword = isset($_SESSION['search_keyword']) ? $_SESSION['search_keyword'] : '';
$rows = isset($_SESSION['search_results']) ? $_SESSION['search_results'] : [];

// Hapus data pencarian dari session setelah ditampilkan
// unset($_SESSION['search_results']);
// unset($_SESSION['search_keyword']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hasil Pencarian</title>
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



  <section class="search" id="search">
    <div class="search-lanj">
      <h2>Hasil Pencarian untuk "
        <?php echo htmlspecialchars($keyword); ?>"
      </h2>
    </div>
    <div class="row">
      <?php if (empty($rows)): ?>
        <div class="search-card-kosong">
          <h1 style="color: #8a8a8a;">Tidak ada produk yang ditemukan.</h1>
        </div>
      <?php else: ?>
        <?php foreach ($rows as $row): ?>
          <div class="search-card">
            <img src="admin/asset/img/produk/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama']; ?>"
              class="search-card-img" />
            <h3 class="search-card-title">
              <?php echo $row['nama']; ?>
            </h3>
            <p class="search-card-price">Rp
              <?php echo number_format($row['harga'], 0, ',', '.'); ?>
            </p>
            <!-- Mengarahkan ke halaman detail sesuai dengan kategori produk -->
            <?php
            $detailPage = '';
            switch (strtolower($row['kategori'])) {
              case 'caseip':
                $detailPage = 'detail/case-detail.php';
                break;
              case 'charger':
                $detailPage = 'detail/charger-detail.php';
                break;
              case 'aksesoris':
                $detailPage = 'detail/aksesoris-detail.php';
                break;
              default:
                $detailPage = 'detail/default-detail.php'; // Halaman default jika kategori tidak dikenal
                break;
            }
            ?>
            <a href="<?php echo $detailPage; ?>?id=<?php echo $row['id']; ?>" id="detail"><i data-feather="eye"></i></a>
            <a href="#" class="shopping-cart-button" data-id="<?php echo $row['id']; ?>"
              data-gambar="<?php echo $row['gambar']; ?>" data-nama="<?php echo $row['nama']; ?>"
              data-kategori="<?php echo $row['kategori']; ?>" data-harga="<?php echo $row['harga']; ?>"
              data-berat="<?php echo $row['berat']; ?>"><i data-feather="shopping-cart"></i></a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

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
      <p><a href="">Lurida<span>Innovations</span></a> | &copy; 2024.</p>
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
  <!-- variabel login -->
  <script>
    var loggedIn = <?php echo json_encode($loggedIn); ?>;
  </script>

  <script>
    // menambahkan ke keranjang
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.shopping-cart-button').forEach(button => {
        button.addEventListener('click', function (e) {
          e.preventDefault();

          if (!loggedIn) {
            window.location.href = "login.php";
            return;
          }

          const idProduk = this.getAttribute('data-id');
          const gambar = this.getAttribute('data-gambar');
          const nama = this.getAttribute('data-nama');
          const kategori = this.getAttribute('data-kategori');
          const harga = this.getAttribute('data-harga');
          const berat = this.getAttribute('data-berat');

          fetch('cart-rekom.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
              id_produk: idProduk,
              gambar: gambar,
              nama: nama,
              kategori: kategori,
              harga: harga,
              berat: berat,
            })
          })
            .then(response => response.text())
            .then(result => {
              alert(result);
              if (result === "Item berhasil ditambahkan ke keranjang") {
                location.reload();
              }
            })
            .catch(error => console.error('Error:', error));
        });
      });
    });
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