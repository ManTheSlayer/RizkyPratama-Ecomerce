<?php
session_start();
include("php/config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_produk = $_POST['id_produk'];
    $gambar = $_POST['gambar'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $kuantitas = 1; // Atur kuantitas default
    $berat = $_POST['berat'];
    $id_user = $_SESSION['id_user'];

    // Cek apakah item sudah ada di keranjang berdasarkan nama
    $stmt = $con->prepare("SELECT * FROM keranjang WHERE id_user = ? AND nama = ?");
    $stmt->bind_param('is', $id_user, $nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'Item sudah ada di keranjang';
    } else {
        $stmt = $con->prepare("INSERT INTO keranjang (id_user, id_produk, gambar, nama, kategori, harga, kuantitas, berat) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('iisssdis', $id_user, $id_produk, $gambar, $nama, $kategori, $harga, $kuantitas, $berat);

        if ($stmt->execute()) {
            echo 'Item berhasil ditambahkan ke keranjang';
        } else {
            echo 'Gagal menambahkan item ke keranjang';
        }
    }

    $stmt->close();
    $con->close();
}
?>
