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
  <title>Admin Pesanan - UGSHOP</title>
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
      <a href="admin_products.php" class="bg-ugpurple text-white px-4 py-2 rounded-md hover:bg-ugpurplelight transition">Produk</a>
      <a href="admin_orders.php" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">Pesanan</a>
      <a href="admin_logout.php" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Logout</a>
    </div>
  </nav>

  <!-- DAFTAR PESANAN -->
  <main class="flex-grow p-6">
    <h2 class="text-2xl font-bold text-ugpurple mb-6">Daftar Pesanan</h2>

    <?php
    $search = $_GET['search'] ?? '';
    $search_sql = '';
    if(!empty($search)){
        $search_safe = mysqli_real_escape_string($conn, $search);
        $search_sql = " AND (u.username LIKE '%$search_safe%' OR p.name LIKE '%$search_safe%')";
    }

    $query = "
        SELECT o.id AS order_id, o.user_id, o.alamat_penerima, o.metode, o.ongkir, o.total_akhir, o.status,
               u.username,
               p.name AS product_name, oi.qty AS product_qty
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE 1=1 $search_sql
        ORDER BY o.id DESC
    ";

    $result = mysqli_query($conn, $query);
    $orders = [];
    while($row = mysqli_fetch_assoc($result)){
        $orders[$row['order_id']]['info'] = [
            'user' => $row['username'],
            'alamat_penerima' => $row['alamat_penerima'],
            'metode' => $row['metode'],
            'ongkir' => $row['ongkir'],
            'total_akhir' => $row['total_akhir'],
            'status' => $row['status']
        ];
        $orders[$row['order_id']]['products'][] = [
            'name' => $row['product_name'],
            'qty' => $row['product_qty']
        ];
    }

    function statusColor($status){
        return match($status){
            'Menunggu Pembayaran' => 'bg-yellow-400 text-black',
            'Paket Diproses' => 'bg-blue-500 text-white',
            'Paket Diantarkan' => 'bg-orange-500 text-white',
            'Paket Telah Tiba' => 'bg-green-600 text-white',
            default => 'bg-gray-400 text-white',
        };
    }

    // Proses update status
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])){
        $order_id = intval($_POST['order_id']);
        $status   = mysqli_real_escape_string($conn, $_POST['status']);
        mysqli_query($conn, "UPDATE orders SET status='$status' WHERE id=$order_id");
        echo "<script>window.location='admin_orders.php';</script>";
    }
    ?>

    <!-- Form Pencarian -->
    <form method="GET" class="mb-6 flex gap-2">
        <input type="text" name="search" placeholder="Cari user atau produk..." 
               value="<?= htmlspecialchars($search); ?>" 
               class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
            Cari
        </button>
    </form>

    <?php if(empty($orders)): ?>
        <div class="bg-white p-6 rounded-xl shadow text-center text-gray-600">
            Tidak ada pesanan<?= $search ? " untuk '$search'" : "" ?>.
        </div>
    <?php else: ?>
        <div class="space-y-6">
            <?php foreach($orders as $order_id => $data): ?>
                <div class="bg-white shadow rounded-xl p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h2 class="font-semibold text-lg">
                            Order ID: <?= $order_id; ?> - User: <?= $data['info']['user']; ?>
                        </h2>
                    </div>

                    <p><strong>Alamat:</strong> <?= $data['info']['alamat_penerima']; ?></p>
                    <p><strong>Metode:</strong> <?= $data['info']['metode']; ?></p>
                    <p><strong>Ongkir:</strong> Rp<?= number_format($data['info']['ongkir'],0,',','.'); ?></p>
                    <p><strong>Total:</strong> Rp<?= number_format($data['info']['total_akhir'],0,',','.'); ?></p>

                    <p class="mt-2"><strong>Produk:</strong></p>
                    <ul class="ml-4 list-disc">
                        <?php foreach($data['products'] as $prod): ?>
                            <li><?= $prod['name']; ?> x <?= $prod['qty']; ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Form update status -->
                    <form method="POST" class="mt-3 flex gap-2 items-center">
                        <input type="hidden" name="order_id" value="<?= $order_id; ?>">
                        <select name="status" class="border px-3 py-2 rounded-lg focus:outline-none">
                            <?php 
                            $statuses = ['Menunggu Pembayaran', 'Paket Diproses', 'Paket Diantarkan', 'Paket Telah Tiba'];
                            foreach($statuses as $s): ?>
                                <option value="<?= $s; ?>" <?= $data['info']['status']==$s ? 'selected' : ''; ?>>
                                    <?= $s; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                            Update Status
                        </button>
                    </form>

                    <!-- Status badge -->
                    <p class="mt-2">
                        <span class="px-3 py-1 rounded-full <?= statusColor($data['info']['status']); ?>">
                            <?= $data['info']['status']; ?>
                        </span>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

  </main>

  <footer class="bg-white text-center py-4 shadow-inner text-gray-600">
    © 2025 UGSHOP Admin Panel
  </footer>

</body>
</html>
