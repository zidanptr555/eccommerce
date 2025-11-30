<?php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja - UGSHOP</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { ugpurple: '#7b2cbf', ugpurplelight: '#9d4edd' }
        }
      }
    }
  </script>
</head>
<body class="bg-purple-50 min-h-screen flex flex-col">

  <!-- ===== NAVBAR ===== -->
  <nav class="bg-white shadow-md p-4 flex justify-between items-center sticky top-0 z-50">
    <h1 class="text-2xl font-bold text-ugpurple">🛒 Keranjang UGSHOP</h1>
    <div class="flex items-center gap-4">
      <a href="cart.php" class="relative text-ugpurple hover:text-ugpurplelight transition">
        🛒
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-1.5 rounded-full">
          <?= isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'],'qty')) : 0; ?>
        </span>
      </a>
      <a href="index.php" class="bg-ugpurple text-white px-4 py-2 rounded-md hover:bg-ugpurplelight transition">
        ← Kembali ke Beranda
      </a>
    </div>
  </nav>


  <!-- ===== ISI KERANJANG ===== -->
  <main class="max-w-5xl mx-auto mt-8 px-6 flex-grow">
    <h2 class="text-xl font-bold mb-4 text-ugpurple">Produk di Keranjang</h2>

    <?php
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
      echo "<p class='text-gray-600 text-center mt-10'>Keranjang kamu masih kosong 😅</p>";
    } else {
      $total = 0;
      echo "<div class='grid gap-4'>";

      foreach ($_SESSION['cart'] as $product_id => $item) {
          
          if (!is_array($item)) {
          $_SESSION['cart'][$product_id] = ['qty' => 1, 'price' => intval($item)];
          $item = $_SESSION['cart'][$product_id];
      }
        
          $query = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
          if ($product = mysqli_fetch_assoc($query)) {

              // Ambil qty & harga dari session
              $qty = $item['qty'];
              $price = $item['price'];
              $subtotal = $price * $qty;
              $total += $subtotal;

              echo "
              <div class='bg-white rounded-xl shadow p-4 flex items-center justify-between'>
                <div class='flex items-center gap-4'>
                  <img src='assets/img/{$product['image']}' class='w-20 h-20 rounded-md object-cover'>
                  <div>
                    <h3 class='font-semibold text-gray-800'>{$product['name']}</h3>";

              // Tampilkan harga normal & promo
              if($product['is_promo'] == 1){
                  echo "<p class='text-gray-500 line-through text-sm'>Rp " . number_format($product['price'],0,',','.') . "</p>";
                  echo "<p class='text-ugpurple font-semibold'>Rp " . number_format($price,0,',','.') . " (Promo!)</p>";
              } else {
                  echo "<p class='text-ugpurple font-semibold'>Rp " . number_format($price,0,',','.') . "</p>";
              }

              echo "<p class='text-sm text-gray-500'>Jumlah: $qty</p>
                  </div>
                </div>
                <div class='text-right'>
                  <p class='text-gray-700 font-semibold mb-2'>Rp " . number_format($subtotal, 0, ',', '.') . "</p>
                  <form method='POST' action='hapus_cart.php'>
                    <input type='hidden' name='product_id' value='$product_id'>
                    <button type='submit' class='text-red-600 hover:underline text-sm'>Hapus</button>
                  </form>
                </div>
              </div>";
          }
      }

      echo "</div>";

      echo "<div class='text-right mt-6 text-xl font-bold text-ugpurple'>Total: Rp " . number_format($total, 0, ',', '.') . "</div>";

      echo "<div class='text-right mt-4'>";
      if (!isset($_SESSION['user_id'])) {
        echo "<a href='login.php' class='bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition'>
                Login untuk Checkout
              </a>";
      } else {
        echo "<a href='checkout.php' class='bg-green-500 text-white px-5 py-2 rounded-lg hover:bg-green-600 transition'>
                Lanjut ke Checkout
              </a>";
      }
      echo "</div>";
    }
    ?>
  </main>

</body>
</html>
