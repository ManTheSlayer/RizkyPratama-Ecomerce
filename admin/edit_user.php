<?php
include("../php/config.php");

// Ambil data di URL
$id = $_GET["id"];

// Query data users berdasarkan id
$result = mysqli_query($con, "SELECT * FROM user WHERE id = $id");
$users = mysqli_fetch_assoc($result);

// Cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    $id = $_POST["id"];
    $namaLengkap = htmlspecialchars($_POST["namaLengkap"]);
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $alamat = htmlspecialchars($_POST["alamat"]);
    $gambarLama = htmlspecialchars($_POST["gambarLama"]);

    // Cek apakah user pilih gambar baru atau tidak
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $namaFile = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $error = $_FILES['gambar']['error'];
        $tmpName = $_FILES['gambar']['tmp_name'];

        // Cek apakah tidak ada kesalahan saat upload
        if ($error === 0) {
            // Cek ekstensi file gambar
            $ekstensiGambarValid = ['jpg', 'png'];
            $ekstensiGambar = explode('.', $namaFile);
            $ekstensiGambar = strtolower(end($ekstensiGambar));

            if (in_array($ekstensiGambar, $ekstensiGambarValid)) {
                // Pindahkan file ke folder img
                if (move_uploaded_file($tmpName, 'asset/img/users/' . $namaFile)) {
                    $gambar = $namaFile;
                } else {
                    echo "
                        <script>
                            alert('Gagal mengupload gambar!');
                        </script>
                    ";
                    return false;
                }
            } else {
                echo "
                    <script>
                        alert('Yang Anda upload bukan gambar!');
                    </script>
                ";
                return false;
            }
        }
    }

    $query = "UPDATE user SET
                foto_profil = '$gambar',
                nama = '$namaLengkap',
                username = '$username',
                email = '$email',
                phone = '$phone',
                alamat = '$alamat'
              WHERE id = $id";

    if (mysqli_query($con, $query)) {
        echo "
            <script>
                alert('Data berhasil diubah!');
                document.location.href = 'index.php?type=user';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Data gagal diubah!');
                document.location.href = 'index.php?type=user';
            </script>
        ";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Case</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet">

    <!-- css -->
    <!-- <link rel="stylesheet" href="admin.css"> -->
    <style>
      <?php include "admin.css" ?>
    </style>
</head>
<body>

<!-- Tambah Case -->
<section class="edit-user-section">
    <div class="edit-user">
        <div class="box" style="width: 60rem;">
            <div class="form-box">
                <h2>Edit User</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="item-form" style="display: flex; flex-wrap: wrap; gap: 1rem;">
                    <input type="hidden" name="id" value="<?php echo $users['id']; ?>">
                    <input type="hidden" name="gambarLama" value="<?php echo $users['foto_profil']; ?>">
                    <div class="form-input">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" name="namaLengkap" id="namaLengkap" value="<?php echo $users['nama']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="harga">Username</label>
                        <input type="text" id="username" name="username" value="<?php echo $users['username']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="stok">Email</label>
                        <input type="text" name="email" id="email" value="<?php echo $users['email']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="bahan">Nomor Telepon</label>
                        <input type="text" name="phone" id="phone" value="<?php echo $users['phone']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="stok">Alamat</label>
                        <textarea type="text" name="alamat" id="alamat" style="width: 100%; height: 4rem; border: 1px solid #ccc;" required ><?php echo $users['alamat']; ?></textarea>
                    </div>
                    <div class="form-input">
                        <label for="gambar">Foto Profil</label>
                        <input type="file" name="gambar" id="gambar" accept=".jpg, .png">
                        <?php if (!empty($users['foto_profil'])): ?>
                            <img src="asset/img/users/<?php echo $users['foto_profil']; ?>" width="100">
                        <?php endif; ?>
                    </div>
                    </div>
                    <div class="button-submit">
                        <input type="submit" class="btn-tambah" name="submit" value="Simpan">
                        <a href="index.php?type=user" class="btn-kembali"><button type="button" class="btn-kembali-isi">Kembali</button></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- javaScript -->
<script src="admin.js"></script>
</body>
</html>
