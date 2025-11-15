<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Promo Hari Ini - UGSHOP</title>
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

 <!-- ===== NAVBAR ===== -->
<nav class="bg-white/70 backdrop-blur-md border-b border-gray-200 shadow-sm p-4 flex justify-between items-center sticky top-0 z-50">
  <div class="flex items-center gap-3">
    <h1 class="text-2xl font-bold tracking-wide text-gray-800">UGSHOP</h1>
    <button onclick="window.location.href='index.php'" 
            class="ml-2 border border-gray-300 text-gray-700 px-4 py-1.5 rounded-lg hover:bg-gray-100 transition-all">
      Beranda
    </button>
  </div>

  <form method="GET" class="flex items-center gap-2">
    <input type="text" name="search" placeholder="Cari produk..."
           class="border border-gray-300 rounded-lg px-3 py-2 w-64 bg-white focus:ring-2 focus:ring-ugpurplelight focus:outline-none transition-all"
           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit"
            class="bg-ugpurple text-white px-5 py-2 rounded-lg hover:bg-ugpurplelight transition-all">
      Cari
    </button>
  </form>

  <div class="flex items-center gap-4">
    <a href="cart.php" class="relative text-gray-700 hover:text-ugpurple transition">
      🛒
      <span class="absolute -top-2 -right-2 bg-ugpurple text-white text-xs px-1.5 py-0.5 rounded-full">
        <?php
        $cart_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM cart"))['total'];
        echo $cart_count;
        ?>
      </span>
    </a>
    <a href="login.php" class="text-gray-700 font-semibold hover:text-ugpurple transition">Login</a>
    <a href="promo.php" 
       class="bg-gradient-to-r from-ugpurple to-ugpurplelight text-white px-4 py-2 rounded-lg shadow-md hover:opacity-90 transition">
      Promo Hari Ini
    </a>
  </div>
</nav>


  <!-- PROMO HEADER -->
  <header class="text-center mt-8 mb-6">
    <h1 class="text-3xl font-bold text-ugpurple">🔥 Promo Hari Ini 🔥</h1>
    <p class="text-gray-600">Nikmati diskon menarik hanya hari ini!</p>
  </header>

  <!-- PRODUK PROMO -->
  <main class="flex-grow px-6 pb-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      <?php
      $result = mysqli_query($conn, "SELECT * FROM products WHERE is_promo = 1");

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $price_display = isset($row['promo_price']) && $row['promo_price'] > 0
            ? "<p class='text-gray-500 line-through text-sm'>Rp " . number_format($row['price'], 0, ',', '.') . "</p>
               <p class='text-ugpurple font-bold'>Rp " . number_format($row['promo_price'], 0, ',', '.') . "</p>"
            : "<p class='text-ugpurple font-bold'>Rp " . number_format($row['price'], 0, ',', '.') . "</p>";

          echo "
          <div class='bg-white p-4 rounded-xl shadow hover:shadow-lg transition flex flex-col'>
            <img src='assets/img/{$row['image']}' alt='{$row['name']}' class='w-full h-40 object-cover rounded-md mb-3'>
            <h3 class='text-lg font-semibold text-gray-700'>{$row['name']}</h3>
            $price_display
            <a href='product_detail.php?id={$row['id']}' 
              class='bg-ugpurple text-white text-center py-2 rounded-md hover:bg-ugpurplelight transition'>
              Lihat Detail
            </a>

          <form method='POST' action='tambah_keranjang.php' class='mt-2'>
            <input type='hidden' name='product_id' value='{$row['id']}'>
            <button type='submit' 
                    class='bg-green-500 w-full text-white py-2 rounded-md hover:bg-green-600 transition'>
              + Tambah ke Keranjang
            </button>
          </form>

          </div>
          ";
        }
      } else {
        echo "<p class='text-gray-600 text-center col-span-4'>Belum ada produk promo hari ini 😅</p>";
      }
      ?>
    </div>
  </main>

  <footer class="bg-white text-center py-4 shadow-inner text-gray-600">
    © 2025 UGSHOP | Belanja Hemat Setiap Hari 💜
  </footer>

</body>
</html>
