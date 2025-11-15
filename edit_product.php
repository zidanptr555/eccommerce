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
  <title>Edit Produk - UGSHOP</title>
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
    <h1 class="text-2xl font-bold text-ugpurple">Edit Produk</h1>
    <a href="admin_products.php" class="text-ugpurple font-semibold hover:underline">Kembali</a>
  </nav>

  <main class="flex-grow flex items-center justify-center px-6 py-10">
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-lg">
      <?php
      $id = $_GET['id'];
      $result = mysqli_query($conn, "SELECT * FROM products WHERE id=$id");
      $row = mysqli_fetch_assoc($result);

      if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $store_area = $_POST['store_area'];

        // Jika ganti gambar
        if (!empty($_FILES['image']['name'])) {
          $image = $_FILES['image']['name'];
          $tmp_name = $_FILES['image']['tmp_name'];
          move_uploaded_file($tmp_name, "assets/img/" . $image);

          $query = "UPDATE products 
                    SET name='$name', price='$price', store_area='$store_area', image='$image' 
                    WHERE id=$id";
        } else {
          $query = "UPDATE products 
                    SET name='$name', price='$price', store_area='$store_area' 
                    WHERE id=$id";
        }

        if (mysqli_query($conn, $query)) {
          echo "<p class='text-green-600 text-center mb-4'>Produk berhasil diperbarui!</p>";
        } else {
          echo "<p class='text-red-600 text-center mb-4'>Gagal memperbarui produk.</p>";
        }
      }
      ?>

      <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">

        <input type="text" name="name" value="<?php echo $row['name']; ?>" 
               required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight">

        <input type="number" name="price" value="<?php echo $row['price']; ?>" 
               required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight">

        <label class="font-semibold block mt-2">Asal Daerah Toko</label>
        <select name="store_area" class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight" required>
          <?php
          $areas = ["Jakarta", "Bogor", "Depok", "Tangerang", "Bekasi"];
          foreach ($areas as $area) {
            $selected = ($row['store_area'] == $area) ? "selected" : "";
            echo "<option value='$area' $selected>$area</option>";
          }
          ?>
        </select>

        <img src="assets/img/<?php echo $row['image']; ?>" class="h-32 rounded-md mb-2">

        <input type="file" name="image" accept="image/*" 
               class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight">

        <button name="update" 
                class="w-full bg-ugpurple text-white py-2 rounded-md hover:bg-ugpurplelight transition">
          Perbarui Produk
        </button>

      </form>
    </div>
  </main>

  <footer class="bg-white text-center py-4 shadow-inner text-gray-600">
    © 2025 UGSHOP Admin Panel
  </footer>

</body>
</html>
