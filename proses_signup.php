<?php
require_once 'koneksi.php'; // file ini berisi koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['username'];
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);

    $cek = mysqli_query($conn, "SELECT * FROM pelanggan WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah terdaftar'); window.location='login.php';</script>";
        exit;
    }

    $query = "INSERT INTO pelanggan (nama, email, password) VALUES ('$nama', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Gagal daftar.'); window.location='login.php';</script>";
    }
}
