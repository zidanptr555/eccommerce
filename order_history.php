<?php
session_start();
include 'config.php';

// ==== WAJIB LOGIN ====
$user_id = intval($_SESSION['user_id'] ?? 0);
if ($user_id <= 0) {
    header("Location: login.php");
    exit();
}

$search = $_GET['search'] ?? '';

// ==== QUERY ORDER USER ====
$query = "
    SELECT o.id AS order_id, o.alamat_penerima, o.metode, o.ongkir, o.total_akhir, o.status,
           p.name AS product_name, oi.qty AS product_qty
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.user_id = $user_id
";

if (!empty($search)) {
    $search_safe = mysqli_real_escape_string($conn, $search);
    $query .= " AND p.name LIKE '%$search_safe%'";
}

$query .= " ORDER BY o.id DESC";

$result = mysqli_query($conn, $query);

// ==== SUSUN DATA ORDER ====
$orders = [];
while ($row = mysqli_fetch_assoc($result)) {
    $orders[$row['order_id']]['info'] = [
        'alamat_penerima' => $row['alamat_penerima'],
        'metode' => $row['metode'],
        'ongkir' => $row['ongkir'],
        'total_akhir' => $row['total_akhir'],
        'status' => $row['status']
    ];
    $orders[$row['order_id']]['products'][] = [
        'name' => $row['product_name'],
        'qty' => $row['product_qty']
    ];
}

// ==== FUNGSIONALITAS WARNA STATUS ====
function statusColor($status) {
    return match($status) {
        'Menunggu Pembayaran' => 'bg-yellow-400 text-black',
        'Paket Diproses' => 'bg-blue-500 text-white',
        'Paket Diantarkan' => 'bg-orange-500 text-white',
        'Paket Telah Tiba' => 'bg-green-600 text-white',
        default => 'bg-gray-400 text-white',
    };
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Pesanan</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="max-w-4xl mx-auto mt-10">

<h1 class="text-3xl font-bold mb-6 text-gray-800">Riwayat Pesanan</h1>

<!-- Button Kembali ke Beranda -->
<div class="mb-6">
    <a href="index.php" 
       class="inline-block bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
       ← Kembali ke Beranda
    </a>
</div>

<!-- Form Pencarian -->
<form method="GET" class="mb-6 flex gap-2">
    <input type="text" name="search" placeholder="Cari produk..." 
           value="<?= htmlspecialchars($search); ?>" 
           class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
        Cari
    </button>
</form>

<?php if (empty($orders)): ?>
<div class="bg-white p-6 rounded-xl shadow text-center text-gray-600">
    Tidak ada pesanan<?= $search ? " untuk '" . htmlspecialchars($search) . "'" : "" ?>.
</div>
<?php else: ?>
<div class="space-y-6">
    <?php foreach ($orders as $order_id => $data): ?>
    <div class="bg-white shadow rounded-xl p-4">
        <div class="flex justify-between items-center mb-2">
            <h2 class="font-semibold text-lg">Order ID: <?= $order_id; ?></h2>
            <a href="payment_detail.php?order_id=<?= $order_id; ?>" 
               class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
               Detail
            </a>
        </div>

        <p><strong>Alamat:</strong> <?= htmlspecialchars($data['info']['alamat_penerima']); ?></p>
        <p><strong>Metode:</strong> <?= htmlspecialchars($data['info']['metode']); ?></p>
        <p><strong>Ongkir:</strong> Rp<?= number_format($data['info']['ongkir'],0,',','.'); ?></p>
        <p><strong>Total:</strong> Rp<?= number_format($data['info']['total_akhir'],0,',','.'); ?></p>

        <p class="mt-2"><strong>Produk:</strong></p>
        <ul class="ml-4 list-disc">
            <?php foreach ($data['products'] as $prod): ?>
                <li><?= htmlspecialchars($prod['name']); ?> x <?= intval($prod['qty']); ?></li>
            <?php endforeach; ?>
        </ul>

        <p class="mt-2">
            <span class="px-3 py-1 rounded-full <?= statusColor($data['info']['status']); ?>">
                <?= htmlspecialchars($data['info']['status']); ?>
            </span>
        </p>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

</div>
</body>
</html>
