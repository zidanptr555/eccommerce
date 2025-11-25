<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = intval($_GET['order_id'] ?? 0);

// Ambil data order
$order_q = mysqli_query($conn, "SELECT * FROM orders WHERE id=$order_id AND user_id={$_SESSION['user_id']}");
$order = mysqli_fetch_assoc($order_q);

if (!$order) {
    die("Order tidak ditemukan.");
}

// Ambil produk di order
$items_q = mysqli_query($conn, "
    SELECT oi.qty, oi.price, p.name, p.image 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = $order_id
");

// Hitung waktu expired (24 jam sejak dibuat)
$created_time = strtotime($order['created_at'] ?? date('Y-m-d H:i:s'));
$expiry_time = $created_time + 24*3600;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Pembayaran</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
// Countdown 24 jam
let expiry = <?= $expiry_time * 1000 ?>;
function countdown() {
    const now = new Date().getTime();
    let distance = expiry - now;

    if(distance < 0) {
        document.getElementById("countdown").innerHTML = "Waktu pembayaran telah habis!";
        return;
    }

    let h = Math.floor((distance % (1000*60*60*24))/(1000*60*60));
    let m = Math.floor((distance % (1000*60*60))/(1000*60));
    let s = Math.floor((distance % (1000*60))/1000);

    document.getElementById("countdown").innerHTML = h + "h " + m + "m " + s + "s";
    setTimeout(countdown, 1000);
}
window.onload = countdown;
</script>
</head>
<body class="bg-purple-50 min-h-screen">

<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-lg">

<h1 class="text-2xl font-bold text-ugpurple mb-4">Detail Pembayaran</h1>

<p><strong>Order ID:</strong> <?= $order['id']; ?></p>
<p><strong>Alamat:</strong> <?= $order['alamat_penerima']; ?></p>
<p><strong>Metode:</strong> <?= $order['metode']; ?></p>
<p><strong>Ongkir:</strong> Rp<?= number_format($order['ongkir'],0,',','.'); ?></p>
<p><strong>Total:</strong> Rp<?= number_format($order['total_akhir'],0,',','.'); ?></p>
<p class="text-red-600 font-semibold mt-2">
    Waktu pembayaran tersisa: <span id="countdown"></span>
</p>

<hr class="my-4">

<h2 class="text-xl font-bold mb-2">Produk dalam Order</h2>
<?php while($item = mysqli_fetch_assoc($items_q)): ?>
<div class="flex items-center mb-3 bg-white shadow rounded p-3">
    <img src="assets/img/<?= $item['image'] ?>" class="w-20 h-20 object-cover rounded mr-4">
    <div class="flex-1">
        <p class="font-semibold"><?= $item['name'] ?></p>
        <p>Jumlah: <?= $item['qty'] ?></p>
        <p>Harga: Rp<?= number_format($item['price'],0,',','.') ?></p>
        <p>Subtotal: Rp<?= number_format($item['qty']*$item['price'],0,',','.') ?></p>
    </div>
</div>
<?php endwhile; ?>

<hr class="my-4">

<?php if (in_array(strtolower($order['metode']), ['transfer bank','transfer'])): ?>
<div class="bg-blue-50 p-4 rounded mb-4">
    <h3 class="font-bold text-blue-600">Instruksi Transfer Bank</h3>
    <p>Silahkan transfer ke rekening berikut:</p>
    <p><b>Bank BCA</b> - 1234567890 a.n Toko Kamu</p>
    <p>Pembayaran harus diselesaikan dalam 24 jam.</p>
</div>
<?php elseif (strtolower($order['metode']) == 'e-wallet'): ?>
<div class="bg-purple-50 p-4 rounded mb-4">
    <h3 class="font-bold text-purple-600">Instruksi E-Wallet</h3>
    <p>Silahkan bayar melalui link berikut:</p>
    <a href="https://linkpembayaran-kamu.com/<?= $order['id'] ?>" class="block bg-purple-600 text-white text-center py-2 rounded-lg mt-2">
        Bayar via E-Wallet
    </a>
</div>
<?php elseif (strtolower($order['metode']) == 'cod'): ?>
<div class="bg-green-50 p-4 rounded mb-4">
    <h3 class="font-bold text-green-600">Cash on Delivery (COD)</h3>
    <p>Siapkan uang cash sebesar Rp<?= number_format($order['total_akhir'],0,',','.'); ?></p>
</div>
<?php endif; ?>

<a href="index.php" class="block mt-6 bg-gray-700 text-white text-center py-2 rounded-lg hover:bg-gray-800">Kembali ke Beranda</a>

</div>
</body>
</html>
