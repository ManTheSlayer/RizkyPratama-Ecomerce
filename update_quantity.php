<?php
// Mulai sesi
session_start();

// Inklusi file koneksi database
include("php/config.php");

// Periksa apakah permintaan adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari permintaan POST
    $cart_id = $_POST['cart_id'];
    $cart_quantity = $_POST['cart_quantity'];

    // Validasi data yang diterima
    if (is_numeric($cart_id) && is_numeric($cart_quantity) && $cart_quantity > 0) {
        // Update kuantitas di database
        $query = "UPDATE keranjang SET kuantitas = ? WHERE id = ? AND id_user = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('iii', $cart_quantity, $cart_id, $_SESSION['id_user']);
        
        if ($stmt->execute()) {
            $response = array('success' => true);
        } else {
            $response = array('success' => false, 'message' => 'Gagal memperbarui kuantitas');
        }
        $stmt->close();
    } else {
        $response = array('success' => false, 'message' => 'Data tidak valid');
    }

    // Mengembalikan respons dalam format JSON
    echo json_encode($response);
}
?>
