<?php
session_start();
include 'db.php';

// Cek apakah pelanggan sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location='login.php';</script>";
    exit;
}

// Validasi ID MUA
if (!isset($_GET['id'])) {
    echo "ID MUA tidak ditemukan.";
    exit;
}

$id_mua = (int) $_GET['id'];
$id_pelanggan = $_SESSION['id_pelanggan'];

$tanggal = date('Y-m-d');
$waktu = date('H:i:s');

// Cek apakah pelanggan sudah booking MUA ini
$cek = mysqli_query($conn, "SELECT * FROM pemesanan WHERE id_pelanggan = $id_pelanggan AND id_mua = $id_mua");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Anda sudah memesan MUA ini.'); window.location='detail_jasa.php?id=$id_mua';</script>";
    exit;
}

// Lakukan booking
mysqli_query($conn, "INSERT INTO pemesanan (tanggal_pemesanan, waktu_pemesanan, id_pelanggan, id_mua) VALUES ('$tanggal', '$waktu', $id_pelanggan, $id_mua)");

echo "<script>alert('Booking berhasil!'); window.location='detail_jasa.php?id=$id_mua';</script>";
