<?php
session_start();
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggan = $_SESSION['id_pelanggan'];
    $id_pemesanan = intval($_POST['id_pemesanan']);
    $metode = $_POST['metode'];

    $koneksi = new mysqli("localhost", "root", "", "mua");
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Validasi booking milik pelanggan
    $cek = $koneksi->prepare("SELECT * FROM pemesanan WHERE id_pemesanan = ? AND id_pelanggan = ?");
    $cek->bind_param("ii", $id_pemesanan, $id_pelanggan);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows == 0) {
        $cek->close();
        $koneksi->close();
        header("Location: booking_saya.php?error=notfound");
        exit();
    }

    // Cek apakah sudah pernah bayar
    $cekBayar = $koneksi->prepare("SELECT * FROM pembayaran WHERE id_pemesanan = ?");
    $cekBayar->bind_param("i", $id_pemesanan);
    $cekBayar->execute();
    $resBayar = $cekBayar->get_result();
    if ($resBayar->num_rows > 0) {
        $cekBayar->close();
        $koneksi->close();
        header("Location: booking_saya.php?error=already_paid");
        exit();
    }

    // Simpan pembayaran
    $stmt = $koneksi->prepare("INSERT INTO pembayaran (id_pemesanan, metode, tanggal_bayar) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $id_pemesanan, $metode);
    $stmt->execute();

    $stmt->close();
    $cek->close();
    $cekBayar->close();
    $koneksi->close();

    header("Location: booking_saya.php?success=paid");
    exit();
}
?>
