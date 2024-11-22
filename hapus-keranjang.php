<?php
include("php/config.php");
session_start();

if(isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $delete_query = mysqli_query($con, "DELETE FROM keranjang WHERE id = '$id'");
    if($delete_query) {
        echo "Item berhasil dihapus dari keranjang.";
    } else {
        echo "Gagal menghapus item.";
    }
}

?>