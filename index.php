<?php
include 'config.php';
session_start();

// ======= PROSES TAMBAH KE KERANJANG =======
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

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>UGSHOP - Beranda</title>
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
      <div class="flex items-center gap-3 cursor-pointer" onclick="window.location.href='index.php'">
        <h1 class="text-2xl font-bold tracking-wide text-gray-800 hover:text-ugpurple transition">UGSHOP</h1>
      </div>

      <!-- SEARCH BAR -->
      <form method="GET" class="flex items-center gap-2">
        <input type="text" name="search" placeholder="Cari produk..."
              class="border border-gray-300 rounded-lg px-3 py-2 w-64 bg-white focus:ring-2 focus:ring-ugpurplelight focus:outline-none transition-all"
              value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit"
                class="bg-ugpurple text-white px-5 py-2 rounded-lg hover:bg-ugpurplelight transition-all">
          Cari
        </button>
      </form>

      <!-- LOGIN / LOGOUT -->
      <div class="flex items-center gap-4">

        <?php if (isset($_SESSION['username'])): ?>

          <!-- SUDAH LOGIN -->
          <span class="text-gray-700 font-semibold">
            Halo, <?= htmlspecialchars($_SESSION['username']); ?> 👋
          </span>

          <!-- TOMBOL RIWAYAT PESANAN -->
          <a href="order_history.php" 
            class="text-ugpurple font-semibold hover:text-ugpurplelight transition">
            Riwayat Pesanan
          </a>

          <a href="logout.php" 
            class="text-red-500 font-semibold hover:text-red-700 transition">
            Logout
          </a>

        <?php else: ?>

          <!-- BELUM LOGIN -->
          <a href="login.php" class="text-gray-700 font-semibold hover:text-ugpurple transition">Login</a>
          <a href="register.php" class="text-ugpurple hover:underline">Daftar</a>

        <?php endif; ?>

        <a href="promo.php" 
          class="bg-gradient-to-r from-ugpurple to-ugpurplelight text-white px-4 py-2 rounded-lg shadow-md hover:opacity-90 transition">
          Promo Hari Ini
        </a>

        <!-- CART -->
        <a href="cart.php" class="relative text-ugpurple hover:text-ugpurplelight transition">
          🛒
          <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 rounded-full">
            <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?>
          </span>
        </a>
      </div>
  </nav>


  <!-- ===== SECTION: PROMO HARI INI ===== -->
  <section class="px-6 mt-8">
    <h2 class="text-2xl font-bold text-ugpurple mb-4">Promo Hari Ini 🎉</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <?php
      $promoQuery = mysqli_query($conn, "SELECT * FROM products WHERE is_promo = 1 LIMIT 8");
      if (mysqli_num_rows($promoQuery) > 0) {
        while ($promo = mysqli_fetch_assoc($promoQuery)) {
          echo "
          <div class='bg-white p-3 rounded-xl shadow hover:shadow-lg transition flex flex-col text-center'>
            <img src='assets/img/{$promo['image']}' alt='{$promo['name']}' class='w-full h-32 object-cover rounded-md mb-3'>
            <h3 class='text-base font-semibold text-gray-700 mb-1'>{$promo['name']}</h3>
            <p class='text-gray-500 line-through text-xs'>Rp " . number_format($promo['price'], 0, ',', '.') . "</p>
            <p class='text-ugpurple font-bold text-sm mb-2'>Rp " . number_format($promo['promo_price'], 0, ',', '.') . "</p>

            <div class='mt-auto flex justify-center gap-2'>
              <a href='detailproduk.php?id={$promo['id']}' class='bg-ugpurple text-white px-3 py-1 rounded-md hover:bg-ugpurplelight transition text-sm'>Lihat</a>
              <form method='POST' class='inline'>
                <input type='hidden' name='product_id' value='{$promo['id']}'>
                <button type='submit' class='bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 transition text-sm'>+ Keranjang</button>
              </form>
            </div>
          </div>
          ";
        }
      } else {
        echo "<p class='text-gray-600 col-span-4 text-center'>Belum ada produk promo hari ini.</p>";
      }
      ?>
    </div>
  </section>

  <!-- ===== SECTION: PRODUK PER KATEGORI ===== -->
  <main class="flex-grow px-6 pb-10 mt-6">
    <?php
    $kategori = ['Elektronik', 'Kosmetik', 'Pakaian'];

    foreach ($kategori as $kat) {
      echo "<h2 class='text-2xl font-bold text-ugpurple mt-8 mb-4'>$kat</h2>";
      echo "<div class='grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4'>";
      
      $query = "SELECT * FROM products WHERE category='$kat' LIMIT 10";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          echo "
          <div class='bg-white p-3 rounded-xl shadow hover:shadow-lg transition flex flex-col text-center'>
            <img src='assets/img/{$row['image']}' alt='{$row['name']}' class='w-full h-32 object-cover rounded-md mb-3'>
            <h3 class='text-base font-semibold text-gray-700 mb-1'>{$row['name']}</h3>
            <p class='text-ugpurple font-bold text-sm mb-2'>Rp " . number_format($row['price'], 0, ',', '.') . "</p>

            <div class='mt-auto flex justify-center gap-2'>
              <a href='product_detail.php?id={$row['id']}' class='bg-ugpurple text-white px-3 py-1 rounded-md hover:bg-ugpurplelight transition text-sm'>Lihat</a>

              <form method='POST' class='inline'>
                <input type='hidden' name='product_id' value='{$row['id']}'>
                <button type='submit' class='bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 transition text-sm'>+ Keranjang</button>
              </form>
            </div>
          </div>
          ";
        }
      } else {
        echo "<p class='text-gray-600 col-span-4'>Belum ada produk di kategori ini</p>";
      }

      echo "</div>";
    }
    ?>
  </main>

  <!-- ===== FOOTER ===== -->
  <footer class="bg-white text-center py-4 shadow-inner text-gray-600">
    © 2025 UGSHOP | Belanja Hemat Setiap Hari 💜
  </footer>

</body>
</html>
