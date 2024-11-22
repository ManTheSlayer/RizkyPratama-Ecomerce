<?php
// Ambil ID dari data yang dikirimkan melalui POST
if(isset($_POST['id'])) {
  $id = $_POST['id'];
  // Lakukan koneksi dan query untuk memindahkan data dari proses ke kemas
  include("../../php/config.php");
  // Misalnya, Anda dapat menggunakan query INSERT INTO ... SELECT ... untuk memindahkan data
  $query = "UPDATE pesanan SET status_pesanan = 'selesai' WHERE id = '$id'";
  if(mysqli_query($con, $query)) {
    echo "success";
  } else {
    echo "Gagal memindahkan data: " . mysqli_error($con);
  }
  
  mysqli_close($con);
}
?>
