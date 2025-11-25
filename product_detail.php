<?php
include 'config.php';
session_start();

// ======= TAMBAH KE KERANJANG DARI HALAMAN DETAIL =======
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
  $product_id = intval($_POST['product_id']);

  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
  }

  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]++;
  } else {
    $_SESSION['cart'][$product_id] = 1;
  }

  header("Location: cart.php");
  exit;
}

// ======= AMBIL DATA PRODUK =======
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $query = "SELECT * FROM products WHERE id = $id";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
  } else {
    echo "<p class='text-center text-red-600 mt-10'>Produk tidak ditemukan.</p>";
    exit;
  }
} else {
  echo "<p class='text-center text-red-600 mt-10'>ID produk tidak diberikan.</p>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Produk - UGSHOP</title>
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
    <h1 class="text-2xl font-bold text-ugpurple">UGSHOP</h1>
    <div class="flex items-center gap-4">
      <a href="cart.php" class="relative text-ugpurple hover:text-ugpurplelight transition">
        🛒
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 rounded-full">
          <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
        </span>
      </a>
      <a href="index.php" class="bg-ugpurple text-white px-4 py-2 rounded-md hover:bg-ugpurplelight transition">Kembali</a>
    </div>
  </nav>

  <!-- DETAIL PRODUK -->
  <main class="flex-grow container mx-auto px-6 py-10">
    <div class="bg-white p-6 rounded-2xl shadow-md flex flex-col md:flex-row gap-6 items-start">
      <img src="assets/img/<?= $row['image']; ?>" alt="<?= $row['name']; ?>"
           class="w-full md:w-1/3 rounded-xl object-cover shadow">

      <div class="flex-1">
        <h2 class="text-2xl font-bold text-ugpurple mb-3"><?= $row['name']; ?></h2>
        <p class="text-xl text-ugpurplelight font-semibold mb-4">Rp <?= number_format($row['price'], 0, ',', '.'); ?></p>

        <div class="text-gray-700 leading-relaxed mb-6">
          <h3 class="font-semibold text-lg mb-2">Deskripsi Produk:</h3>
          <p class="bg-purple-50 p-4 rounded-md border border-purple-100"><?= nl2br($row['description']); ?></p>
        </div>

        <!-- FORM TAMBAH KE KERANJANG -->
        <form method="POST">
          <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
          <button type="submit"
                  class="bg-ugpurple text-white px-6 py-2 rounded-md hover:bg-ugpurplelight transition">
            + Tambahkan ke Keranjang
          </button>
        </form>
      </div>
    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-white text-center py-4 shadow-inner text-gray-600">
    © 2025 UGSHOP | Belanja Nyaman & Aman
  </footer>

</body>
</html>
