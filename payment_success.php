<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id   = $_SESSION['user_id'];
$username  = $_SESSION['username'];

// Ambil data POST dari payment.php
$nama       = $_POST['nama'] ?? '';
$hp         = $_POST['hp'] ?? '';
$alamat     = $_POST['alamat'] ?? '';
$asal_toko  = $_POST['asal'] ?? '';
$tujuan     = $_POST['tujuan'] ?? '';
$ongkir     = intval($_POST['ongkir'] ?? 0);
$total      = intval($_POST['total'] ?? 0);
$metode     = $_POST['metode'] ?? '';

// Hitung total akhir
$total_akhir = $total + $ongkir;

// ===== INSERT ke tabel orders =====
$query = "INSERT INTO orders 
        (user_id, username, nama_penerima, hp_penerima, alamat_penerima,
         kota_toko, kota_tujuan, ongkir, total_belanja, total_akhir, status)
        VALUES 
        ('$user_id', '$username', '$nama', '$hp', '$alamat',
         '$asal_toko', '$tujuan', '$ongkir', '$total', '$total_akhir', 'Menunggu Pembayaran')";

$insertOrder = mysqli_query($conn, $query);

if (!$insertOrder) {
    die("Gagal menyimpan data order: " . mysqli_error($conn));
}

$order_id = mysqli_insert_id($conn);

// ===== INSERT ke order_items (AMAN) =====
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $product_id => $qty) {

        $product_id = intval($product_id);
        $qty        = intval($qty);

        if ($qty <= 0) continue;

        // Ambil data produk
        $q = mysqli_query($conn, "SELECT price, name FROM products WHERE id = $product_id LIMIT 1");
        $p = mysqli_fetch_assoc($q);

        if (!$p) continue;

        $price = intval($p['price']);

        // Insert item pesanannya
        mysqli_query($conn, 
            "INSERT INTO order_items (order_id, product_id, qty, price) 
             VALUES ($order_id, $product_id, $qty, $price)"
        );
    }

} else {
    // Keranjang kosong → hindari error
    error_log("Cart kosong pada payment_success untuk user $user_id");
}


// Hapus keranjang setelah semua item disimpan
unset($_SESSION['cart']);
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran Berhasil</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-xl">
    <h2 class="text-2xl font-bold text-green-600 mb-4">Pembayaran Diproses!</h2>
    <p class="text-gray-700 mb-6">
        Terima kasih! Pesanan kamu sedang menunggu konfirmasi pembayaran.
    </p>

    <div class="border rounded-lg p-4 bg-gray-50">
        <p><strong>ID Order:</strong> <?= $order_id ?></p>
        <p><strong>Nama Penerima:</strong> <?= $nama ?></p>
        <p><strong>No. HP:</strong> <?= $hp ?></p>
        <p><strong>Alamat:</strong> <?= $alamat ?></p>
        <p><strong>Asal Toko:</strong> <?= $asal_toko ?></p>
        <p><strong>Tujuan:</strong> <?= $tujuan ?></p>
        <p><strong>Ongkir:</strong> Rp<?= number_format($ongkir, 0, ',', '.') ?></p>
        <p><strong>Total Belanja:</strong> Rp<?= number_format($total, 0, ',', '.') ?></p>
        <p><strong>Total Akhir:</strong> Rp<?= number_format($total_akhir, 0, ',', '.') ?></p>
        <p><strong>Status:</strong> <span class="text-yellow-600 font-semibold">Menunggu Pembayaran</span></p>

        <hr class="my-4">
        <p class="font-semibold mb-2">Produk dalam Pesanan:</p>
        <ul class="list-disc pl-5">
            <?php
            $items = mysqli_query($conn, 
                "SELECT oi.qty, oi.price, p.name 
                 FROM order_items oi 
                 JOIN products p ON oi.product_id = p.id
                 WHERE oi.order_id = $order_id"
            );
            while($item = mysqli_fetch_assoc($items)) {
                echo "<li>{$item['name']} x{$item['qty']} - Rp" . number_format($item['price'], 0, ',', '.') . "</li>";
            }
            ?>
        </ul>
    </div>

    <div class="mt-6">
        <a href="index.php" 
           class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">
           Kembali ke Beranda
        </a>
    </div>
</div>

</body>
</html>
