<?php
include("php/config.php");
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

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

$id_user = $_SESSION['id_user']; // Ambil id_user dari session

if (isset($_POST['update_cart'])) {
  $update_quantity = $_POST['cart_quantity'];
  $update_id = $_POST['cart_id'];
  mysqli_query($con, "UPDATE keranjang SET kuantitas = '$update_quantity' WHERE id = '$update_id'") or die('query failed');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Proses data yang dikirimkan
  $quantity = $_POST['quantity'];
  header("Location: keranjang.php");
  exit();
}

// Menghitung jumlah item dalam keranjang
$query_count = mysqli_query($con, "SELECT COUNT(*) as total FROM keranjang WHERE id_user = '$id_user'");
$row = mysqli_fetch_assoc($query_count);
$total_items = $row['total'];

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Keranjang</title>
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
    /* Hapus spinner di semua browser */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input[type=number] {
      -moz-appearance: textfield;
    }
  </style>

  <style>
    <?php include "style.css" ?>
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar">
    <a href="#" class="navbar-logo">Rizky<span>Pratama</span></a>

    <div class="navbar-nav">
      <a href="index.php">Beranda</a>
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
      <a href="#" id="shopping-cart-button"><i data-feather="shopping-cart"></i>(
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
      <button type="submit" name="cari" id="search-button-box" class="disabled" disabled><i
          data-feather="search"></i></button>
    </form>
  </nav>

  <!-- keranjang page -->
  <section class="keranjang" id="keranjang">
    <div class="container">
      <div class="content">
        <h1>Keranjang Belanja</h1>
        <form action="checkout.php" method="post">
          <table id="cartTable">
            <thead>
              <tr>
                <th>pilih</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Kuantitas</th>
                <th>Total Harga</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $cart_query = mysqli_query($con, "SELECT * FROM keranjang WHERE id_user = '$id_user' ORDER BY id DESC") or die(mysqli_error($con));
              if (mysqli_num_rows($cart_query) > 0) {
                while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                  $item_id = $fetch_cart['id'];
                  $harga = $fetch_cart['harga'];
                  $kuantitas = $fetch_cart['kuantitas'];
                  $sub_total = $harga * $kuantitas;
                  ?>
                  <tr>
                    <td><input type="checkbox" name="selected_items[]" value="<?= $fetch_cart['id'] ?>"
                        class="item-checkbox" data-id="<?= $item_id ?>" data-harga="<?= $harga ?>"
                        data-kuantitas="<?= $kuantitas ?>"></td>
                    <td>
                      <img src="admin/asset/img/produk/<?= $fetch_cart['gambar'] ?>" alt=""
                        style="width: 5rem; height: auto" />
                    </td>
                    <td>
                      <?= $fetch_cart['nama'] ?>
                    </td>
                    <td>Rp
                      <?= number_format($fetch_cart['harga']) ?>
                    </td>
                    <td>
                      <form method="post" action="update_quantity.php" class="form-kuantitas">
                        <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                        <button type="button" class="btn-update" onclick="updateQuantity(this, -1)">-</button>
                        <input type="number" class="kuantitas" name="cart_quantity" value="<?= $fetch_cart['kuantitas']; ?>"
                          min="1">
                        <button type="button" class="btn-update" onclick="updateQuantity(this, 1)">+</button>
                      </form>
                    </td>
                    <td>Rp
                      <?= number_format($sub_total); ?>
                    </td>
                    <td>
                      <button class="btn-hapus" data-id="<?= $fetch_cart['id']; ?>">Hapus</button>
                    </td>
                  </tr>
                  <?php
                }
              } else {
                echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="7">Tidak ada produk</td></tr>';
              }
              ?>
              <tr class="table-bawah">
                <td id="selectedCount" colspan="5">Total (0 item terpilih)</td>
                <td id="grandTotal">Rp0</td>
                <!-- <td>Rp120.000</td> -->
                <td>
                  <a href="checkout.php"><button class="btn-checkout">Checkout</button></a>
                </td>
              </tr>
            </tbody>
          </table>
        </form>

        <div class="cart-phone">
          <?php
          $cart_query = mysqli_query($con, "SELECT * FROM keranjang WHERE id_user = '$id_user' ORDER BY id DESC") or die(mysqli_error($con));
          if (mysqli_num_rows($cart_query) > 0) {
            while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
              $item_id = $fetch_cart['id'];
              $harga = $fetch_cart['harga'];
              $kuantitas = $fetch_cart['kuantitas'];
              $sub_total = $harga * $kuantitas;
              ?>
              <div class="container">
                <div class="item">
                  <div class="checkbox">
                    <input type="checkbox" name="selected_items[]" value="<?= $fetch_cart['id'] ?>" class="item-checkbox"
                      data-id="<?= $item_id ?>" data-harga="<?= $harga ?>" data-kuantitas="<?= $kuantitas ?>">
                  </div>
                  <div class="barang-bayar">
                    <div class="barang-item">
                      <div class="gambar">
                        <img src="admin/asset/img/produk/<?= $fetch_cart['gambar'] ?>" alt="Pesanan" />
                      </div>
                      <div class="barang-title">
                        <h3>
                          <?= $fetch_cart['nama'] ?>
                        </h3>
                        <div class="total-harga">
                          <p class="harga">Rp
                            <?= number_format($sub_total); ?>
                          </p>
                          <form method="post" action="update_quantity.php" class="form-kuantitas">
                            <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                            <button type="button" class="btn-update" onclick="updateQuantity(this, -1)">-</button>
                            <input type="number" class="kuantitas" name="cart_quantity"
                              value="<?= $fetch_cart['kuantitas']; ?>" min="1">
                            <button type="button" class="btn-update" onclick="updateQuantity(this, 1)">+</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="aksi">
                    <button class="btn-hapus" data-id="<?= $fetch_cart['id']; ?>">Hapus</button>
                  </div>
                </div>
                <?php
            }
          } else {
            echo '<div class="kosong"><p>Tidak ada produk</p></div>';
          }
          ?>

          </div>
          <div class="total-harga">
            <div class="container">
              <div class="harga">
                <h4 id="selectedCount">Total (0 item terpilih)</h4>
                <p id="grandTotal">Rp0</p>
                <div class="button">
                  <a href="checkout.php"><button class="btn-checkout-phone">Checkout</button></a>
                </div>
              </div>
            </div>
          </div>
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
  <script>
    <?php include "script.js"; ?>
  </script>

  <!-- update grand total -->
  <script>
    function updateGrandTotal() {
      let grandTotal = 0;
      let anyChecked = false;
      document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
        const harga = parseFloat(checkbox.getAttribute('data-harga'));
        const kuantitas = parseInt(checkbox.getAttribute('data-kuantitas'));
        grandTotal += harga * kuantitas;
        anyChecked = true;
      });
      const grandTotalText = `Rp${grandTotal.toLocaleString()}`;
      document.querySelectorAll("#grandTotal").forEach(element => {
        element.innerText = grandTotalText;
      });

      const checkoutButtons = document.querySelectorAll('.btn-checkout, .btn-checkout-phone');

      checkoutButtons.forEach(button => {
        if (anyChecked) {
          button.classList.remove('disabled');
          button.disabled = false;
        } else {
          button.classList.add('disabled');
          button.disabled = true;
        }
      });
    }

    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', updateGrandTotal);
    });

    // Inisialisasi grand total dan status tombol saat halaman dimuat
    updateGrandTotal();
  </script>

  <!-- button hapus -->
  <script>
    document.querySelectorAll('.btn-hapus').forEach(button => {
      button.addEventListener('click', function (e) {
        e.preventDefault();
        const itemId = this.getAttribute('data-id');
        if (confirm('Hapus dari keranjang?')) {
          fetch(`hapus-keranjang.php?hapus=${itemId}`, {
            method: 'GET'
          })
            .then(response => response.text())
            .then(data => {
              alert(data);
              location.reload(); // Reload halaman untuk memperbarui tampilan keranjang
            });
        }
      });
    });


    document.addEventListener("DOMContentLoaded", function () {
      var quantityInputs = document.querySelectorAll(".kuantitas");
      quantityInputs.forEach(function (input) {
        input.addEventListener("keydown", function (event) {
          if (event.key === "Enter") {
            event.preventDefault();
            submitForm(input);
          }
        });

        // Tambahkan event listener untuk mendeteksi kapan keyboard tidak aktif
        var timeout;
        input.addEventListener("input", function () {
          clearTimeout(timeout);
          timeout = setTimeout(function () {
            submitForm(input);
          }, 1000); // Waktu tunda (ms) setelah keyboard tidak aktif
        });
      });

      function submitForm(input) {
        var form = input.closest("form");

        // Ambil data yang diperlukan dari form
        var cart_id = form.querySelector('input[name="cart_id"]').value;
        var cart_quantity = input.value;

        // Buat data form yang akan dikirim
        var formData = new FormData();
        formData.append("cart_id", cart_id);
        formData.append("cart_quantity", cart_quantity);

        // Kirim data menggunakan Ajax
        fetch("update_quantity.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Update total harga dan tampilan lainnya jika perlu
              console.log("Update berhasil");
              location.reload();
            } else {
              console.log("Update gagal");
            }
          })
          .catch((error) => console.error("Error:", error));
      }

      // Tambahkan event listener untuk checkbox
      var checkboxes = document.querySelectorAll(".item-checkbox");
      checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener("change", function () {
          updateSelectedCount();
        });
      });

      // menghitung item yang terpilih
      function updateSelectedCount() {
        var selectedCheckboxes = document.querySelectorAll(
          ".item-checkbox:checked"
        );
        var selectedCount = selectedCheckboxes.length;
        var selectedCountText = "Total (" + selectedCount + " item terpilih)";

        // Update kedua elemen dengan ID "selectedCount"
        document.querySelectorAll("#selectedCount").forEach(function (element) {
          element.textContent = selectedCountText;
        });
      }
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