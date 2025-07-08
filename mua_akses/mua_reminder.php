<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['id_mua'])) {
    header("Location: login_mua.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");
if ($koneksi->connect_error) die("Koneksi gagal");

$id_mua = $_SESSION['id_mua'];

// Load fungsi kirim email
require_once '../kirim_reminder.php';

$pesan = "";

// Handle kirim reminder
if (isset($_POST['reminder']) && isset($_POST['id_pemesanan'])) {
    $id_pemesanan = (int)$_POST['id_pemesanan'];

    $query = $koneksi->query("SELECT p.*, pl.nama AS nama_pelanggan, pl.email AS email_pelanggan, m.nama AS nama_mua 
        FROM pemesanan p 
        JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan 
        JOIN mua m ON p.id_mua = m.id_mua 
        JOIN pembayaran pb ON pb.id_pemesanan = p.id_pemesanan
        WHERE p.id_pemesanan = $id_pemesanan AND p.id_mua = $id_mua AND pb.metode IS NOT NULL");

    if ($query && $row = $query->fetch_assoc()) {
        $judul = "📢 Reminder Booking Anda Bersama {$row['nama_mua']}";
        $isi = "
            Hai {$row['nama_pelanggan']},<br><br>
            Ini pengingat bahwa Anda memiliki janji dengan <strong>{$row['nama_mua']}</strong> pada:<br>
            📅 <strong>{$row['tanggal_pemesanan']}</strong> pukul <strong>{$row['waktu_pemesanan']}</strong>.<br><br>
            Silakan pastikan kehadiran dan kesiapan Anda.<br><br>
            Salam,<br><strong>Booking MUA</strong>
        ";

        if (kirimEmail($row['email_pelanggan'], $judul, $isi)) {
            $pesan = "📧 Email reminder berhasil dikirim ke {$row['nama_pelanggan']}.";
        } else {
            $pesan = "❌ Gagal mengirim email reminder.";
        }
    } else {
        $pesan = "⚠️ Booking tidak ditemukan atau belum dikonfirmasi.";
    }
}

// Ambil daftar
$belum_dikonfirmasi = $koneksi->query("SELECT p.*, pl.nama AS nama_pelanggan 
    FROM pemesanan p 
    JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
    LEFT JOIN pembayaran pb ON pb.id_pemesanan = p.id_pemesanan
    WHERE p.id_mua = $id_mua AND pb.metode IS NULL");

$sudah_dikonfirmasi = $koneksi->query("SELECT p.*, pl.nama AS nama_pelanggan 
    FROM pemesanan p 
    JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan
    JOIN pembayaran pb ON pb.id_pemesanan = p.id_pemesanan
    WHERE p.id_mua = $id_mua AND pb.metode IS NOT NULL");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Booking</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fff0f4; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        h2 { color: #db7093; text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        th { background-color: #f9d7e2; }
        .btn {
            background: #28a745; color: white; padding: 6px 12px;
            border: none; border-radius: 5px; cursor: pointer; margin: 2px;
        }
        .btn-reminder { background: #007bff; }
        .msg {
            background: #d4edda; color: #155724;
            padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;
        }
        a { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; text-align: center; }
    </style>
</head>
<body>
<div class="container">
    <h2>✅ Reminder Booking</h2>

    <?php if (!empty($pesan)): ?>
        <div class="msg"><?= $pesan ?></div>
    <?php endif; ?>

    <h3>🕒 Menunggu Konfirmasi</h3>
    <?php if ($belum_dikonfirmasi->num_rows > 0): ?>
        <table>
            <tr><th>Nama Client</th><th>Tanggal</th></tr>
            <?php while ($row = $belum_dikonfirmasi->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pemesanan']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Tidak ada booking yang menunggu konfirmasi.</p>
    <?php endif; ?>

    <h3>✅ Sudah Dikonfirmasi</h3>
    <?php if ($sudah_dikonfirmasi->num_rows > 0): ?>
        <table>
            <tr><th>Nama Client</th><th>Tanggal</th><th>Aksi</th></tr>
            <?php while ($row = $sudah_dikonfirmasi->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal_pemesanan']) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="id_pemesanan" value="<?= $row['id_pemesanan'] ?>">
                            <button class="btn btn-reminder" name="reminder">Kirim Reminder</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>Belum ada booking yang dikonfirmasi.</p>
    <?php endif; ?>

    <a href="mua_dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>
</body>
</html>

<?php $koneksi->close(); ?>
