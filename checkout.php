<?php
include("php/config.php");
session_start();
$loggedIn = isset($_SESSION['id_user']);
// if (!isset($_SESSION['valid'])) {
//   header('Location: index.php');
//   exit();
// }
if (!isset($_SESSION['id_user'])) {
  // Jika pengguna belum login, arahkan ke halaman login
  echo "<script>alert('Silakan login terlebih dahulu'); window.location.href = 'login.php';</script>";
  exit;
}

$id_user = $_SESSION['id_user'];
$selected_items = isset($_POST['selected_items']) ? $_POST['selected_items'] : [];

// Memeriksa apakah form checkout telah disubmit
if (isset($_POST['submit'])) {
  // Mengambil data dari form
  $namaLengkap = mysqli_real_escape_string($con, $_POST['nama']);
  $phone = mysqli_real_escape_string($con, $_POST['phone']);
  $alamat = mysqli_real_escape_string($con, $_POST['alamat']);
  $provinsi = mysqli_real_escape_string($con, $_POST['provinsi']);
  $distrik = mysqli_real_escape_string($con, $_POST['distrik']);
  $tipe = mysqli_real_escape_string($con, $_POST['tipe']);
  $kodepos = mysqli_real_escape_string($con, $_POST['kodepos']);
  $ekspedisi = mysqli_real_escape_string($con, $_POST['ekspedisi']);
  $final_total = mysqli_real_escape_string($con, $_POST['final-total']);
  $grand_total = mysqli_real_escape_string($con, $_POST['grand_total']);
  $total_ongkir = mysqli_real_escape_string($con, $_POST['total-ongkir']);
  $total_produk = mysqli_real_escape_string($con, $_POST['total_produk']);
  $selected_items = isset($_POST['selected_items']) ? $_POST['selected_items'] : []; // Mengambil array item ID dari form
  $bukti = ""; // Inisialisasi variabel untuk nama file bukti transfer
  $status = "diproses";

  // Mengatur zona waktu ke Asia/Jakarta (WIB)
  date_default_timezone_set('Asia/Jakarta');
  // Mendapatkan tanggal saat ini
  $tanggal_sekarang = date('d-m-Y H:i:s');

  // Menggabungkan data alamat ke dalam satu variabel
  $alamat_lengkap = "$provinsi, $tipe, $distrik, $kodepos. Detail: $alamat";

  // Simpan file bukti transfer jika ada yang diunggah
  if ($_FILES['bukti']['size'] > 0) {
    $file_name = $_FILES['bukti']['name'];
    $file_size = $_FILES['bukti']['size'];
    $file_tmp = $_FILES['bukti']['tmp_name'];
    $file_type = $_FILES['bukti']['type'];

    // Memeriksa tipe file yang diizinkan (misalnya hanya gambar atau dokumen tertentu)
    $allowed_types = array('image/jpeg', 'image/png');

    // Memeriksa ukuran file
    if ($file_size > 2000000) {
      echo "<script>alert('Checkout gagal! Ukuran file terlalu besar. Maksimal 2MB.'); window.location.href = 'keranjang.php';</script>";
      exit();
    } elseif (!in_array($file_type, $allowed_types)) {
      echo "<script>alert('Checkout gagal! Tipe file tidak didukung. Hanya file JPG atau PNG yang diizinkan.'); window.location.href = 'keranjang.php';</script>";
      exit();
    } else {
      // Simpan file ke direktori tujuan
      $upload_dir = "admin/asset/bukti/";
      $file_path = $upload_dir . $file_name;

      if (move_uploaded_file($file_tmp, $file_path)) {
        $bukti = $file_name; // Menetapkan nama file bukti untuk dimasukkan ke dalam database
      } else {
        echo "<script>alert('Checkout gagal! Gagal mengunggah file bukti. Silakan coba lagi.'); window.location.href = 'keranjang.php';</script>";
        exit();
      }
    }
  } else {
    echo "<script>alert('Checkout gagal! Silakan unggah bukti pembayaran.'); window.location.href = 'keranjang.php';</script>";
    exit();
  }

  // Query untuk insert data ke tabel proses
  $insert_query = "INSERT INTO pesanan (id_user, namaLengkap, phone, alamat, ekspedisi, final_total, nama_produk, bukti, total_ongkir, grand_total, tanggal, status_pesanan) 
                     VALUES('$id_user', '$namaLengkap', '$phone', '$alamat_lengkap', '$ekspedisi', '$final_total', '$total_produk', '$bukti', '$total_ongkir', '$grand_total', '$tanggal_sekarang', '$status')";

  // Jika proses insert berhasil
  if (mysqli_query($con, $insert_query)) {
    // Mengambil ID produk dari URL (Beli Sekarang)
    $id_produk = isset($_GET['id']) ? $_GET['id'] : 0;

    if ($id_produk) {
      // Query untuk mendapatkan data produk dari tabel produk
      $product_query = mysqli_query($con, "SELECT * FROM produk WHERE id = '$id_produk'") or die(mysqli_error($con));

      if ($product = mysqli_fetch_assoc($product_query)) {
        $id_produk = $product['id'];
        $kuantitas = 1; // Kuantitas adalah 1 untuk Beli Sekarang
        $kategori = $product['kategori'];

        // Update stok produk
        $update_query = "UPDATE produk SET stok = stok - $kuantitas, dibeli = dibeli + $kuantitas WHERE id = $id_produk";

        if (!mysqli_query($con, $update_query)) {
          throw new Exception("Terjadi kesalahan saat mengupdate stok produk.");
        }
      }
    } else {
      // Validasi apakah ada item yang dipilih
      if (empty($selected_items)) {
        echo "<script>alert('Tidak ada item yang dipilih.'); window.location.href = 'keranjang.php';</script>";
        exit();
      }

      // Mengambil data dari keranjang untuk mengupdate stok
      foreach ($selected_items as $item_id) {
        // Validasi ID item
        $item_id = mysqli_real_escape_string($con, $item_id);
        $cart_query = mysqli_query($con, "SELECT * FROM keranjang WHERE id = '$item_id' AND id_user = '$id_user'") or die(mysqli_error($con));

        if (mysqli_num_rows($cart_query) > 0) {
          while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
            $id_produk = $fetch_cart['id_produk'];
            $kuantitas = $fetch_cart['kuantitas'];
            $kategori = $fetch_cart['kategori'];

            // Update stok produk
            $update_query = "UPDATE produk SET stok = stok - $kuantitas, dibeli = dibeli + $kuantitas WHERE id = $id_produk";

            if (!mysqli_query($con, $update_query)) {
              throw new Exception("Terjadi kesalahan saat mengupdate stok produk.");
            }
          }
          // Mengosongkan keranjang belanja setelah checkout
          mysqli_query($con, "DELETE FROM keranjang WHERE id = '$item_id'") or die(mysqli_error($con));
        }
      }
    }

    // Redirect ke halaman sukses atau halaman lain yang sesuai
    echo "<script>alert('Pesanan anda di proses'); window.location.href = 'akun/pesanan.php';</script>";
  } else {
    // Menyiapkan pesan alert jika terjadi kesalahan saat insert data
    echo "<script>alert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'); window.location.href = 'keranjang.php';</script>";
  }
}


// Menghitung jumlah item dalam keranjang
$query_count = mysqli_query($con, "SELECT COUNT(*) as total FROM keranjang WHERE id_user = '$id_user'");
$row = mysqli_fetch_assoc($query_count);
$total_items = $row['total'];

// Menghitung total berat item dalam keranjang
$query_total_berat = mysqli_query($con, "SELECT SUM(berat * kuantitas) as total_berat FROM keranjang WHERE id_user = '$id_user'");
$row_berat = mysqli_fetch_assoc($query_total_berat);
$total_berat = $row_berat['total_berat'];

// akun
$query = mysqli_query($con, "SELECT * FROM user WHERE id=$id_user");

$res_username = $res_namaLengkap = $res_email = $res_phone = $res_alamat = "Data tidak tersedia";

if ($result = mysqli_fetch_assoc($query)) {
  $res_username = $result['username'];
  $res_namaLengkap = $result['nama'];
  $res_email = $result['email'];
  $res_phone = $result['phone'];
  $res_alamat = $result['alamat'];
  $res_id = $result['id'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Checkout</title>
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
      <a href="index.php">Home</a>
      <div class="dropdown">
        <a href="#" class="kategori-nav">Kategori</a>
        <div class="dropdown-menu">
          <a href="kategori/case.php">Case</a>
          <a href="kategori/charger.php">Charger</a>
          <a href="kategori/aksesoris.php">Aksesoris</a>
        </div>
      </div>
      <a href="tentang-kami.php">Tentang Kami</a>
    </div>

    <div class="navbar-extra">
      <a href="#" id="search-button"><i data-feather="search"></i></a>
      <a href="keranjang.php" id="shopping-cart-button"><i data-feather="shopping-cart"></i>(
        <?= $total_items ?>)
      </a>
      <a href="<?php echo $loggedIn ? 'akun/akun.php' : 'login.php'; ?>" id="user-button">
        <i data-feather="user"></i>|
        <?php echo $loggedIn ? 'Akun' : 'Login'; ?>
      </a>
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>

    <!-- search box -->
    <form action="search.php" method="post" class="search-form" id="search-form">
      <input type="text" name="keyword" id="search-box" placeholder="Cari disini..." autocomplete="off" />
      <!-- <label for="search-box" name="cari" id="search-label"><i data-feather="search"></i></label> -->
      <button type="submit" name="cari" id="search-button"><i data-feather="search"></i></button>
    </form>
  </nav>

  <!-- main page -->
  <section class="checkout" id="checkout">
    <div class="checkout-container">
      <div class="alamat-container">
        <div class="alamat">
          <h1>Alamat pengiriman</h1>
          <form id="alamat-form" action="" method="post">
            <div class="form-alamat">
              <div class="inputBox">
                <span>Nama Lengkap</span>
                <input type="text" value="<?php echo htmlspecialchars($res_namaLengkap); ?>" name="nama"
                  placeholder="Lengkapi profil akun anda!!!" required readonly />
              </div>
              <div class="inputBox">
                <span>Nomor Telepon</span>
                <input type="text" value="<?php echo htmlspecialchars($res_phone); ?>" name="phone"
                  placeholder="Lengkapi profil akun anda!!!" readonly />
              </div>
              <div class="inputBox">
                <span>Provinsi</span>
                <select class="form-control" name="nama_provinsi"></select>
              </div>
              <div class="inputBox">
                <span>Kota</span>
                <select class="form-control" name="nama_distrik" id="">
                  <option value=''>--Pilih Provinsi Dahulu--</option>
                </select>
              </div>
              <div class="inputBox">
                <span>Ekspedisi</span>
                <select class="form-control" name="nama_ekspedisi" id="">
                  <option value=''>--Pilih Kota Dahulu--</option>
                </select>
              </div>
              <div class="inputBox">
                <span>Paket</span>
                <select class="form-control" name="nama_paket" id="">
                  <option value=''>--Pilih Ekspedisi Dahulu--</option>
                </select>
              </div>
              <div class="inputBox">
                <span>Nama Jalan, Gedung, No. Rumah</span>
                <input type="text" value="<?php echo htmlspecialchars($res_alamat); ?>" name="alamat"
                  placeholder="Lengkapi profil akun anda!!!" readonly />
                <input type="hidden" name="total_berat" value="<?= $total_berat ?>">
                <input type="hidden" name="ekspedisi">
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- barang dibeli -->
      <div class="pesanan-container">
        <h1>Barang yang dibeli</h1>
        <?php
        include("php/config.php");

        // Ambil ID produk dari URL
        $id_produk = isset($_GET['id']) ? $_GET['id'] : 0;

        // Jika ada ID produk dari tombol Beli Sekarang
        if ($id_produk) {
          $product_query = mysqli_query($con, "SELECT * FROM produk WHERE id = '$id_produk'") or die(mysqli_error($con));

          if ($product = mysqli_fetch_assoc($product_query)) {
            $sub_total = $product['harga'];
            $grand_total = $sub_total;
            ?>
            <div class="barang-bayar">
              <div class="barang-item">
                <div class="gambar">
                  <img src="admin/asset/img/produk/<?= $product['gambar'] ?>" alt="Pesanan" />
                </div>
                <div class="barang-title">
                  <h3>
                    <?= $product['nama'] ?>
                  </h3>
                  <p>Rp
                    <?= number_format($product['harga']) ?>
                  </p>
                  <div class="total-harga">
                    <p>x1</p> <!-- Karena ini pembelian langsung, jumlah kuantitas adalah 1 -->
                    <h4>Rp
                      <?= number_format($sub_total); ?>
                    </h4>
                  </div>
                </div>
              </div>
            </div>
            <?php
          } else {
            echo "<p>Produk tidak ditemukan.</p>";
          }
        } else {
          // Jika tidak ada ID produk dari tombol keranjang, tampilkan item dari keranjang
          $grand_total = 0;
          foreach ($selected_items as $item_id) {
            $cart_query = mysqli_query($con, "SELECT * FROM keranjang WHERE id = '$item_id' AND id_user = '$id_user'") or die(mysqli_error($con));
            if ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
              $sub_total = $fetch_cart['harga'] * $fetch_cart['kuantitas'];
              $grand_total += $sub_total;
              ?>
              <div class="barang-bayar">
                <div class="barang-item">
                  <div class="gambar">
                    <img src="admin/asset/img/produk/<?= $fetch_cart['gambar'] ?>" alt="Pesanan" />
                  </div>
                  <div class="barang-title">
                    <h3>
                      <?= $fetch_cart['nama'] ?>
                    </h3>
                    <p>Rp
                      <?= number_format($fetch_cart['harga']) ?>
                    </p>
                    <div class="total-harga">
                      <p>x
                        <?= $fetch_cart['kuantitas']; ?>
                      </p>
                      <h4>Rp
                        <?= number_format($sub_total); ?>
                      </h4>
                    </div>
                  </div>
                </div>
              </div>
              <?php
            }
          }
        }
        ?>


        <div class="subtotal" action="" method="post">
          <div class="content">
            <div class="detail">
              <p>Subtotal untuk Produk:</p>
              <p>Total Ongkos Kirim:</p>
              <h3>Total Pembayaran:</h3>
            </div>
            <div class="price">
              <p>Rp
                <?= number_format($grand_total); ?>
              </p>
              <p name="total-ongkir">Rp0</p>
              <h3 class="total" name="final-total">Rp0</h3>
            </div>
          </div>
          <a href="#" class="bayar" id="bayar"><button class="btn-bayar" id="btn-bayar" disabled>Bayar</button></a>
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
            <a href="kategori/case.php"><i data-feather="chevron-right" width="16" height="16"></i> Case</a>
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

  <!-- modal box -->
  <div class="modal" id="modal-bayar">
    <div class="container">
      <div class="box">
        <div class="bayar-box">
          <div class="box-pesanan">
            <?php
            include("php/config.php");
            // Set timezone ke Asia/Jakarta
            date_default_timezone_set('Asia/Jakarta');
            // Set waktu saat ini
            $waktu_sekarang = new DateTime();
            // Tambahkan 1 jam untuk batas waktu pembayaran
            $batas_waktu_bayar = $waktu_sekarang->modify('+1 hour')->format('Y-m-d H:i:s');
            $tanggal_batas = $waktu_sekarang->format('d M Y');

            // Ambil ID produk dari URL
            $id_produk = isset($_GET['id']) ? $_GET['id'] : 0;

            $produk = [];

            if ($id_produk) {
              // Jika ada ID produk dari tombol Beli Sekarang
              $product_query = mysqli_query($con, "SELECT * FROM produk WHERE id = '$id_produk'") or die(mysqli_error($con));

              if ($product = mysqli_fetch_assoc($product_query)) {
                $produk[] = $product['nama'] . ' (1)'; // Karena ini pembelian langsung, jumlah kuantitas adalah 1
              }
            } else {
              // Jika tidak ada ID produk dari tombol Beli Sekarang, tampilkan item dari keranjang
              foreach ($selected_items as $item_id) {
                $cart_query = mysqli_query($con, "SELECT * FROM keranjang WHERE id = '$item_id' AND id_user = '$id_user'") or die(mysqli_error($con));

                while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                  $produk[] = $fetch_cart['nama'] . ' (' . $fetch_cart['kuantitas'] . ')';
                }
              }
            }

            $total_produk = implode(', ', $produk);
            ?>
            <div class="item" style="padding: 0.2rem;">
              <p>
                <?= $total_produk ?>
              </p>
            </div>
            <div class="total">
              <h3>Total : <h3 name="final-total"></h3>
              </h3>
            </div>
            <div class="batas-waktu">
              <p>Batas waktu pembayaran: <span id="countdown" style="font-weight: bold;"></span></p>
            </div>
          </div>
        </div>
        <div class="rekening">
          <h4>Transfer ke salah satu tujuan di bawah ini:</h4>
          <p>1560023020532 [Mandiri a/n Muhamad Syahrudin]</p>
          <p>0895347839710 [Dana a/n Muhamad Syahrudin]</p>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="upload-bukti">
            <input type="hidden" name="nama" value="<?php echo htmlspecialchars($res_namaLengkap); ?>" />
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($res_phone); ?>" />
            <input type="hidden" name="alamat" value="<?php echo htmlspecialchars($res_alamat); ?>" />
            <input type="hidden" name="grand_total" value="<?php echo htmlspecialchars($grand_total); ?>" />
            <input type="hidden" name="total_produk" value="<?php echo htmlspecialchars($total_produk); ?>" />
            <?php foreach ($selected_items as $item_id): ?>
              <input type="hidden" name="selected_items[]" value="<?php echo htmlspecialchars($item_id); ?>" />
            <?php endforeach; ?>
            <input type="hidden" name="total-ongkir">
            <input type="hidden" name="final-total">
            <input type="hidden" name="provinsi">
            <input type="hidden" name="distrik">
            <input type="hidden" name="tipe">
            <input type="hidden" name="kodepos">
            <input type="hidden" name="ekspedisi">
            <label for="bukti">Upload bukti transfer</label>
            <input type="file" name="bukti" required />
            <p>*Harap transfer sesuai dengan nominal transfer di atas. Terimakasih</p>
          </div>
          <div class="btn">
            <button type="button" class="btn-back" id="btn-back">Kembali</button>
            <input type="submit" class="btn-konfirmasi" name="submit" value="Konfirmasi" required />
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- batas waktu bayar -->
  <script>
    // Variabel global untuk timer dan batas waktu
    var countdownInterval;

    // Fungsi untuk memulai hitung mundur
    function mulaiHitungMundur(batasWaktu) {
      // Hentikan timer sebelumnya jika ada
      if (countdownInterval) {
        clearInterval(countdownInterval);
      }

      // Update hitung mundur setiap detik
      countdownInterval = setInterval(function () {
        var now = new Date().getTime();
        var waktuTersisa = batasWaktu - now;

        // Hitung jam, menit, dan detik tersisa
        var jam = Math.floor((waktuTersisa % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var menit = Math.floor((waktuTersisa % (1000 * 60 * 60)) / (1000 * 60));
        var detik = Math.floor((waktuTersisa % (1000 * 60)) / 1000);

        // Tampilkan hitung mundur dengan bold dan tanggal
        document.getElementById("countdown").innerHTML = "<?= $tanggal_batas ?> - " + jam + " : " + menit + " : " + detik;

        // Jika waktu sudah habis
        if (waktuTersisa < 0) {
          clearInterval(countdownInterval);
          document.getElementById("countdown").innerHTML = "Waktu pembayaran telah habis";

          // Redirect ke keranjang.php setelah 3 detik
          setTimeout(function () {
            window.location.href = "keranjang.php";
          }, 3000);  // Tunggu 3 detik sebelum redirect
        }
      }, 1000);
    }

    // Event listener untuk tombol Bayar
    document.getElementById('btn-bayar').addEventListener('click', function () {
      // Ambil batas waktu dari PHP dan konversi ke format timestamp
      var batasWaktu = new Date("<?= $batas_waktu_bayar ?>").getTime();

      // Mulai hitung mundur
      mulaiHitungMundur(batasWaktu);
    });
  </script>

  <!-- Feather Icons -->
  <script>
    feather.replace();

    // Tampilkan modal ketika tombol bayar diklik
    document
      .getElementById("bayar")
      .addEventListener("click", function (event) {
        event.preventDefault(); // Mencegah tautan dari tindakan default
        document.getElementById("modal-bayar").style.display = "flex";
      });

    // Tutup modal ketika tombol kembali diklik
    document
      .getElementById("btn-back")
      .addEventListener("click", function (event) {
        event.preventDefault(); // Mencegah tautan dari tindakan default
        document.getElementById("modal-bayar").style.display = "none";
      });

    // Tutup modal ketika area di luar box modal diklik
    window.addEventListener("click", function (event) {
      if (event.target == document.getElementById("modal-bayar")) {
        document.getElementById("modal-bayar").style.display = "none";
      }
    });
  </script>

  <!-- Raja ongkir -->
  <script src="jquery.min.js"></script>
  <script>
    $(document).ready(function () {
      $.ajax({
        type: 'post',
        url: 'dataprovinsi.php',
        success: function (hasil_provinsi) {
          $("select[name=nama_provinsi]").html(hasil_provinsi);
          console.log(hasil_provinsi);
        }
      });

      $("select[name=nama_provinsi]").on("change", function () {
        var id_provinsi_terpilih = $("option:selected", this).attr("id_provinsi");
        $.ajax({
          type: 'post',
          url: 'datadistrik.php',
          data: 'id_provinsi=' + id_provinsi_terpilih,
          success: function (hasil_distrik) {
            $("select[name=nama_distrik]").html(hasil_distrik);
          }
        });
      });

      $("select[name=nama_distrik]").on("change", function () {
        $.ajax({
          type: 'post',
          url: 'dataekspedisi.php',
          success: function (hasil_ekspedisi) {
            $("select[name=nama_ekspedisi]").html(hasil_ekspedisi);
          }
        });
      });

      $("select[name=nama_ekspedisi]").on("change", function () {
        // mendapatkan ekspedisi yang dipilih
        var ekspedisi_terpilih = $("select[name=nama_ekspedisi]").val();
        // mendapatkan id_distrik yang dipilih pengguna
        var distrik_terpilih = $("option:selected", "select[name=nama_distrik]").attr("id_distrik");
        // mendapatkan total berat dari inputan
        var total_berat = $("input[name=total_berat]").val();
        $.ajax({
          type: 'post',
          url: 'datapaket.php',
          data: 'ekspedisi=' + ekspedisi_terpilih + '&distrik=' + distrik_terpilih +
            '&berat=' + total_berat,
          success: function (hasil_paket) {
            $("select[name=nama_paket]").html(hasil_paket);

            $("input[name=ekspedisi]").val(ekspedisi_terpilih);
          }
        });
      });
      $("select[name=nama_distrik]").on("change", function () {
        $prov = $("option:selected", this).attr("nama_provinsi");
        $dist = $("option:selected", this).attr("nama_distrik");
        $tipe = $("option:selected", this).attr("tipe_distrik");
        $kodepos = $("option:selected", this).attr("kodepos");

        $("input[name=provinsi]").val($prov);
        $("input[name=distrik]").val($dist);
        $("input[name=tipe]").val($tipe);
        $("input[name=kodepos]").val($kodepos);
      });

      $("select[name=nama_paket]").on("change", function () {
        var ongkir = $("option:selected", this).attr("ongkir");
        var grand_total = <?= $grand_total ?>; // Ambil nilai grand_total dari PHP
        var final_total = parseInt(grand_total) + parseInt(ongkir);
        // Mengambil nilai dari variabel PHP ke JavaScript
        var namaLengkap = "<?php echo $res_namaLengkap; ?>";
        var alamat = "<?php echo $res_alamat; ?>";

        $("p[name=total-ongkir]").text("Rp" + Number(ongkir).toLocaleString());
        $("h3[name=final-total]").text("Rp" + Number(final_total).toLocaleString());
        $("input[name=final-total]").val(final_total);
        $("input[name=total-ongkir]").val(ongkir);

        // Periksa dan atur status tombol bayar
        checkBtnBayar(final_total, namaLengkap, alamat);
      });

      function checkBtnBayar(final_total, namaLengkap, alamat) {
        if (final_total > 0 && namaLengkap.trim() !== '' && alamat.trim() !== '') {
          $("#btn-bayar").removeClass('disabled').prop('disabled', false);
        } else {
          $("#btn-bayar").addClass('disabled').prop('disabled', true);
        }
      }
    });
  </script>

  <!-- javaScript -->
  <script src="script.js"></script>
</body>

</html>