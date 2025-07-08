<?php
session_start();
if (!isset($_SESSION['id_mua'])) {
    header("Location: login_mua.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");
$id_mua = $_SESSION['id_mua'];

if (isset($_POST['verifikasi']) && isset($_POST['id_pemesanan'])) {
    $id_pemesanan = (int)$_POST['id_pemesanan'];
    $koneksi->query("UPDATE pemesanan SET pembayaran='Terverifikasi' WHERE id_pemesanan=$id_pemesanan AND id_mua=$id_mua");
    $pesan = "✅ Pembayaran berhasil diverifikasi.";
}

$data = $koneksi->query("SELECT p.*, pl.nama AS nama_pelanggan FROM pemesanan p 
    JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan 
    WHERE p.id_mua = $id_mua AND p.status = 'Dikonfirmasi' AND pembayaran = 'Belum' ");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f0ff; padding: 40px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        h2 { color: #9b59b6; text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        th { background-color: #e7d9f6; }
        form { margin: 0; }
        .btn { background: #9b59b6; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        a { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <h2>💰 Verifikasi Pembayaran Client</h2>
    <?php if (isset($pesan)): ?><div class="msg"><?= $pesan ?></div><?php endif; ?>

    <?php if ($data->num_rows > 0): ?>
        <table>
            <tr>
                <th>Nama Client</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_pemesanan']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_pemesanan" value="<?= $row['id_pemesanan'] ?>">
                        <button class="btn" name="verifikasi">Verifikasi</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Tidak ada pembayaran yang perlu diverifikasi.</p>
    <?php endif; ?>

    <a href="mua_dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>
</body>
</html>

<?php $koneksi->close(); ?>
