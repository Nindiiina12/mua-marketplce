<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $koneksi->query("DELETE FROM pemesanan WHERE id_pemesanan = $id");
    header("Location: data_pemesanan.php");
    exit();
}

$sql = "
    SELECT p.id_pemesanan, p.tanggal_pemesanan, p.waktu_pemesanan, 
           pel.nama AS nama_pelanggan, pel.email AS email_pelanggan,
           m.nama AS nama_mua, m.spesialisasi
    FROM pemesanan p
    LEFT JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
    LEFT JOIN mua m ON p.id_mua = m.id_mua
    ORDER BY p.tanggal_pemesanan DESC
";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pemesanan</title>
    <style>
        body { font-family: sans-serif; padding: 30px; background: #f1f1f1; }
        h2 { color: #db7093; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .btn { padding: 6px 12px; background: #c0392b; color: white; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>

<h2>Daftar Pemesanan</h2>

<table>
    <tr>
        <th>Tanggal</th>
        <th>Waktu</th>
        <th>Pelanggan</th>
        <th>Email</th>
        <th>MUA</th>
        <th>Spesialisasi</th>
        <th>Aksi</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['tanggal_pemesanan'] ?></td>
            <td><?= $row['waktu_pemesanan'] ?></td>
            <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
            <td><?= htmlspecialchars($row['email_pelanggan']) ?></td>
            <td><?= htmlspecialchars($row['nama_mua']) ?></td>
            <td><?= htmlspecialchars($row['spesialisasi']) ?></td>
            <td><a class="btn" href="data_pemesanan.php?hapus=<?= $row['id_pemesanan'] ?>" onclick="return confirm('Hapus pemesanan ini?')">Hapus</a></td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">Belum ada pemesanan.</td></tr>
    <?php endif; ?>
</table>
    </br>
    </br>
<a href="dashboard.php" class="btn">kembali</a>
</body>
</html>
