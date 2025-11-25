<?php
session_start();
include 'config.php';

// ==== WAJIB LOGIN ====
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ==== AMBIL DATA USER ====
$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? '';

// ==== AMBIL DATA DARI FORM ====
$nama_penerima   = $_POST['nama'] ?? '';
$hp_penerima     = $_POST['hp'] ?? '';
$alamat_penerima = $_POST['alamat'] ?? '';
$kota_toko       = $_POST['asal'] ?? '';
$kota_tujuan     = $_POST['tujuan'] ?? '';
$ongkir          = intval($_POST['ongkir'] ?? 0);
$total           = intval($_POST['total'] ?? 0);
$metode          = $_POST['metode'] ?? '';

// ==== Pastikan metode pembayaran string ====
if (is_array($metode)) {
    $metode = implode(", ", $metode);
}

// Escape untuk aman
$nama_penerima   = mysqli_real_escape_string($conn, $nama_penerima);
$hp_penerima     = mysqli_real_escape_string($conn, $hp_penerima);
$alamat_penerima = mysqli_real_escape_string($conn, $alamat_penerima);
$kota_toko       = mysqli_real_escape_string($conn, $kota_toko);
$kota_tujuan     = mysqli_real_escape_string($conn, $kota_tujuan);
$metode          = mysqli_real_escape_string($conn, $metode);

// ==== Hitung total akhir ====
$total_akhir = $total + $ongkir;

// ==== INSERT ORDER ====
$qInsert = "
INSERT INTO orders 
(user_id, username, nama_penerima, hp_penerima, alamat_penerima,
 kota_toko, kota_tujuan, ongkir, total_belanja, total_akhir, metode, status)
VALUES
('$user_id', '$username', '$nama_penerima', '$hp_penerima', '$alamat_penerima',
 '$kota_toko', '$kota_tujuan', '$ongkir', '$total', '$total_akhir', '$metode', 'Menunggu Pembayaran')
";

mysqli_query($conn, $qInsert);
$order_id = mysqli_insert_id($conn);

// ==== INSERT ORDER ITEMS ====
foreach ($_SESSION['cart'] as $pid => $item) {
    $qty   = intval($item['qty']);
    $price = floatval($item['price']);

    mysqli_query($conn, "
        INSERT INTO order_items (order_id, product_id, qty, price)
        VALUES ($order_id, $pid, $qty, $price)
    ");
}



// ==== HAPUS KERANJANG ====
unset($_SESSION['cart']);

// ==== REDIRECT KE HALAMAN PAYMENT ====
header("Location: payment_method.php?id=$order_id");
exit();
?>
