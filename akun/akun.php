<?php
session_start();
include("../php/config.php");
$loggedIn = isset($_SESSION['id_user']);
// if (!isset($_SESSION['valid'])) {
//   header('Location: ../index.php');
//   exit();
// }
$id_user = $_SESSION['id_user'];

// Menghitung jumlah item dalam keranjang
$query_count = mysqli_query($con, "SELECT COUNT(*) as total FROM keranjang WHERE id_user = '$id_user'");
$row = mysqli_fetch_assoc($query_count);
$total_items = $row['total'];
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['id_user'])) {
  header("Location: index.php");
  exit();
}

$id = $_SESSION['id_user'];
$query = mysqli_query($con, "SELECT * FROM user WHERE id=$id");

$res_username = $res_namaLengkap = $res_email = $res_phone = $res_alamat = "Data tidak tersedia";

if ($result = mysqli_fetch_assoc($query)) {
  $res_username = $result['username'];
  $res_namaLengkap = $result['nama'];
  $res_email = $result['email'];
  $res_phone = $result['phone'];
  $res_alamat = $result['alamat'];
  $res_id = $result['id'];
  $res_foto_profil = $result['foto_profil'];
}

?>

<?php
if (isset($_POST['submit'])) {
  $email = mysqli_real_escape_string($con, $_POST['email']);
  $phone = mysqli_real_escape_string($con, $_POST['phone']);
  $username = mysqli_real_escape_string($con, $_POST['username']);
  $namaLengkap = mysqli_real_escape_string($con, $_POST['nama']);
  $alamat = mysqli_real_escape_string($con, $_POST['alamat']);
  $foto_profil = "";

  $id = $_SESSION['id_user'];

  // Cek apakah file diunggah dan tidak ada error
  if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
    $file_name = $_FILES['foto_profil']['name'];
    $file_size = $_FILES['foto_profil']['size'];
    $file_tmp = $_FILES['foto_profil']['tmp_name'];
    $file_type = $_FILES['foto_profil']['type'];

    // Memeriksa tipe file yang diizinkan (misalnya hanya gambar atau dokumen tertentu)
    $allowed_types = array('image/jpeg', 'image/png');

    // Memeriksa ukuran file
    if ($file_size > 1000000) {
      echo "<script>alert('Ukuran file terlalu besar. Maksimal 1MB.'); window.location.href = 'akun.php';</script>";
      exit();
    } elseif (!in_array($file_type, $allowed_types)) {
      echo "<script>alert('Tipe file tidak didukung. Hanya file JEPG atau PNG yang diizinkan.'); window.location.href = 'akun.php';</script>";
      exit();
    } else {
      // Simpan file ke direktori tujuan
      $upload_dir = "../admin/asset/img/users/";
      $file_path = $upload_dir . $file_name;

      if (move_uploaded_file($file_tmp, $file_path)) {
        $foto_profil = $file_name; // Menetapkan nama file bukti untuk dimasukkan ke dalam database
      } else {
        echo "<script>alert('Gagal mengunggah foto profil. Silakan coba lagi.'); window.location.href = 'akun.php';</script>";
        exit();
      }
    }
  }

  // Mengupdate query jika ada file profil atau tidak
  if ($foto_profil !== "") {
    $edit_query = mysqli_query($con, "UPDATE user SET email='$email', phone='$phone', foto_profil='$foto_profil', username='$username', nama='$namaLengkap', alamat='$alamat' WHERE id=$id") or die("error occured");
  } else {
    $edit_query = mysqli_query($con, "UPDATE user SET email='$email', phone='$phone', username='$username', nama='$namaLengkap', alamat='$alamat' WHERE id=$id") or die("error occured");
  }

  if ($edit_query) {
    echo "<script>alert('Profil berhasil disimpan.'); window.location.href = 'akun.php';</script>";
    exit();
  } else {
    echo "<script>alert('Gagal menyimpan profil. Coba lagi.'); window.location.href = 'akun.php';</script>";
    exit();
  }
} else {
  $id = $_SESSION['id_user'];
  $query = mysqli_query($con, "SELECT * FROM user WHERE id=$id");

  while ($result = mysqli_fetch_assoc($query)) {
    $res_email = $result['email'];
    $res_phone = $result['phone'];
    $res_username = $result['username'];
    $res_namaLengkap = $result['nama'];
    $res_alamat = $result['alamat'];
    $res_foto_profil = $result['foto_profil']; // Menambahkan foto profil ke variabel
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Akun</title>
  <!-- Fonts -->

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet" />

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- css -->
  <!-- <link rel="stylesheet" href="../style.css" /> -->
  <style>
    <?php include "../style.css" ?>
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar">
    <a href="#" class="navbar-logo">Rizky<span>Pratama</span></a>

    <div class="navbar-nav">
      <a href="../index.php">Beranda</a>
      <div class="dropdown">
        <a href="#">Kategori</a>
        <div class="dropdown-menu">
          <a href="../kategori/banner.php">Banner</a>
          <a href="../kategori/stiker.php">Print Stiker</a>
          <a href="../kategori/kartunama.php">Kartu Nama</a>
        </div>
      </div>
      <a href="#" class="nav-akun">Akun</a>
      <a href="pesanan.php" class="nav-pesanan">Pesanan Saya</a>
      <a href="ubahpw.php" class="nav-ubahpw">Ganti Password</a>
      <a href="../tentang-kami.php">Tentang Kami</a>
      <a href="../php/logout.php" class="nav-logout">Keluar</a>
    </div>

    <div class="navbar-extra">
      <a href="#" id="search-button"><i data-feather="search"></i></a>
      <a href="../keranjang.php" id="shopping-cart-button"><i data-feather="shopping-cart"></i>(
        <?= $total_items ?>)
      </a>
      <a href="<?php echo $loggedIn ? 'akun.php' : 'login.php'; ?>" id="user-button">
        <i data-feather="user"></i>|
        <?php echo $loggedIn ? 'Akun' : 'Login'; ?>
      </a>
      <a href="../signup.php" id="daftar" style="font-size: 1.2rem;">Daftar</a>
      <p href="#" id="batas" style="font-size: 1.2rem;">|</p>
      <a href="../login.php" id="login" style="font-size: 1.2rem;">Masuk</a>
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>

    <!-- search box -->
    <form action="../search.php" method="post" class="search-form" id="search-form">
      <input type="text" name="keyword" id="search-box" placeholder="Cari disini..." autocomplete="off" />
      <!-- <label for="search-box" name="cari" id="search-label"><i data-feather="search"></i></label> -->
      <button type="submit" name="cari" id="search-button-box" class="disabled" disabled><i
          data-feather="search"></i></button>
    </form>
  </nav>

  <!-- menu item -->
  <section class="all-container">
    <div>
      <div class="side-bar">
        <div class="box-item">
          <div class="menu">
            <div class="item">
              <a href="#" class="menu-item active" id="menu-pesanan">
                <i class="fa fa-user"></i> Akun Saya
              </a>
            </div>
            <div class="item">
              <a href="pesanan.php" class="menu-item">
                <i class="fa fa-list"></i> Pesanan Saya
              </a>
            </div>
            <div class="item">
              <a href="ubahpw.php" class="menu-item">
                <i class="fa fa-lock"></i> Ganti Password
              </a>
            </div>
            <div class="item">
              <a href="../php/logout.php" class="menu-item">
                <i class="fa fa-sign-out-alt"></i> Keluar
              </a>
            </div>
          </div>

        </div>

        <!-- akun saya -->
        <section>
          <div class="akun" id="akun">
            <form action="" method="post" enctype="multipart/form-data">
              <div class="box-pp">
                <div class="item">
                  <img id="profile-picture"
                    src="../admin/asset/img/users/<?= htmlspecialchars($res_foto_profil ?: 'kosong.jpg') ?>"
                    alt="foto profil">
                  <div class="file-upload">
                    <input type="file" name="foto_profil" id="file-input" />
                    <label for="file-input" class="btn">Pilih Gambar</label>
                  </div>
                  <p>Ukuran : Maks. 1 MB</p>
                  <p>Format gambar : .JPEG, .PNG</p>
                </div>
              </div>
              <div class="box-akun">
                <p>Profil Saya</p>
                <div class="box">
                  <label for="email">Email</label>
                  <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($res_email); ?>"
                    required />
                </div>
                <div class="box">
                  <label for="username">Nama Lengkap</label>
                  <input type="text" name="nama" id="nama" placeholder="Masukkan Nama Lengkap"
                    value="<?php echo htmlspecialchars($res_namaLengkap); ?>" required />
                </div>
                <div class="box">
                  <label for="username">Username</label>
                  <input type="text" name="username" id="username"
                    value="<?php echo htmlspecialchars($res_username); ?>" required />
                </div>
                <div class="box">
                  <label for="Telepon">Telepon</label>
                  <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($res_phone); ?>"
                    required>
                </div>
                <div class="box">
                  <label for="alamat">Alamat</label>
                  <textarea name="alamat" id="alamat" placeholder="Masukkan Nama Jalan, Gedung, No. Rumah" rows="4"
                    cols="50" required><?php echo htmlspecialchars($res_alamat); ?></textarea>
                </div>
                <div class="box-btn">
                  <input type="submit" class="btn" name="submit" value="Simpan" required />
                </div>
              </div>
            </form>
          </div>
        </section>
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
            <a href="../kategori/case.php"><i data-feather="chevron-right" width="16" height="16"></i> Soft Case</a>
            <a href="../kategori/charger.php"><i data-feather="chevron-right" width="16" height="16"></i> Charger</a>
            <a href="../kategori/aksesoris.php"><i data-feather="chevron-right" width="16" height="16"></i>
              Aksesoris</a>
            <a href="../tentang-kami.php"><i data-feather="chevron-right" width="16" height="16"></i> Tentang Kami</a>
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
          <img src="../asset/img/pembayaran/Mandiri.jpeg" alt="Mandiri" class="mandiri">
          <img src="../asset/img/pembayaran/Dana.jpeg" alt="Dana" class="dana">
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
  <script>
    <?php include "../script.js"; ?>
  </script>

  <script>
    document.getElementById('file-input').addEventListener('change', function () {
      var file = this.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById('profile-picture').src = e.target.result;
        }
        reader.readAsDataURL(file);
      }
    });
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