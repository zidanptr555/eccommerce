<?php
session_start();
include 'config.php';

// Ambil ID kategori dari URL
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data kategori
$category_q = mysqli_query($conn, "SELECT * FROM categories WHERE id=$category_id");
if(mysqli_num_rows($category_q) == 0){
    die("Kategori tidak ditemukan!");
}
$category = mysqli_fetch_assoc($category_q);

// Ambil semua produk kategori ini beserta deskripsi kategori
$products_q = mysqli_query($conn, "
    SELECT p.*, c.description AS category_desc
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.category_id = $category_id
    ORDER BY p.id DESC
");

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $category['name'] ?> - UGSHOP</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="max-w-5xl mx-auto mt-10 px-4">

    <!-- Judul Kategori & Deskripsi -->
    <h1 class="text-3xl font-bold mb-2 text-gray-800"><?= $category['name'] ?></h1>
    <p class="text-gray-700 mb-6"><?= $category['description'] ?></p>

    <?php if(mysqli_num_rows($products_q) == 0): ?>
        <div class="bg-white p-6 rounded-xl shadow text-center text-gray-600">
            Belum ada produk di kategori ini.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <?php while($prod = mysqli_fetch_assoc($products_q)): ?>
                <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition flex flex-col">
                    <img src="assets/img/<?= $prod['image'] ?>" class="w-full h-40 object-cover rounded-md mb-3">
                    <h2 class="font-semibold text-lg"><?= $prod['name'] ?></h2>
                    <p class="text-gray-600 text-sm mb-2"><?= $prod['category_desc'] ?></p>
                    <p class="text-ugpurple font-bold mb-2">Rp <?= number_format($prod['price'],0,',','.') ?></p>
                    <a href="product_detail.php?id=<?= $prod['id'] ?>" 
                       class="mt-auto bg-purple-600 text-white py-2 rounded-md text-center hover:bg-purple-700">
                       Lihat Produk
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>

    <!-- Tombol Kembali -->
    <div class="mt-6">
        <a href="index.php" class="inline-block text-purple-600 hover:underline">
            ← Kembali ke Beranda
        </a>
    </div>

</div>

</body>
</html>
