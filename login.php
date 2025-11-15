<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - UGSHOP</title>
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

  <!-- ===== LOGIN SECTION ===== -->
  <div class="bg-white w-96 p-8 rounded-2xl shadow-lg">
    <h1 class="text-3xl font-bold text-center text-ugpurple mb-6">UGSHOP</h1>
    <h2 class="text-center text-gray-600 mb-6">Masuk ke akunmu</h2>

    <form method="POST" action="login_process.php" class="flex flex-col gap-4">
      <div>
        <label for="username" class="block text-gray-700 font-medium mb-1">Username</label>
        <input type="text" id="username" name="username" required
               class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-ugpurplelight">
      </div>

      <div>
        <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
        <input type="password" id="password" name="password" required
               class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-ugpurplelight">
      </div>

      <button type="submit"
              class="bg-ugpurple text-white font-semibold py-2 rounded-md hover:bg-ugpurplelight transition">
        Login
      </button>
    </form>

    <!-- ===== TAMBAHAN LINK ===== -->
    <div class="mt-6 text-center text-sm text-gray-600">
      Belum punya akun?
      <a href="register.php" class="text-ugpurple hover:underline">Daftar Sekarang</a>
    </div>

    <!-- ===== KEMBALI KE BERANDA ===== -->
    <div class="mt-4 text-center">
      <a href="index.php" class="inline-block text-ugpurplelight hover:text-ugpurple text-sm font-medium">
        ← Kembali ke Beranda
      </a>
    </div>
  </div>

</body>
</html>
