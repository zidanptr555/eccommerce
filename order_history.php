<?php
include 'config.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil semua pesanan milik user
$query = "
    SELECT o.*, p.name AS product_name, p.image AS product_image
    FROM orders o
    JOIN products p ON o.product_id = p.id
    WHERE o.user_id = $user_id
    ORDER BY o.id DESC
";

$result = mysqli_query($conn, $query);
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

    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="bg-white p-6 rounded-xl shadow text-center text-gray-600">
            Kamu belum memiliki pesanan.
        </div>
    <?php else: ?>

        <div class="space-y-6">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="flex bg-white shadow rounded-xl p-4">

                    <!-- Gambar Produk -->
                    <img src="uploads/<?= $row['product_image']; ?>" 
                         class="w-24 h-24 object-cover rounded-lg mr-4">

                    <div class="flex-1">
                        <h2 class="text-xl font-semibold"><?= $row['product_name']; ?></h2>

                        <p class="text-gray-600 text-sm">
                            <strong>Order ID:</strong> <?= $row['id']; ?>
                        </p>

                        <p class="text-gray-600 text-sm">
                            <strong>Alamat Pengiriman:</strong> <?= $row['receiver_address']; ?>
                        </p>

                        <p class="text-gray-600 text-sm">
                            <strong>Metode Pengiriman:</strong> <?= $row['shipping_method']; ?>
                        </p>

                        <p class="text-gray-600 text-sm">
                            <strong>Ongkir:</strong> Rp<?= number_format($row['shipping_cost'], 0, ',', '.'); ?>
                        </p>

                        <p class="text-gray-600 text-sm">
                            <strong>Total Harga:</strong> Rp<?= number_format($row['total_price'], 0, ',', '.'); ?>
                        </p>

                        <p class="mt-2">
                            <span class="px-3 py-1 rounded-full text-white
                                <?php if ($row['payment_status'] == 'paid'): ?>
                                    bg-green-600
                                <?php else: ?>
                                    bg-yellow-600
                                <?php endif; ?>
                            ">
                                <?= ucfirst($row['payment_status']); ?>
                            </span>
                        </p>
                    </div>

                    <!-- Tombol Detail -->
                    <div class="flex flex-col justify-center ml-4">
                        <a href="payment_success.php?order_id=<?= $row['id']; ?>"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            Detail
                        </a>
                    </div>

                </div>
            <?php endwhile; ?>
        </div>

    <?php endif; ?>

</div>

</body>
</html>
