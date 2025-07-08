<?php
session_start();
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: /mua/login.php");
    exit();
}

$showWelcome = false;
if (!isset($_SESSION['welcome_shown'])) {
    $_SESSION['welcome_shown'] = true;
    $showWelcome = true;
}

$koneksi = new mysqli("localhost", "root", "", "mua");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data MUA beserta rata-rata rating dan ulasan terbaru
$sql = "SELECT m.*, 
               COALESCE(AVG(r.rating), 0) AS rata_rating, 
               (SELECT ulasan FROM rating WHERE id_mua = m.id_mua ORDER BY id_rating DESC LIMIT 1) AS ulasan_terakhir 
        FROM mua m 
        LEFT JOIN rating r ON m.id_mua = r.id_mua 
        GROUP BY m.id_mua";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home - Booking MUA</title>
  <link rel="stylesheet" href="style.css">
  <style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: Arial, sans-serif;
        display: flex;
        flex-direction: column;
        background-image: url('download.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #28a745;
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        z-index: 9999;
        animation: fadeOut 0.5s ease-in-out 3s forwards;
        font-weight: bold;
    }

    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateY(-20px);
        }
    }

    header nav {
        display: flex;
        justify-content: center;
        gap: 30px;
        background: #fcdc56;
        padding: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    nav a {
        color: #000;
        text-decoration: none;
        font-weight: bold;
        padding: 6px 10px;
        border-radius: 4px;
        transition: background 0.3s;
    }

    nav a:hover {
        background-color: #ffe189;
    }

    .container {
        flex: 1;
        padding: 30px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 25px;
    }

    .mua-box {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 20px;
        border-radius: 10px;
        width: 300px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: transform 0.3s;
    }

    .mua-box:hover {
        transform: translateY(-5px);
    }

    .mua-box h3 {
        margin: 0;
        font-size: 20px;
        color: #db7093;
    }

    .mua-box p {
        margin: 5px 0;
        color: #333;
    }

    .btn-book {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 15px;
        background-color: #db7093;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-right: 5px;
        font-size: 14px;
    }

    .stars {
        color: gold;
    }

    footer {
        background: #fcdc56;
        padding: 12px;
        text-align: center;
        font-weight: bold;
    }
  </style>
</head>
<body>

<?php if ($showWelcome): ?>
<div class="toast">
    ✅ Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?>!<br>
    Anda berhasil login ke sistem Booking MUA.
</div>
<?php endif; ?>

<header>
    <nav>
        <a href="index.php">🏠 Beranda</a>
        <a href="atur_akun.php">⚙️ Atur Akun</a>
        <a href="booking_saya.php">📅 Booking Saya</a>
        <a href="logout.php">🚪 Keluar</a>
    </nav>
</header>

<div class="container">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="mua-box">
                <h3><a href="profil_mua.php?id=<?= $row['id_mua'] ?>"><?= htmlspecialchars($row['nama']) ?></a></h3>
                <p><strong>Spesialisasi:</strong> <?= htmlspecialchars($row['spesialisasi']) ?></p>
                <p><strong>Harga:</strong> Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
                <p><strong>Jadwal:</strong> <?= htmlspecialchars($row['jadwal_tersedia']) ?></p>
                <p class="stars">⭐ <?= number_format($row['rata_rating'], 1) ?> / 5</p>
                <?php if ($row['ulasan_terakhir']): ?>
                    <p><em>"<?= htmlspecialchars($row['ulasan_terakhir']) ?>"</em></p>
                <?php endif; ?>
                <a class="btn-book" href="pesan.php?id_mua=<?= $row['id_mua'] ?>">Booking</a>
                <a class="btn-book" href="profil_mua.php?id=<?= $row['id_mua'] ?>">Detail</a>
                <a class="btn-book" href="beri_rating.php?id=<?= $row['id_mua'] ?>">⭐ Beri Ulasan</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="color: white; text-align:center;">Tidak ada MUA yang tersedia.</p>
    <?php endif; ?>
</div>

<!-- Tambahan konten container baru -->
<div class="container">
    <div class="mua-box">
        <h3>Info Tambahan</h3>
        <p>Selamat datang di layanan Booking MUA. Silakan pilih MUA favorit Anda dan jangan lupa beri ulasan ⭐</p>
    </div>
</div>

<footer>
    &copy; <?= date('Y') ?> Booking MUA | Semua Hak Dilindungi
</footer>

</body>
</html>

<?php $koneksi->close(); ?>
