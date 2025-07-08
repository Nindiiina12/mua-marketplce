<?php
session_start();
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id_mua'])) {
    echo "ID MUA tidak ditemukan.";
    exit();
}

$id_mua = intval($_GET['id_mua']);

// Koneksi
$koneksi = new mysqli("localhost", "root", "", "mua");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data MUA
$sql = "SELECT * FROM mua WHERE id_mua = $id_mua";
$result = $koneksi->query($sql);
if ($result->num_rows == 0) {
    echo "MUA tidak ditemukan.";
    exit();
}
$mua = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking MUA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fffaf0;
            padding: 30px;
        }

        .card {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #db7093;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="date"],
        input[type="time"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-submit {
            background-color: #db7093;
            color: white;
            padding: 10px 20px;
            border: none;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #c25d80;
        }

        .info {
            background: #fcdc56;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        a.back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #db7093;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>📝 Booking: <?= htmlspecialchars($mua['nama']) ?></h2>

    <div class="info">
        <p><strong>Spesialisasi:</strong> <?= htmlspecialchars($mua['spesialisasi']) ?></p>
        <p><strong>Harga:</strong> Rp<?= number_format($mua['harga'], 0, ',', '.') ?></p>
        <p><strong>Jadwal Tersedia:</strong> <?= htmlspecialchars($mua['jadwal_tersedia']) ?></p>
    </div>

    <form action="proses_pesan.php" method="POST">
        <input type="hidden" name="id_mua" value="<?= $id_mua ?>">
        <label for="tanggal">Tanggal Booking:</label>
        <input type="date" name="tanggal" required>

        <label for="waktu">Waktu Booking:</label>
        <input type="time" name="waktu" required>

        <button type="submit" class="btn-submit">Kirim Booking</button>
    </form>

    <a href="index.php" class="back-link">⬅️ Kembali ke Beranda</a>
</div>

</body>
</html>
