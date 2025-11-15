<?php
include 'config.php';

// Ambil data dari form
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

// Cek password sama atau tidak
if ($password !== $confirm) {
    echo "<script>alert('Password tidak sama!'); window.location='register.php';</script>";
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users (username, email, password) 
          VALUES ('$username', '$email', '$hash')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
} else {
    echo "<script>alert('Gagal daftar! Username mungkin sudah digunakan.'); window.location='register.php';</script>";
}
