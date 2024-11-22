<?php
include("../php/config.php");

// ambil data di URL
$id = $_GET["id"];

// query data charger berdasarkan id
$result = mysqli_query($con, "SELECT * FROM produk WHERE id = $id");
$charger = mysqli_fetch_assoc($result);

// cek apakah tombol submit sudah ditekan atau belum
if (isset($_POST["submit"])) {
    $id = $_POST["id"];
    $nama = htmlspecialchars($_POST["nama"]);
    $harga = htmlspecialchars($_POST["harga"]);
    $stok = htmlspecialchars($_POST["stok"]);
    $bahan = htmlspecialchars($_POST["bahan"]);
    $berat = htmlspecialchars($_POST["berat"]);
    $model = htmlspecialchars($_POST["model"]);
    $deskripsi = htmlspecialchars($_POST["deskripsi"]);
    $gambarLama = htmlspecialchars($_POST["gambarLama"]);

    // cek apakah user pilih gambar baru atau tidak
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $namaFile = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $error = $_FILES['gambar']['error'];
        $tmpName = $_FILES['gambar']['tmp_name'];

        // cek apakah tidak ada kesalahan saat upload
        if ($error === 0) {
            // cek ekstensi file gambar
            $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
            $ekstensiGambar = explode('.', $namaFile);
            $ekstensiGambar = strtolower(end($ekstensiGambar));

            if (in_array($ekstensiGambar, $ekstensiGambarValid)) {
                // pindahkan file ke folder img
                if (move_uploaded_file($tmpName, 'asset/img/produk/' . $namaFile)) {
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
                        alert('Yang anda upload bukan gambar!');
                    </script>
                ";
                return false;
            }
        }
    }

    $query = "UPDATE produk SET
                gambar = '$gambar',
                nama = '$nama',
                harga = '$harga',
                stok = '$stok',
                bahan = '$bahan',
                berat = '$berat',
                model = '$model',
                deskripsi = '$deskripsi'
              WHERE id = $id";

    mysqli_query($con, $query);

    if (mysqli_affected_rows($con) > 0) {
        echo "
            <script>
                alert('Data berhasil diubah!');
                document.location.href = 'index.php?type=produk';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('Data gagal diubah!');
                document.location.href = 'index.php?type=produk';
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
    <title>Edit Charger</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<!-- Tambah Charger -->
<section class="tambah-charger-section">
    <div class="tambah-charger">
        <div class="box" style="width: 60rem;">
            <div class="form-box">
                <h2>Edit Charger</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="item-form" style="display: flex; flex-wrap: wrap; gap: 1rem;">
                    <input type="hidden" name="id" value="<?php echo $charger['id']; ?>">
                    <input type="hidden" name="gambarLama" value="<?php echo $charger['gambar']; ?>">
                    <div class="form-input">
                        <label for="nama">Nama Produk</label>
                        <input type="text" name="nama" id="nama" value="<?php echo $charger['nama']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="harga">Harga</label>
                        <input type="text" id="harga" name="harga" value="<?php echo $charger['harga']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="stok">Stok</label>
                        <input type="text" name="stok" id="stok" value="<?php echo $charger['stok']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="bahan">Bahan</label>
                        <input type="text" name="bahan" id="bahan" value="<?php echo $charger['bahan']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="berat">Berat</label>
                        <input type="text" name="berat" id="berat" value="<?php echo $charger['berat']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="model">Model</label>
                        <input type="text" name="model" id="model" value="<?php echo $charger['model']; ?>" required>
                    </div>
                    <div class="form-input">
                        <label for="stok">Deskripsi</label>
                        <textarea type="text" name="deskripsi" id="deskripsi" style="width: 24rem; height: 4rem; border: 1px solid #ccc;" required ><?php echo $charger['deskripsi']; ?></textarea>
                    </div>
                    <div class="form-input">
                        <label for="gambar">Gambar</label>
                        <input type="file" name="gambar" id="gambar" accept=".jpg, .jpeg, .png">
                        <?php if (!empty($charger['gambar'])): ?>
                            <img src="asset/img/produk/<?php echo $charger['gambar']; ?>" width="100">
                        <?php endif; ?>
                    </div>
                    </div>
                    <div class="button-submit">
                        <input type="submit" class="btn-tambah" name="submit" value="Simpan">
                        <a href="index.php?type=produk" class="btn-kembali"><button type="button" class="btn-kembali-isi">Kembali</button></a>
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
