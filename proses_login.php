<?php
session_start();
require_once 'koneksi.php'; // pastikan file koneksi ini ada

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']); // SHA-256 hashing

    $sql = "SELECT * FROM pelanggan WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);
        $_SESSION['id_pelanggan'] = $data['id_pelanggan'];
        $_SESSION['nama'] = $data['nama'];
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Email atau password salah!'); window.location='login.php';</script>";
    }
}
?>
