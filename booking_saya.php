<?php
ob_start();
session_start();
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

$id_pelanggan = $_SESSION['id_pelanggan'];

$koneksi = new mysqli("localhost", "root", "", "mua");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Handle pembatalan booking
if (isset($_GET['batal'])) {
    require_once 'kirim_email.php'; // pastikan file ini tersedia

    $id = (int)$_GET['batal'];

    // Ambil data booking untuk dikirim ke email
    $booking = $koneksi->query("SELECT p.*, m.nama AS nama_mua, m.email AS email_mua, pel.nama AS nama_pelanggan, pel.email AS email_pelanggan
        FROM pemesanan p
        JOIN mua m ON p.id_mua = m.id_mua
        JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
        WHERE p.id_pemesanan = $id AND p.id_pelanggan = $id_pelanggan")->fetch_assoc();

    if ($booking) {
        // Email ke MUA
        $subjekMua = "❌ Booking Dibatalkan oleh Pelanggan";
        $pesanMua = "
            Hai {$booking['nama_mua']},<br><br>
            Booking dari <strong>{$booking['nama_pelanggan']}</strong> pada:<br>
            📅 <strong>{$booking['tanggal_pemesanan']}</strong> jam <strong>{$booking['waktu_pemesanan']}</strong><br>
            Telah <strong>dibatalkan</strong> oleh pelanggan.<br><br>
            Silakan update jadwal Anda.<br><br>
            Salam,<br><strong>Tim Booking MUA</strong>
        ";
        kirimEmail($booking['email_mua'], $subjekMua, $pesanMua);

        // Email ke Pelanggan
        $subjekPel = "❌ Konfirmasi Pembatalan Booking";
        $pesanPel = "
            Hai {$booking['nama_pelanggan']},<br><br>
            Anda telah membatalkan booking dengan <strong>{$booking['nama_mua']}</strong> pada:<br>
            📅 <strong>{$booking['tanggal_pemesanan']}</strong> jam <strong>{$booking['waktu_pemesanan']}</strong><br><br>
            Jika ini tidak disengaja, silakan booking ulang melalui aplikasi.<br><br>
            Salam,<br><strong>Tim Booking MUA</strong>
        ";
        kirimEmail($booking['email_pelanggan'], $subjekPel, $pesanPel);
    }

    // Hapus dari database
    $stmt = $koneksi->prepare("DELETE FROM pemesanan WHERE id_pemesanan = ? AND id_pelanggan = ?");
    // $stmt->bind_param("ii", $id, $id_pelanggan);
     $stmt->execute();
    $stmt->close();

    // header("Location: booking_saya.php?success=batal");
    $stmt = $koneksi->prepare("DELETE FROM pemesanan WHERE id_pemesanan = ? AND id_pelanggan = ?");
    $stmt->bind_param("ii", $id, $id_pelanggan);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Booking berhasil dibatalkan.');window.location.href='booking_saya.php?success=batal';</script>";
        } else {
            echo "<script>alert('Data tidak ditemukan atau bukan milik Anda.');window.location.href='booking_saya.php?error=notfound';</script>";
        }
    } else {
        echo "<script>alert('Gagal menjalankan query.');</script>";
    }

    $stmt->close();

    exit();
}

// Ambil data booking
$sql = "SELECT p.*, m.nama AS nama_mua,
            (SELECT COUNT(*) FROM pembayaran pb WHERE pb.id_pemesanan = p.id_pemesanan) AS sudah_bayar
        FROM pemesanan p 
        JOIN mua m ON p.id_mua = m.id_mua
        WHERE p.id_pelanggan = $id_pelanggan
        ORDER BY p.tanggal_pemesanan DESC";

$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Saya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fffaf0;
            padding: 30px;
        }
        h2 {
            color: #db7093;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #fcdc56;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: white;
        }
        .success { background-color: #28a745; }
        .warning { background-color: #ffc107; color: #000; }
        .danger { background-color: #dc3545; }
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin: 2px;
            display: inline-block;
            cursor: pointer;
        }
        .btn-cancel { background-color: #dc3545; color: white; }
        .btn-pay { background-color: #007bff; color: white; }
        .btn-home {
            display: inline-block;
            margin-top: 20px;
            background-color: #db7093;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .status-paid { color: green; font-weight: bold; }
        .status-unpaid { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2>🗓️ Daftar Booking Anda</h2>

<?php if (isset($_GET['success']) && $_GET['success'] === 'batal'): ?>
    <div class="alert danger">❌ Booking berhasil dibatalkan!</div>
<?php elseif (isset($_GET['success']) && $_GET['success'] === 'paid'): ?>
    <div class="alert success">💰 Pembayaran berhasil!</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'already_paid'): ?>
    <div class="alert warning">⚠️ Anda sudah membayar booking ini.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] === 'notfound'): ?>
    <div class="alert danger">❌ Data booking tidak ditemukan!</div>
<?php endif; ?>

<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>No</th>
            <th>Nama MUA</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_mua']) ?></td>
            <td><?= $row['tanggal_pemesanan'] ?></td>
            <td><?= $row['waktu_pemesanan'] ?></td>
            <td>
                <?php if ($row['sudah_bayar']): ?>
                    <span class="status-paid">Sudah Bayar</span>
                <?php else: ?>
                    <span class="status-unpaid">Belum Bayar</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!$row['sudah_bayar']): ?>
                    <a class="btn btn-pay" href="pembayaran.php?id=<?= $row['id_pemesanan'] ?>">Bayar</a>
                <?php endif; ?>
                <a class="btn btn-cancel" href="?batal=<?= $row['id_pemesanan'] ?>" onclick="return confirm('Yakin ingin membatalkan booking ini?')">Batalkan</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>Belum ada booking.</p>
<?php endif; ?>

<a class="btn-home" href="index.php">⬅️ Kembali ke Beranda</a>

</body>
</html>

<?php $koneksi->close(); ob_end_flush(); ?>