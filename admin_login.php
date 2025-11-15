<?php
session_start();
include 'config.php';

// Jika admin sudah login, langsung ke halaman admin
if (isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_products.php");
  exit;
}

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Username & password bisa kamu ubah sesuka kamu
  $admin_user = "admin";
  $admin_pass = "12345";

  if ($username === $admin_user && $password === $admin_pass) {
    $_SESSION['admin_logged_in'] = true;
    header("Location: admin_products.php");
    exit;
  } else {
    $error = "Username atau password salah!";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin - UGSHOP</title>
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
<body class="bg-purple-50 min-h-screen flex items-center justify-center">

  <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-sm">
    <h2 class="text-2xl font-bold text-ugpurple mb-6 text-center">Login Admin UGSHOP</h2>

    <?php if (isset($error)) echo "<p class='text-red-600 text-center mb-3'>$error</p>"; ?>

    <form action="" method="POST" class="space-y-4">
      <div>
        <label class="block text-gray-700 mb-1 font-medium">Username</label>
        <input type="text" name="username" required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight focus:outline-none">
      </div>

      <div>
        <label class="block text-gray-700 mb-1 font-medium">Password</label>
        <input type="password" name="password" required class="w-full border rounded-md px-3 py-2 focus:ring-2 focus:ring-ugpurplelight focus:outline-none">
      </div>

      <button type="submit" name="login" class="w-full bg-ugpurple text-white py-2 rounded-md hover:bg-ugpurplelight transition">Login</button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
      &copy; 2025 UGSHOP Admin
    </p>
  </div>

</body>
</html>
