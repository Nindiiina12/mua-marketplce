<?php
session_start();
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID MUA tidak valid.");
}

$id_mua = intval($_GET['id']);
$koneksi = new mysqli("localhost", "root", "", "mua");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$stmt = $koneksi->prepare("SELECT * FROM mua WHERE id_mua = ?");
$stmt->bind_param("i", $id_mua);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("MUA tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil MUA - <?= htmlspecialchars($data['nama']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fefefe;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            background: #fff8dc;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        h2 {
            color: #db7093;
        }

        .back {
            margin-top: 20px;
            display: inline-block;
            padding: 8px 14px;
            background: #db7093;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        p {
            margin: 8px 0;
        }
        .chat-btn {
        display: inline-block;
        margin-top: 14px;
        padding: 8px 14px;
        background-color: #25d366;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2><?= htmlspecialchars($data['nama']) ?></h2>
    <p><strong>Spesialisasi:</strong> <?= htmlspecialchars($data['spesialisasi']) ?></p>
    <p><strong>Harga:</strong> Rp<?= number_format($data['harga'], 0, ',', '.') ?></p>
    <p><strong>Jadwal Tersedia:</strong> <?= htmlspecialchars($data['jadwal_tersedia']) ?></p>
    <?php if ($data['email']): ?>
        <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
    <?php endif; ?>
    <?php if ($data['no_telp']): ?>
        <p><strong>No. Telepon:</strong> <?= htmlspecialchars($data['no_telp']) ?></p>
    <?php endif; ?>

    <a href="index.php" class="back">← Kembali</a>
    <?php

    $no_wa = preg_replace('/^0/', '+62', $data['no_telp']);
    $link_wa = "https://wa.me/" . preg_replace('/[^0-9]/', '', $no_wa);
    ?>
    <a href="<?= $link_wa ?>" target="_blank" class="chat-btn">💬 Chat via WhatsApp</a>

</div>

</body>
</html>
<?php $stmt->close(); $koneksi->close(); ?>
