<?php
include 'db.php';

if (!isset($_GET['id'])) {
    echo "ID pemesanan tidak ditemukan.";
    exit;
}

$id_pemesanan = (int) $_GET['id'];

// Cek dulu apakah data pemesanan ada
$cek = mysqli_query($conn, "SELECT * FROM pemesanan WHERE id_pemesanan = $id_pemesanan");
if (mysqli_num_rows($cek) == 0) {
    echo "Data pemesanan tidak ditemukan.";
    exit;
}

// Hapus pemesanan
mysqli_query($conn, "DELETE FROM pemesanan WHERE id_pemesanan = $id_pemesanan");

echo "<script>alert('Booking berhasil dibatalkan'); window.location='booking_saya.php';</script>";
