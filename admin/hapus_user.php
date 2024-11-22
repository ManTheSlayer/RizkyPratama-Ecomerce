<?php
include("../php/config.php");

// Pastikan parameter id tersedia dan merupakan bilangan bulat
if(isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = $_GET["id"];

    // Buat query DELETE
    $query = "DELETE FROM user WHERE id = $id";
    $result = mysqli_query($con, $query);

    // Periksa apakah query berhasil dieksekusi
    if($result) {
        $affectedRows = mysqli_affected_rows($con);
        if($affectedRows > 0) {
            echo "<script>alert('User berhasil dihapus!'); window.location.href = 'index.php?type=user';</script>";
        } else {
            echo "<script>alert('User dengan ID tersebut tidak ditemukan.'); window.location.href = 'index.php?type=user';</script>";
        }
    } else {
        echo "<script>alert('Terjadi kesalahan saat menghapus User.'); window.location.href = 'index.php?type=user';</script>";
    }
} else {
    // Jika parameter id tidak tersedia atau tidak valid
    echo "<script>alert('Parameter id tidak valid.'); window.location.href = 'index.php?type=user';</script>";
}
?>
