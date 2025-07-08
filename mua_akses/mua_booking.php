<?php
session_start();
if (!isset($_SESSION['id_mua'])) {
    header("Location: mua_login.php");
    exit();
}

// require_once 'kirim_email.php'; // Pastikan fungsi kirimEmail tersedia

$koneksi = new mysqli("localhost", "root", "", "mua");
$id_mua = $_SESSION['id_mua'];

// // Kirim reminder otomatis H-1
// $besok = date('Y-m-d', strtotime('+1 day'));
// $reminder_query = $koneksi->query("SELECT p.*, pl.nama AS nama_pelanggan, pl.email AS email_pelanggan, m.nama AS nama_mua, m.email AS email_mua
//     FROM pemesanan p
//     JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
//     JOIN mua m ON p.id_mua = m.id_mua
//     WHERE p.id_mua = $id_mua AND p.tanggal_pemesanan = '$besok'");

// while ($r = $reminder_query->fetch_assoc()) {
//     $subjek = "📅 Reminder Booking Besok - {$r['nama_mua']}";
//     $pesan = "
//         Hai {$r['nama_pelanggan']},<br><br>
//         Ini adalah pengingat bahwa Anda memiliki janji dengan <strong>{$r['nama_mua']}</strong> besok:<br>
//         📅 <strong>{$r['tanggal_pemesanan']}</strong><br>
//         ⏰ <strong>{$r['waktu_pemesanan']}</strong><br><br>
//         Silakan persiapkan diri Anda dengan baik. 😊<br><br>
//         Salam,<br><strong>Tim Booking MUA</strong>
//     ";
//     kirimEmail($r['email_pelanggan'], $subjek, $pesan);
// }

// Tampilkan daftar booking
$data = $koneksi->query("SELECT p.*, pl.nama AS nama_pelanggan, pl.no_telp, pb.metode
    FROM pemesanan p 
    JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
    JOIN pembayaran pb ON p.id_pemesanan = pb.id_pemesanan 
    WHERE p.id_mua = $id_mua ORDER BY p.tanggal_pemesanan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Booking</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fdf6f9; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        h2 { color: #db7093; text-align: center; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #eee; text-align: left; font-size: 14px; }
        th { background-color: #f9d7e2; color: #333; }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .Menunggu\ Konfirmasi { background: #fff3cd; color: #856404; }
        .Dikonfirmasi { background: #d1ecf1; color: #0c5460; }
        .Selesai { background: #d4edda; color: #155724; }
        a { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h2>📋 Daftar Booking dari Pelanggan</h2>

    <?php if ($data->num_rows > 0): ?>
        <table>
            <tr>
                <th>Nama Pelanggan</th>
                <th>No. Telp</th>
                <th>Tanggal</th>
                <th>Status Bayar</th>
            </tr>
            <?php while ($row = $data->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                <td><?= htmlspecialchars($row['no_telp']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_pemesanan']) ?></td>
            
            
                <td>
                    <?php if ($row['metode'] == null): ?>
                        <span class="status Menunggu\ Konfirmasi">Menunggu Konfirmasi</span>
                    <?php else: ?>
                        <span class="status Dikonfirmasi">Dikonfirmasi</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Belum ada booking yang masuk.</p>
    <?php endif; ?>

    <a href="mua_dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>
</body>
</html>

<?php $koneksi->close(); ?>
