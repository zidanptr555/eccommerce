<?php
session_start();
include 'config.php';

// Ambil data dari form login
$username = $_POST['username'];
$password = $_POST['password'];

// Cek username di database
$query = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    // Verifikasi password
    if (password_verify($password, $row['password'])) {

        // Simpan session
        $_SESSION['username'] = $row['username'];
        $_SESSION['user_id'] = $row['id'];

        // Arahkan ke beranda (ubah jika kamu punya halaman lain)
        header("Location: index.php");
        exit();
    } else {
        // Password salah
        echo "<script>alert('Password salah!'); window.location='login.php';</script>";
        exit();
    }
} else {
    // Username tidak ditemukan
    echo "<script>alert('Username tidak terdaftar!'); window.location='login.php';</script>";
    exit();
}
?>
