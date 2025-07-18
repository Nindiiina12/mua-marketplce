<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $koneksi->query("DELETE FROM mua WHERE id_mua = $id");
    header("Location: data_mua.php");
    exit();
}

$mua = $koneksi->query("SELECT * FROM mua");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola MUA</title>
    <style>
        body { font-family: sans-serif; padding: 30px; background: #fff8dc; }
        h2 { color: #db7093; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        a.btn { padding: 6px 12px; background: #db7093; color: white; text-decoration: none; border-radius: 4px; }
        .btn-danger { background: #c0392b; }
        @media (max-width: 768px) {
        table {display: block;overflow-x: auto;white-space: nowrap;}
        table th, table td {min-width: 120px;}
        body {padding: 15px;}
        h2 {font-size: 20px;}
        a.btn {display: inline-block;margin: 5px 5px 10px 0;font-size: 14px;}
}

    </style>
</head>
<body>

<h2>Data MUA</h2>
<a href="dashboard.php" class="btn">kembali</a>
<a href="tambah_mua.php" class="btn">+ Tambah MUA</a>
<br><br>

<table>
    <tr>
        <th>Nama</th>
        <th>Spesialisasi</th>
        <th>Harga</th>
        <th>Jadwal</th>
        <th>Email</th>
        <th>No. Telp</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = $mua->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['spesialisasi']) ?></td>
        <td>Rp<?= number_format($row['harga'], 0, ',', '.') ?></td>
        <td><?= htmlspecialchars($row['jadwal_tersedia']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['no_telp']) ?></td>
        <td>
            <a class="btn btn-danger" href="data_mua.php?hapus=<?= $row['id_mua'] ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            <a class="btn" href="edit_mua.php?id=<?= $row['id_mua'] ?>">Edit</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
