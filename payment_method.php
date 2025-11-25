<?php
include 'config.php';
session_start();

$id = $_GET['id'] ?? 0;

$order = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT * FROM orders WHERE id = $id"));

if (!$order) {
    die("Order tidak ditemukan.");
}

$metode = $order['metode'];
$total  = $order['total_akhir'];

// Waktu pembayaran 24 jam sejak order dibuat
$order_time = strtotime($order['created_at']); // pastikan ada kolom created_at
$deadline   = $order_time + 24*60*60;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Instruksi Pembayaran</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-xl">

<h2 class="text-2xl font-bold mb-4">Instruksi Pembayaran</h2>

<p class="mb-3 text-gray-600">ID Order: <b><?= $id ?></b></p>
<p class="mb-5 text-gray-600">Total yang harus dibayar: 
   <b>Rp<?= number_format($total, 0, ',', '.') ?></b>
</p>

<?php if (in_array($metode, ["Transfer Bank", "E-Wallet"])): ?>

    <?php if ($metode == "Transfer Bank"): ?>
        <h3 class="text-xl font-bold text-blue-600 mb-2">Transfer Bank</h3>
        <p class="mb-2">Silahkan transfer ke nomor rekening berikut:</p>

        <div class="bg-blue-50 p-4 rounded-lg mb-4">
            <p><b>Bank BCA</b></p>
            <p>No Rek: <b>1234567890</b></p>
            <p>a.n <b>Toko Kamu</b></p>
        </div>
    <?php elseif ($metode == "E-Wallet"): ?>
        <h3 class="text-xl font-bold text-purple-600 mb-2">E-Wallet Payment</h3>
        <p class="mb-3">Silahkan klik link berikut untuk membayar:</p>

        <a href="https://linkpembayaran-kamu.com/<?= $id ?>"
           class="block bg-purple-600 text-white text-center py-2 px-4 rounded-lg mb-4">
            Bayar via E-Wallet
        </a>
    <?php endif; ?>

    <p class="mb-2">Pembayaran harus diselesaikan dalam:</p>
    <div id="countdown" class="text-red-600 font-semibold text-lg mb-4"></div>

<?php elseif ($metode == "COD"): ?>

    <h3 class="text-xl font-bold text-green-600 mb-2">Cash on Delivery (COD)</h3>
    <p class="mb-4">
        Silahkan siapkan uang cash sebesar:
        <b>Rp<?= number_format($total, 0, ',', '.') ?></b>
    </p>

    <p class="text-gray-600">
        Kurir akan menghubungi kamu sebelum barang dikirim.
    </p>

<?php endif; ?>

<div class="mt-6">
    <a href="index.php" 
       class="block text-center bg-gray-700 hover:bg-gray-800 text-white py-2 px-4 rounded-lg">
       Kembali ke Beranda
    </a>
</div>

</div>

<?php if (in_array($metode, ["Transfer Bank", "E-Wallet"])): ?>
<script>
// Set deadline dari PHP
var countDownDate = new Date("<?= date('Y-m-d H:i:s', $deadline) ?>").getTime();

// Update setiap 1 detik
var x = setInterval(function() {
  var now = new Date().getTime();
  var distance = countDownDate - now;

  if (distance < 0) {
    clearInterval(x);
    document.getElementById("countdown").innerHTML = "Waktu pembayaran telah habis!";
  } else {
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    document.getElementById("countdown").innerHTML = hours + " jam "
      + minutes + " menit " + seconds + " detik";
  }
}, 1000);
</script>
<?php endif; ?>

</body>
</html>
