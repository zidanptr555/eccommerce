<?php
session_start();
include 'config.php';

// ==== CEK LOGIN ====
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ==== CEK KERANJANG ====
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Keranjang kosong!'); window.location='cart.php';</script>";
    exit();
}

// ==== HITUNG TOTAL BELANJA + DAPATKAN ASAL TOKO ====
$totalBelanja = 0;
$asal_toko = "Jakarta"; // default fallback

foreach ($_SESSION['cart'] as $product_id => $item) {
    $q = mysqli_query($conn, "SELECT name, price, promo_price, is_promo, store_area FROM products WHERE id = $product_id");
    $p = mysqli_fetch_assoc($q);

    $hargaProduk = ($p['is_promo'] == 1) ? $p['promo_price'] : $p['price'];
    $subtotal = $hargaProduk * $item['qty'];
    $totalBelanja += $subtotal;

    // Ambil store_area dari produk pertama
    $asal_toko = $p['store_area'];
    break;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - UGSHOP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { ugpurple: '#7b2cbf', ugpurplelight: '#9d4edd' } } }
        };
    </script>

    <script>
        function hitungOngkir() {
            let asal = document.getElementById("asal_toko").value;
            let tujuan = document.getElementById("tujuan").value;

            const ongkirRules = {
                "Jakarta-Bogor": 10000, "Bogor-Jakarta": 10000,
                "Jakarta-Depok": 7000, "Depok-Jakarta": 7000,
                "Jakarta-Tangerang": 10000, "Tangerang-Jakarta": 10000,
                "Jakarta-Bekasi": 7000, "Bekasi-Jakarta": 7000,
                "Bogor-Depok": 8000, "Depok-Bogor": 8000,
                "Bogor-Tangerang": 12000, "Tangerang-Bogor": 12000,
                "Bogor-Bekasi": 12000, "Bekasi-Bogor": 12000,
                "Depok-Tangerang": 9000, "Tangerang-Depok": 9000,
                "Depok-Bekasi": 9000, "Bekasi-Depok": 9000,
                "Tangerang-Bekasi": 11000, "Bekasi-Tangerang": 11000
            };

            let key = asal + "-" + tujuan;
            let ongkir = ongkirRules[key] ?? 0;

            document.getElementById("ongkir_value").innerText = "Rp " + ongkir.toLocaleString();

            let totalBelanja = <?= $totalBelanja ?>;
            let totalAkhir = totalBelanja + ongkir;

            document.getElementById("total_akhir").innerText = "Rp " + totalAkhir.toLocaleString();
            document.getElementById("ongkir_hidden").value = ongkir;
        }
    </script>
</head>

<body class="bg-purple-50 min-h-screen">

<!-- NAVBAR -->
<nav class="bg-white shadow-md p-4 flex justify-between items-center sticky top-0 z-50">
    <h1 class="text-2xl font-bold text-ugpurple">🧾 Checkout UGSHOP</h1>
    <a href="cart.php" class="bg-ugpurple text-white px-4 py-2 rounded-md hover:bg-ugpurplelight">← Kembali</a>
</nav>

<div class="max-w-4xl mx-auto mt-8 bg-white shadow-xl p-6 rounded-2xl">

    <h2 class="text-xl font-bold text-ugpurple mb-4">Produk yang Dibeli</h2>

    <?php
    echo "<div class='space-y-2'>";
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $q = mysqli_query($conn, "SELECT name, price, promo_price, is_promo FROM products WHERE id=$product_id");
        $p = mysqli_fetch_assoc($q);

        $hargaProduk = ($p['is_promo'] == 1) ? $p['promo_price'] : $p['price'];
        $subtotal = $hargaProduk * $item['qty'];

        echo "<div class='flex justify-between p-2 border rounded-md'>";
        echo "<div><b>{$p['name']}</b> × {$item['qty']}</div>";
        if ($p['is_promo'] == 1) {
            echo "<div>Rp " . number_format($hargaProduk,0,',','.') . " (Promo!)</div>";
        } else {
            echo "<div>Rp " . number_format($hargaProduk,0,',','.') . "</div>";
        }
        echo "</div>";
    }
    echo "</div>";
    ?>

    <hr class="my-4">

    <h2 class="text-xl font-bold text-ugpurple mb-4">Data Penerima</h2>

    <form action="checkout_process.php" method="POST" class="space-y-4">

        <div>
            <label class="font-semibold">Nama Penerima</label>
            <input required name="nama_penerima" type="text" class="w-full p-2 border rounded-md mt-1">
        </div>

        <div>
            <label class="font-semibold">Nomor HP Penerima</label>
            <input required name="hp_penerima" type="text" class="w-full p-2 border rounded-md mt-1">
        </div>

        <div>
            <label class="font-semibold">Alamat Lengkap</label>
            <textarea required name="alamat_penerima" class="w-full p-3 border rounded-md h-24 mt-1"></textarea>
        </div>

        <div>
            <label class="font-semibold">Asal Daerah Toko</label>
            <input id="asal_toko" value="<?= $asal_toko ?>" readonly class="w-full p-2 border bg-gray-100 rounded-md mt-1 font-bold">
        </div>

        <div>
            <label class="font-semibold flex items-center gap-2"><span class="text-2xl">🚚</span> Tujuan Pengiriman</label>
            <select id="tujuan" name="tujuan" required class="w-full p-2 border rounded-md mt-1" onchange="hitungOngkir()">
                <option value="">-- Pilih Kota --</option>
                <option>Jakarta</option>
                <option>Bogor</option>
                <option>Depok</option>
                <option>Tangerang</option>
                <option>Bekasi</option>
            </select>
        </div>

        <input type="hidden" id="ongkir_hidden" name="ongkir">

        <hr class="my-4">

        <h2 class="text-lg font-bold text-ugpurple">Ringkasan Pembayaran</h2>
        <p>Total Belanja: <b>Rp <?= number_format($totalBelanja,0,',','.') ?></b></p>
        <p>Ongkir: <b id="ongkir_value">Rp 0</b></p>
        <p class="text-xl font-bold text-ugpurple">Total Akhir: <span id="total_akhir">Rp <?= number_format($totalBelanja,0,',','.') ?></span></p>

        <button class="w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 mt-4" type="submit">Bayar Sekarang</button>

    </form>
</div>

</body>
</html>
