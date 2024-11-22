<?php
  include("../php/config.php");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Aksesoris</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- css -->
    <!-- <link rel="stylesheet" href="admin.css" /> -->
    <style>
      <?php include "admin.css" ?>
    </style>
  </head>
  <body>

    <!-- Tambah Charger -->
<section class="tambah-aksesoris-section">
  <div class="tambah-aksesoris">
    <div class="box" style="width: 60rem;">
      <div class="form-box">
        <?php
          if (isset($_POST['submit'])) {
            $kategori = $_POST['kategori'];
            $nama = $_POST['nama'];
            $harga = $_POST['harga'];
            $stok = $_POST['stok'];
            $bahan = $_POST['bahan'];
            $berat = $_POST['berat'];
            $model = $_POST['model'];
            $deskripsi = $_POST['deskripsi'];
            
            // Tangani upload gambar
            if (isset($_FILES['gambar'])) {
                $target_dir = "asset/img/produk/";
                $namaFile = basename($_FILES["gambar"]["name"]);
                $target_file = $target_dir . $namaFile;
                $tmpName = $_FILES["gambar"]["tmp_name"];
                $ukuranFile = $_FILES["gambar"]["size"];
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                // Cek jika file gambar adalah gambar sebenarnya
                $check = getimagesize($tmpName);
                if ($check !== false) {
                    // Periksa ukuran file (maksimal 2MB)
                    if ($ukuranFile > 2000000) {
                        echo "<div class='message-error'>
                                <p>Maaf, ukuran file max-2MB.</p>
                              </div><br>";
                        echo "<a href='index.php?type=produk'><button class='btn-kembali'>Kembali</button></a>";
                    } else {
                        // Hanya izinkan format file tertentu
                        if (in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
                            // Pindahkan file yang diupload ke direktori target
                            if (move_uploaded_file($tmpName, $target_file)) {
                                $gambar = $namaFile; // Gunakan nama file asli

                                // Masukkan ke database menggunakan prepared statement
                                $stmt = mysqli_prepare($con, "INSERT INTO produk (kategori, gambar, nama, harga, stok, bahan, berat, model, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                mysqli_stmt_bind_param($stmt, 'sssssssss', $kategori, $gambar, $nama, $harga, $stok, $bahan, $berat, $model, $deskripsi);

                                if (mysqli_stmt_execute($stmt)) {
                                    echo "<div class='message-sukses'>
                                            <p>Aksesoris Berhasil Ditambah!</p>
                                          </div><br>";
                                    echo "<a href='index.php?type=produk'><button class='btn-kembali'>Kembali</button></a>";
                                } else {
                                    echo "<div class='message-error'>
                                            <p>Maaf, terjadi kesalahan saat memasukkan ke database!</p>
                                          </div><br>";
                                    echo "<a href='index.php?type=produk'><button class='btn-kembali'>Kembali</button></a>";
                                }
                            } else {
                                echo "<div class='message-error'>
                                        <p>Maaf, terjadi kesalahan saat mengupload file!</p>
                                      </div><br>";
                                echo "<a href='index.php?type=produk'><button class='btn-kembali'>Kembali</button></a>";
                            }
                        } else {
                            echo "<div class='message-error'>
                                    <p>Maaf, hanya file JPG, JPEG, & PNG yang diperbolehkan!</p>
                                  </div><br>";
                            echo "<a href='index.php?type=produk'><button class='btn-kembali'>Kembali</button></a>";
                        }
                    }
                } else {
                    echo "<div class='message-error'>
                            <p>File bukan gambar!</p>
                          </div><br>";
                    echo "<a href='index.php?type=produk'><button class='btn-kembali'>Kembali</button></a>";
                }
            }
        } else {
        ?>
        <h2>Tambah Aksesoris</h2>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="item-form" style="display: flex; flex-wrap: wrap; gap: 1rem;">
          <input type="hidden" name="kategori" id="kategori" value="aksesoris"/>
          <div class="form-input">
            <label for="nama">Nama Produk</label>
            <input type="text" name="nama" id="nama" required />
          </div>
          <div class="form-input">
            <label for="harga">Harga</label>
            <input type="text" id="harga" name="harga" required>
          </div>
          <div class="form-input">
            <label for="stok">Stok</label>
            <input type="text" name="stok" id="stok" required />
          </div>
          <div class="form-input">
            <label for="bahan">Bahan</label>
            <input type="text" name="bahan" id="bahan" required />
          </div>
          <div class="form-input">
            <label for="model">Model</label>
            <input type="text" name="model" id="model" required />
          </div>
          <div class="form-input">
            <label for="berat">Berat</label>
            <input type="text" name="berat" id="berat" required />
          </div>
          <div class="form-input">
            <label for="stok">Deskripsi</label>
            <textarea type="text" name="deskripsi" id="deskripsi" style="width: 26rem; height: 4rem; border: 1px solid #ccc;" required ></textarea>
          </div>
          <div class="form-input">
            <label for="gambar">Gambar</label>
            <input type="file" name="gambar" id="gambar" accept=".jpg, .jpeg, .png" required/>
          </div>
          </div>
          <div class="button-submit">
            <input type="submit" class="btn-tambah" name="submit" value="Tambah" />
            <a href="index.php?type=produk"class="btn-kembali"><button type="button" class="btn-kembali-isi">Kembali</button></a>
          </div>
        </form>
        <?php } ?>
      </div>
    </div>
  </div>
</section>


    <!-- javaScript -->
    <script src="admin.js"></script>
  </body>
</html>
