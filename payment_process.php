<?php
session_start();
include 'config.php';

// ==== WAJIB LOGIN ====
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || empty($_SESSION['username'])) {
    die("Error: Silakan login terlebih dahulu sebelum checkout.");
}

$user_id = intval($_SESSION['user_id']);
$username = trim($_SESSION['username']);
if ($username === '') die("Error: Username tidak boleh kosong.");

// ==== AMBIL DATA FORM ====
$nama_penerima = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
$hp_penerima = mysqli_real_escape_string($conn, $_POST['hp'] ?? '');
$alamat_penerima = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
$kota_toko = mysqli_real_escape_string($conn, $_POST['asal'] ?? '');
$kota_tujuan = mysqli_real_escape_string($conn, $_POST['tujuan'] ?? '');
$ongkir = intval($_POST['ongkir'] ?? 0);
$total = intval($_POST['total'] ?? 0);
$metode = trim($_POST['metode'] ?? '');

if ($metode === '') die("Error: Metode pembayaran harus dipilih.");

// ==== INSERT ORDER ====
$total_akhir = $total + $ongkir;

$qInsert = "
INSERT INTO orders
(user_id, username, nama_penerima, hp_penerima, alamat_penerima, kota_toko, kota_tujuan, ongkir, total_belanja, total_akhir, metode, status)
VALUES
('$user_id', '$username', '$nama_penerima', '$hp_penerima', '$alamat_penerima', '$kota_toko', '$kota_tujuan', '$ongkir', '$total', '$total_akhir', '$metode', 'Menunggu Pembayaran')
";
mysqli_query($conn, $qInsert);
$order_id = mysqli_insert_id($conn);

// ==== MERGE ITEM DUPLIKAT DI CART ====
$mergedCart = [];
foreach ($_SESSION['cart'] as $pid => $item) {
    $pid = intval($pid);
    if (isset($mergedCart[$pid])) {
        $mergedCart[$pid]['qty'] += intval($item['qty']);
    } else {
        $mergedCart[$pid] = [
            'qty' => intval($item['qty']),
            'price' => floatval($item['price'])
        ];
    }
}

// ==== INSERT ORDER ITEMS ====
if (!empty($mergedCart)) {
    $values = [];
    foreach ($mergedCart as $pid => $item) {
        $qty = $item['qty'];
        $price = $item['price'];
        $values[] = "($order_id, $pid, $qty, $price)";
    }
    $sqlItems = "INSERT INTO order_items (order_id, product_id, qty, price) VALUES " . implode(", ", $values);
    mysqli_query($conn, $sqlItems);
}

// ==== HAPUS KERANJANG ====
unset($_SESSION['cart']);

// ==== REDIRECT KE PAYMENT ====
header("Location: payment_method.php?id=$order_id");
exit();
?>
