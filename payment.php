<?php
session_start();
include 'config.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ===================================
// AMBIL ORDER_ID DARI URL
// ===================================
if (!isset($_GET['order_id'])) {
    echo "Order ID tidak ditemukan!";
    exit();
}

$order_id = intval($_GET['order_id']);

// ===================================
// AMBIL DATA ORDER DARI DATABASE
// ===================================
$q = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id LIMIT 1");
if (mysqli_num_rows($q) == 0) {
    echo "Data pesanan tidak ditemukan!";
    exit();
}

$order = mysqli_fetch_assoc($q);

// Masukkan ke variabel agar mudah dipakai di HTML
$nama      = $order['nama_penerima'];
$hp        = $order['hp_penerima'];
$alamat    = $order['alamat_penerima'];
$asal_toko = $order['kota_toko'];
$tujuan    = $order['kota_tujuan'];
$ongkir    = $order['ongkir'];
$total     = $order['total_akhir'];

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran - UGSHOP</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: { ugpurple: '#7b2cbf', ugpurplelight: '#9d4edd' }
      }
    }
  }
</script>
</head>

<body class="bg-purple-50 min-h-screen">

<nav class="bg-white shadow-md p-4 flex justify-between items-center sticky top-0 z-50">
  <h1 class="text-2xl font-bold text-ugpurple">💰 Pembayaran</h1>
  <a href="index.php" class="text-ugpurple hover:underline">Kembali ke Beranda</a>
</nav>

<div class="max-w-3xl mx-auto bg-white mt-8 p-8 rounded-2xl shadow-lg">

  <h2 class="text-xl font-bold text-ugpurple mb-4">Ringkasan Pesanan</h2>

  <div class="space-y-2 text-gray-700">
    <p><b>Nama:</b> <?= $nama ?></p>
    <p><b>HP:</b> <?= $hp ?></p>
    <p><b>Alamat:</b> <?= $alamat ?></p>
    <p><b>Asal Toko:</b> <?= $asal_toko ?></p>
    <p><b>Tujuan:</b> <?= $tujuan ?></p>
    <p><b>Ongkir:</b> Rp <?= number_format($ongkir, 0, ',', '.') ?></p>
    <p class="text-lg font-bold text-ugpurple">
      Total yang harus dibayar:
      Rp <?= number_format($total, 0, ',', '.') ?>
    </p>
  </div>

  <hr class="my-6">

  <h2 class="text-xl font-bold text-ugpurple mb-3">Pilih Metode Pembayaran</h2>

  <form action="payment_success.php" method="POST" class="space-y-4">

    <!-- KIRIM DATA KE payment_success.php -->
    <input type="hidden" name="order_id" value="<?= $order_id ?>">
    <input type="hidden" name="nama" value="<?= $nama ?>">
    <input type="hidden" name="hp" value="<?= $hp ?>">
    <input type="hidden" name="alamat" value="<?= $alamat ?>">
    <input type="hidden" name="total" value="<?= $total ?>">

    <div class="space-y-3">

      <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-purple-100 cursor-pointer">
        <input type="radio" name="metode" value="Transfer Bank" required>
        <span class="font-semibold">🏦 Transfer Bank</span>
      </label>

      <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-purple-100 cursor-pointer">
        <input type="radio" name="metode" value="E-Wallet">
        <span class="font-semibold">📱 E-Wallet (Dana, OVO, Gopay)</span>
      </label>

      <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-purple-100 cursor-pointer">
        <input type="radio" name="metode" value="COD">
        <span class="font-semibold">🚪 COD (Bayar di Tempat)</span>
      </label>

    </div>

    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-bold mt-4">
      Konfirmasi Pembayaran
    </button>

  </form>

</div>

</body>
</html>
