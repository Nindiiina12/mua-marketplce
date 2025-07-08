<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");

// Handle hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $koneksi->query("DELETE FROM pelanggan WHERE id_pelanggan = $id");
    header("Location: data_pelanggan.php");
    exit();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $email = $koneksi->real_escape_string($_POST['email']);
    $no_telp = $koneksi->real_escape_string($_POST['no_telp']);
    $alamat = $koneksi->real_escape_string($_POST['alamat']);
    $koneksi->query("UPDATE pelanggan SET nama='$nama', email='$email', no_telp='$no_telp', alamat='$alamat' WHERE id_pelanggan=$id");
}

// Pencarian
$search = "";
if (isset($_GET['cari'])) {
    $search = $koneksi->real_escape_string($_GET['cari']);
    $pelanggan = $koneksi->query("SELECT * FROM pelanggan WHERE nama LIKE '%$search%' OR email LIKE '%$search%'");
} else {
    $pelanggan = $koneksi->query("SELECT * FROM pelanggan");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan</title>
    <style>
        body {
            font-family: sans-serif;
            background: #fffaf0;
            padding: 30px;
        }

        h2 {
            color: #db7093;
        }

        input[type="text"] {
            padding: 8px;
            margin-bottom: 10px;
            width: 300px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #ffffff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background: #fcdc56;
        }

        a {
            color: #db7093;
            text-decoration: none;
            font-weight: bold;
        }

        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-edit { background: #ffc107; }
        .btn-hapus { background: #dc3545; color: white; }
        .btn-update { background: #28a745; color: white; }

        .back-link {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
<h2>👤 Data Pelanggan</h2>

<form method="GET">
    <input type="text" name="cari" placeholder="Cari nama atau email..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Cari</button>
</form>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>No Telp</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = $pelanggan->fetch_assoc()):
        ?>
        <tr>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $row['id_pelanggan'] ?>">
                <td><?= $no++ ?></td>
                <td><input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>"></td>
                <td><input type="text" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
                <td><input type="text" name="no_telp" value="<?= htmlspecialchars($row['no_telp']) ?>"></td>
                <td><input type="text" name="alamat" value="<?= htmlspecialchars($row['alamat']) ?>"></td>
                <td>
                    <button class="btn btn-update" name="update">Update</button>
                    <a class="btn btn-hapus" href="?hapus=<?= $row['id_pelanggan'] ?>" onclick="return confirm('Yakin ingin hapus pelanggan ini?')">Hapus</a>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a class="back-link" href="dashboard.php">⬅️ Kembali ke Dashboard</a>
</body>
</html>
