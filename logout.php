<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Logout - UGSHOP</title>
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

<body class="bg-purple-50 flex items-center justify-center min-h-screen">

  <div class="bg-white w-96 p-8 rounded-2xl shadow-lg text-center">
    <h1 class="text-3xl font-bold text-ugpurple mb-4">UGSHOP</h1>

    <p class="text-gray-600 mb-6">Kamu telah berhasil logout.</p>

    <a href="login.php"
       class="bg-ugpurple text-white px-4 py-2 rounded-md hover:bg-ugpurplelight transition inline-block">
      Login Kembali
    </a>

    <div class="mt-4">
      <a href="index.php" class="text-ugpurplelight hover:text-ugpurple text-sm font-medium">
        ← Kembali ke Beranda
      </a>
    </div>
  </div>

</body>
</html>
