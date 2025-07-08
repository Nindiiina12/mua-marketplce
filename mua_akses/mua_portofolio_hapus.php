<?php
session_start();
if (!isset($_SESSION['id_mua'])) {
    header("Location: login_mua.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");
$id_mua = $_SESSION['id_mua'];

// Pastikan metode POST dan id_portofolio dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_portofolio'])) {
    $id_portofolio = (int)$_POST['id_portofolio'];

    // Ambil nama file gambar
    $stmt = $koneksi->prepare("SELECT gambar FROM portofolio WHERE id_portofolio = ? AND id_mua = ?");
    $stmt->bind_param("ii", $id_portofolio, $id_mua);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_path = "../uploads/" . $row['gambar'];

        // Hapus file jika ada
        if (!empty($row['gambar']) && file_exists($file_path)) {
            unlink($file_path);
        }

        // Hapus data dari database
        $hapus = $koneksi->prepare("DELETE FROM portofolio WHERE id_portofolio = ? AND id_mua = ?");
        $hapus->bind_param("ii", $id_portofolio, $id_mua);
        $hapus->execute();

        // Beri notifikasi via query string (opsional, bisa diambil pakai $_GET['hapus'])
        header("Location: mua_portofolio.php?hapus=berhasil");
        exit();
    }
}

// Jika gagal atau tidak valid, tetap redirect
header("Location: mua_portofolio.php?hapus=gagal");
exit();
?>
