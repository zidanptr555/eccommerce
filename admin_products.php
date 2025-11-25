<?php 
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}
include 'config.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Produk - UGSHOP</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            ugpurple: '#7b2cbf',
            ugpurplelight: '#9d4edd',
          }
        }
      }
    }
  </script>
</head>
<body class="bg-purple-50 min-h-screen flex flex-col">

  <!-- NAVBAR -->
  <nav class="bg-white shadow-md p-4 flex justify-between items-center sticky top-0 z-50">
    <h1 class="text-2xl font-bold text-ugpurple">UGSHOP Admin</h1>
    <div class="flex items-center gap-4">
       <a href="add_product.php" class="bg-ugpurple text-white px-4 py-2 rounded-md hover:bg-ugpurplelight transition">+ Tambah Produk</a>

        <!-- Shortcut ke halaman pesanan -->
        <a href="admin_orders.php" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">Lihat Pesanan</a>

        <a href="index.php" class="text-ugpurple hover:underline">Lihat Toko</a>
        <a href="admin_logout.php" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Logout</a>
    </div>
  </nav>

  <!-- DAFTAR PRODUK -->
  <main class="flex-grow p-6">
    <h2 class="text-2xl font-bold text-ugpurple mb-6">Daftar Produk</h2>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");

    if (mysqli_num_rows($result) > 0) {
      echo "<div class='grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6'>";

      while ($row = mysqli_fetch_assoc($result)) {

        echo "
        <div class='bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition flex flex-col'>
          
          <img src='assets/img/{$row['image']}' class='w-full h-40 object-cover rounded-md mb-3'>

          <h3 class='text-lg font-semibold text-gray-700'>{$row['name']}</h3>

          <p class='text-ugpurple font-bold mt-1'>
            Rp " . number_format($row['price'], 0, ',', '.') . "
          </p>

          <p class='text-sm text-gray-600 mt-1'>
            Asal Toko: <span class='font-semibold text-ugpurple'>" . ucfirst($row['store_area']) . "</span>
          </p>

          <div class='flex gap-2 mt-auto'>
            <a href='edit_product.php?id={$row['id']}' 
               class='flex-1 bg-yellow-400 text-white py-2 rounded-md hover:bg-yellow-500 text-center transition'>
               Edit
            </a>

            <a href='?delete={$row['id']}' 
               onclick='return confirm(\"Yakin ingin hapus produk ini?\")' 
               class='flex-1 bg-red-500 text-white py-2 rounded-md hover:bg-red-600 text-center transition'>
               Hapus
            </a>
          </div>
        </div>";
      }

      echo "</div>";
    } else {
      echo "<p class='text-gray-600'>Belum ada produk.</p>";
    }

    // Hapus produk
    if (isset($_GET['delete'])) {
      $id = $_GET['delete'];
      mysqli_query($conn, "DELETE FROM products WHERE id=$id");
      echo "<script>alert('Produk berhasil dihapus!');window.location='admin_products.php';</script>";
    }
    ?>
  </main>

  <footer class="bg-white text-center py-4 shadow-inner text-gray-600">
    © 2025 UGSHOP Admin Panel
  </footer>

</body>
</html>
