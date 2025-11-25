-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Nov 2025 pada 04.17
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ecommerce`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Elektronik', NULL),
(2, 'Fashion', NULL),
(3, 'Makanan', NULL),
(4, 'Gaming', NULL),
(5, 'Aksesoris', 'Casing lentur anti crack\n\nAman dan cepat\n\nUntuk konten creator');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `hp_penerima` varchar(20) DEFAULT NULL,
  `alamat_penerima` text DEFAULT NULL,
  `kota_toko` varchar(100) DEFAULT NULL,
  `kota_tujuan` varchar(100) DEFAULT NULL,
  `ongkir` int(11) DEFAULT 0,
  `total_akhir` int(11) DEFAULT 0,
  `metode` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_belanja` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `username`, `nama_penerima`, `hp_penerima`, `alamat_penerima`, `kota_toko`, `kota_tujuan`, `ongkir`, `total_akhir`, `metode`, `status`, `created_at`, `total_belanja`) VALUES
(1, 1, NULL, 'Zidan Putra', '08123456789', 'Ciledug', 'Jakarta', 'Tangerang', 15000, 200000, 'COD', 'pending', '2025-11-23 13:22:52', 0),
(2, 3, NULL, 'ndjandjan', '090800', 'bdfhsbdfs', 'Depok', 'Jakarta', 7000, 357000, NULL, 'Menunggu Pembayaran', '2025-11-23 13:47:44', 350000),
(3, 3, NULL, 'ndjandjan', '090800', 'bdfhsbdfs', 'Depok', 'Jakarta', 7000, 357000, NULL, 'Menunggu Pembayaran', '2025-11-23 13:47:49', 350000),
(4, 3, NULL, 'ndjandjan', '090800', 'bdfhsbdfs', 'Depok', 'Jakarta', 7000, 357000, NULL, 'Menunggu Pembayaran', '2025-11-23 13:47:55', 350000),
(5, 3, NULL, 'ndjandjan', '090800', 'bdfhsbdfs', 'Depok', 'Jakarta', 7000, 357000, NULL, 'Menunggu Pembayaran', '2025-11-23 13:50:33', 350000),
(6, 3, NULL, 'ndjandjan', '090800', 'bdfhsbdfs', 'Depok', 'Jakarta', 7000, 357000, NULL, 'Menunggu Pembayaran', '2025-11-23 13:55:52', 350000),
(7, 3, NULL, 'ndjandjan', '090800', 'bdfhsbdfs', '', '', 0, 357000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 13:57:42', 357000),
(8, 3, NULL, 'ndjandjan', '090800', 'fxgffdxfd', 'Depok', 'Jakarta', 7000, 357000, NULL, 'Menunggu Pembayaran', '2025-11-23 13:58:50', 350000),
(9, 3, 'zidan000', 'ndjandjan', '090800', 'fxgffdxfd', '', '', 0, 357000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 13:58:54', 357000),
(10, 3, 'zidan000', 'ndjandjan', '090800', 'fxgffdxfd', '', '', 0, 357000, 'E-Wallet', 'Menunggu Pembayaran', '2025-11-23 14:00:02', 357000),
(11, 3, NULL, 'ndjandjan', '090800', 'dxxd', 'Depok', 'Jakarta', 7000, 62000, NULL, 'Menunggu Pembayaran', '2025-11-23 14:11:19', 55000),
(12, 3, 'zidan000', 'ndjandjan', '090800', 'dxxd', '', '', 0, 62000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 14:11:25', 62000),
(13, 3, NULL, 'ndjandjan', '090800', 'nnn', 'Depok', 'Bogor', 8000, 128000, NULL, 'Menunggu Pembayaran', '2025-11-23 14:33:33', 120000),
(14, 3, 'zidan000', 'ndjandjan', '090800', 'nnn', '', '', 0, 128000, 'E-Wallet', 'Paket Diproses', '2025-11-23 14:33:36', 128000),
(15, 3, NULL, 'ndjandjan', '090800', 'xsssssss', 'Bogor', 'Depok', 8000, 98000, NULL, 'Menunggu Pembayaran', '2025-11-23 15:47:16', 90000),
(16, 3, 'zidan000', 'ndjandjan', '090800', 'xsssssss', '', '', 0, 98000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 15:47:20', 98000),
(17, 3, 'zidan000', 'ndjandjan', '090800', 'xsssssss', '', '', 0, 98000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 15:48:59', 98000),
(18, 3, 'zidan000', 'ndjandjan', '090800', 'xsssssss', '', '', 0, 98000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 15:49:03', 98000),
(19, 3, 'zidan000', 'ndjandjan', '090800', 'xsssssss', '', '', 0, 98000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 15:49:07', 98000),
(20, 3, 'zidan000', 'ndjandjan', '090800', 'xsssssss', '', '', 0, 98000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 15:50:48', 98000),
(21, 3, 'zidan000', 'ndjandjan', '090800', 'xsssssss', '', '', 0, 98000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 15:50:53', 98000),
(22, 3, 'zidan000', 'ndjandjan', '090800', 'xsssssss', '', '', 0, 98000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 15:51:00', 98000),
(23, 3, 'zidan000', 'ndjandjan', '090800', 'xsssssss', '', '', 0, 98000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 15:52:20', 98000),
(24, 3, 'zidan000', 'ndjandjan', '090800', 'xsssssss', '', '', 0, 98000, 'Transfer Bank', 'Menunggu Pembayaran', '2025-11-23 15:53:43', 98000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `price`) VALUES
(1, 1, 1, 1, 150000),
(2, 1, 3, 1, 125000),
(3, 6, 2, 1, 350000),
(4, 7, 2, 1, 350000),
(5, 8, 2, 1, 350000),
(6, 9, 2, 1, 350000),
(7, 11, 4, 1, 55000),
(8, 12, 4, 1, 55000),
(9, 13, 5, 1, 120000),
(10, 14, 5, 1, 120000),
(11, 15, 1, 1, 90000),
(12, 24, 1, 1, 90000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `metode` varchar(50) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `metode`, `total`, `created_at`) VALUES
(1, 1, 'COD', 290000, '2025-11-23 13:22:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `promo_price` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_promo` tinyint(1) NOT NULL DEFAULT 0,
  `category` varchar(100) NOT NULL,
  `store_area` varchar(100) DEFAULT 'Depok'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `promo_price`, `description`, `image`, `is_promo`, `category`, `store_area`) VALUES
(1, 1, 'Headphone Bluetooth', 150000, 90000, 'Headphone wireless kualitas tinggi', 'hp.jpg', 1, 'Elektronik', 'Bogor'),
(2, 1, 'Keyboard Mechanical', 350000, 0, 'Keyboard gaming RGB', 'keyboard.jpeg', 1, 'Elektronik', 'Depok'),
(3, 1, 'Mouse Gaming X7', 125000, 0, 'Mouse gaming responsif', 'mouse.png', 0, 'Elektronik', 'Jakarta'),
(4, 2, 'Kaos Polos Premium', 55000, 0, 'Bahan cotton combed 30s', 'kaos.webp', 1, 'Pakaian', 'Depok'),
(5, 2, 'Jaket Hoodie Hitam', 120000, 0, 'Nyaman dan hangat', 'hoodie.webp', 0, 'Pakaian', 'Jakarta'),
(6, 3, 'Keripik Pedas Level 10', 20000, 0, 'Cemilan super pedas', 'keripik.jpeg', 0, 'Kosmetik', 'Bekasi'),
(7, 3, 'Cookies hytam khas bogor', 30000, 0, 'Cookies hytam khas bogor loh ya', 'cookies.jpeg', 1, 'Kosmetik', 'Bogor'),
(8, 4, 'Stik PS5 Original', 850000, 0, 'DualSense Controller', 'ps5stick.jpeg', 0, 'Elektronik', 'Jakarta'),
(9, 4, 'Mousepad Gaming XL', 45000, 0, 'Ukuran besar anti slip', 'mousepad.png', 1, 'Elektronik', 'Tangerang'),
(10, 5, 'Casing HP Transparan', 15000, 0, 'Casing lentur anti crack', 'casing.jpeg', 0, 'Elektronik', 'Bekasi'),
(11, 5, 'Charger Fast Charging 20W', 35000, 0, 'Aman dan cepat', 'charger.webp', 1, 'Elektronik', 'Bogor'),
(12, 5, 'Tripod Mini Meja', 25000, 20000, 'Untuk konten creator', 'tripod.jpeg', 1, 'Elektronik', 'Depok');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Zidan123', NULL, 'zidan123', '2025-11-23 13:22:52'),
(2, 'Budi', NULL, 'budi123', '2025-11-23 13:22:52'),
(3, 'zidan000', 'zidan@example.com', '$2y$10$GStKfCTEv5Mz9FXXndZKp.Gsb/OofLDO6dnDFTYDykAHo.UUe6Fmm', '2025-11-23 13:45:19');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
