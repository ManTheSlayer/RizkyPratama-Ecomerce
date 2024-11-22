<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Selesai</title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet" />

  <!-- Feather Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- css -->
  <!-- <link rel="stylesheet" type="text/css" href="../style.css" /> -->
  <style>
    <?php include "../../style.css" ?>
  </style>
</head>

<body>
  <section class="detail-proses">
    <div class="proses-container">
      <?php
      include("../../php/config.php");
      $id = $_GET['id'];
      $result = mysqli_query($con, "SELECT * FROM pesanan WHERE id = $id");
      $selesai = mysqli_fetch_assoc($result);
      ?>
      <div class="box">
        <div class="nota-pesanan">
          <h1>Nota Pesanan</h1>
        </div>
        <div class="pesanan-selesai">
          <h3>Pesanan Selesai</h3>
        </div>
        <div class="tanggal">
          <p>Tanggal Pembelian</p>
          <p>
            <?= $selesai['tanggal'] ?> WIB
          </p>
        </div>
      </div>
      <div class="box-alamat">
        <div class="judul">
          <h4>Alamat Pengiriman :</h4>
          <p class="kurir">Kurir :
            <?= $selesai['ekspedisi'] ?>
          </p>
        </div>
        <div class="alamat">
          <h4 class="penerima">
            <?= $selesai['namaLengkap'] ?>
          </h4>
          <p>
            <?= $selesai['alamat'] ?>
          </p>
        </div>
        <div class="resi">
          <h4 class="resi-no">Resi :
            <?= $selesai['resi'] ?>
          </h4>
        </div>
      </div>
      <div class="box">
        <table>
          <thead>
            <tr>
              <th>Produk</th>
              <th>Total Harga</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <?= $selesai['nama_produk'] ?>
              </td>
              <td>Rp
                <?= number_format($selesai['grand_total']) ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="box-price">
        <div class="content">
          <div class="judul">
            <p>Subtotal</p>
            <p>Total Ongkir</p>
            <h3>Total Pembayaran</h3>
          </div>
          <div class="price">
            <p>Rp
              <?= number_format($selesai['grand_total']) ?>
            </p>
            <p>Rp
              <?= number_format($selesai['total_ongkir']) ?>
            </p><br><br>
            <h3 class="total">Rp
              <?= number_format($selesai['final_total']) ?>
            </h3>
          </div>
        </div>
        <div class="button">
          <a href="#" class="bukti" data-bukti="<?= $selesai['bukti'] ?>"><button class="btn-bukti">Cek bukti
              transfer</button></a>
          <a href="../pesanan.php?type=selesai"><button type="button" class="btn-kembali">Kembali</button></a>
        </div>
      </div>
    </div>
  </section>

  <!-- modal bukti -->
  <div class="modal" id="modal-bayar">
    <div class="container">
      <i data-feather="x" id="btn-back-bukti"></i>
      <img src="" alt="Image" id="file-bukti">
    </div>
  </div>

  <!-- Feather Icons -->
  <script>
    feather.replace();
  </script>

  <script>
    // Tampilkan modal ketika tombol bayar diklik
    document.querySelectorAll(".bukti").forEach(function (element) {
      element.addEventListener("click", function (event) {
        event.preventDefault();
        const buktiSrc = event.currentTarget.dataset.bukti;
        document.getElementById("file-bukti").src = "../../admin/asset/bukti/" + buktiSrc;
        document.getElementById("modal-bayar").style.display = "flex";
      });
    });

    document.getElementById("btn-back-bukti").addEventListener("click", function (event) {
      event.preventDefault();
      document.getElementById("modal-bayar").style.display = "none";
    });

    window.addEventListener("click", function (event) {
      if (event.target == document.getElementById("modal-bayar")) {
        document.getElementById("modal-bayar").style.display = "none";
      }
    });
  </script>
</body>

</html>