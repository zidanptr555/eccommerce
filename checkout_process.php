<?php 
session_start();
include 'config.php';

// ==== WAJIB LOGIN ====
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ==== CEK KERANJANG ====
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

// ==== Ambil data dari checkout.php ====
$nama     = $_POST['nama_penerima'];
$hp       = $_POST['hp_penerima'];
$alamat   = $_POST['alamat_penerima'];
$tujuan   = $_POST['tujuan'];

// ==== Ambil asal daerah toko dari produk pertama ====
$asal_toko = "Jakarta";
foreach ($_SESSION['cart'] as $pid => $qty) {
    $q = mysqli_query($conn, "SELECT store_area FROM products WHERE id = $pid");
    $p = mysqli_fetch_assoc($q);

    if ($p) {
        $asal_toko = $p['store_area'];
    }
    break;
}

// ==== Tabel Ongkir ====
$ongkirTable = [
    "Jakarta" => ["Bogor"=>10000, "Depok"=>7000, "Tangerang"=>10000, "Bekasi"=>7000],
    "Bogor" => ["Jakarta"=>10000, "Depok"=>8000, "Tangerang"=>12000, "Bekasi"=>10000],
    "Depok" => ["Jakarta"=>7000, "Bogor"=>8000, "Tangerang"=>9000, "Bekasi"=>6000],
    "Tangerang" => ["Jakarta"=>10000, "Bogor"=>12000, "Depok"=>9000, "Bekasi"=>11000],
    "Bekasi" => ["Jakarta"=>7000, "Bogor"=>10000, "Depok"=>6000, "Tangerang"=>11000],
];

// ==== Validasi Ongkir ====
if (!isset($ongkirTable[$asal_toko][$tujuan])) {
    echo "<script>alert('Ongkir tidak tersedia!'); window.location='checkout.php';</script>";
    exit();
}

$ongkir = $ongkirTable[$asal_toko][$tujuan];

// ==== Hitung total belanja ====
$totalBelanja = 0;
foreach ($_SESSION['cart'] as $product_id => $qty) {
    $q = mysqli_query($conn, "SELECT price FROM products WHERE id = $product_id");
    $p = mysqli_fetch_assoc($q);

    $totalBelanja += $p['price'] * $qty;
}

$totalAkhir = $totalBelanja + $ongkir;

// ==== SIMPAN ORDER ====
$user_id = $_SESSION['user_id'];

mysqli_query($conn, "
    INSERT INTO orders 
    (user_id, nama_penerima, hp_penerima, alamat_penerima, kota_toko, kota_tujuan, ongkir, total_belanja, total_akhir, status) 
    VALUES 
    ('$user_id', '$nama', '$hp', '$alamat', '$asal_toko', '$tujuan', '$ongkir', '$totalBelanja', '$totalAkhir', 'Menunggu Pembayaran')
");

$order_id = mysqli_insert_id($conn);

// ==== SIMPAN ITEM PESANAN ====
foreach ($_SESSION['cart'] as $product_id => $qty) {
    $q = mysqli_query($conn, "SELECT price FROM products WHERE id = $product_id");
    $p = mysqli_fetch_assoc($q);
    $harga = $p['price'];

    mysqli_query($conn, "
        INSERT INTO order_items (order_id, product_id, qty, price)
        VALUES ($order_id, $product_id, $qty, $harga)
    ");
}

// ❗ Jangan hapus cart dulu — baru hapus setelah payment_success
// unset($_SESSION['cart']);  // DIHAPUS

// ==== Redirect ke Payment ====
header("Location: payment.php?order_id=$order_id&nama=$nama&hp=$hp&alamat=$alamat&asal=$asal_toko&tujuan=$tujuan&ongkir=$ongkir&total=$totalAkhir");
exit();
?>
