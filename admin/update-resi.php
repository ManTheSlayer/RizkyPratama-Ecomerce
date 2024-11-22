<?php
include("../php/config.php");

// ambil data di URL
$id = $_GET["id"];

// query data case berdasarkan id
$result = mysqli_query($con, "SELECT * FROM pesanan WHERE id = $id");
$row = mysqli_fetch_assoc($result);

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    $id = $_POST["id"];
    $resi = htmlspecialchars($_POST["resi"]);

     // query untuk memperbarui status pesanan dan memasukkan nomor resi
    $query = "UPDATE pesanan SET status_pesanan = 'dikirim', resi = '$resi' WHERE id = '$id'";
    mysqli_query($con, $query);

    if (mysqli_affected_rows($con) > 0) {
        echo "
            <script>
                alert('Pesanan berhasil di kirim!');
                document.location.href = 'index.php?type=pesanan';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('pesanan gagal dikirim!');
                document.location.href = 'index.php?type=pesanan';
            </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Case</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    <!-- css -->
    <link rel="stylesheet" href="admin.css" />
  </head>
  <body>
    <section class="tambah-case-section">
      <div class="tambah-case">
        <div class="box" style="width: 30rem">
          <div class="form-box">
            <h2>Tambah Resi</h2>
            <form action="" method="post" enctype="multipart/form-data">
              <div class="item-form">
                <div class="form-input">
                  <label for="nama">Resi</label>
                  <input type="text" name="resi" id="resi" required />
                  <input type="hidden" name="id" id="id" value="<?= $row['id'] ?>"/>
                </div>
              </div>
              <div class="button-submit">
                <input
                  type="submit"
                  class="btn-tambah"
                  name="submit"
                  value="Konfirmasi"
                />
                <a href="index.php?type=pesanan"
                  ><button
                    type="button"
                    class="btn-kembali"
                    style="width: 7rem"
                  >
                    Kembali
                  </button></a
                >
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </body>
</html>
