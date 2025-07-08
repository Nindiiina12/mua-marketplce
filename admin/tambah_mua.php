<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $spesialisasi = $_POST['spesialisasi'];
    $harga = $_POST['harga'];
    $jadwal = $_POST['jadwal'];
    $email = $_POST['email'];
    $telp = $_POST['telp'];

    $stmt = $koneksi->prepare("INSERT INTO mua (nama, spesialisasi, harga, jadwal_tersedia, email, no_telp) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsss", $nama, $spesialisasi, $harga, $jadwal, $email, $telp);
    $stmt->execute();

    header("Location: data_mua.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah MUA</title>
    <style>
        body { font-family: sans-serif; background: #fff8dc; padding: 30px; }
        form { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, textarea { width: 100%; padding: 8px; margin-bottom: 15px; }
        button { background: #db7093; color: white; padding: 10px 15px; border: none; cursor: pointer; }
    </style>
</head>
<body>

<h2>Tambah MUA Baru</h2>
<form method="POST">
    <input type="text" name="nama" placeholder="Nama MUA" required>
    <input type="text" name="spesialisasi" placeholder="Spesialisasi" required>
    <input type="number" name="harga" placeholder="Harga" required>
    <textarea name="jadwal" placeholder="Jadwal Tersedia" required></textarea>
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="telp" placeholder="No. Telp">
    <button type="submit">Simpan</button>
</form>

</body>
</html>
