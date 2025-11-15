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
  <title>Tambah Produk - UGSHOP</title>
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

  <nav class="bg-white shadow-md p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-ugpurple">Tambah Produk</h1>
    <a href="admin_products.php" class="text-ugpurple font-semibold hover:underline">Kembali</a>
  </nav>

  <main class="flex-grow flex items-center justify-center px-6 py-10">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-lg">
      <h2 class="text-xl font-bold text-ugpurple mb-6 text-center">Tambah Produk Baru</h2>

      <?php
      if (isset($_POST['submit'])) {

        $name = $_POST['name'];
        $price = $_POST['price'];
        $deskripsi = $_POST['deskripsi'];
        $store_area = $_POST['store_area']; // ⭐ ambil asal daerah toko

        $image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        $target_dir = "assets/img/";
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($tmp_name, $target_file)) {

            // INSERT + store_area
            $query = "INSERT INTO products (name, price, deskripsi, image, store_area)
                      VALUES ('$name', '$price', '$deskripsi', '$image', '$store_area')";
            
            if (mysqli_query($conn, $query)) {
                echo "<p class='text-green-600 text-center mb-4'>Produk berhasil ditambahkan!</p>";
            } else {
                echo "<p class='text-red-600 text-center mb-4'>Gagal menambahkan produk.</p>";
            }

        } else {
            echo "<p class='text-red-600 text-center mb-4'>Gagal mengunggah gambar.</p>";
        }
      }
      ?>

      <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">

        <input type="text" name="name" placeholder="Nama Produk" required
        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight">

        <input type="number" name="price" placeholder="Harga Produk" required
        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight">

        <input type="file" name="image" accept="image/*" required
        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight">

        <label class="block font-semibold mt-4">Deskripsi Produk</label>
        <textarea name="deskripsi" rows="4"
        class="border rounded-md w-full p-2 focus:ring-2 focus:ring-ugpurplelight"></textarea>

        <!-- ⭐ store_area -->
        <label class="block font-semibold mt-4">Asal Daerah Toko</label>
        <select name="store_area" required
        class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight">

          <option value="">-- Pilih Asal Daerah --</option>
          <option value="jakarta">Jakarta</option>
          <option value="bogor">Bogor</option>
          <option value="depok">Depok</option>
          <option value="tangerang">Tangerang</option>
          <option value="bekasi">Bekasi</option>

        </select>

        <button name="submit"
        class="w-full bg-ugpurple text-white py-2 rounded-md hover:bg-ugpurplelight transition">
          Tambah
        </button>

      </form>
    </div>
  </main>

  <footer class="bg-white text-center py-4 shadow-inner text-gray-600">
    © 2025 UGSHOP Admin Panel
  </footer>

</body>
</html>
