<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");

$jumlah_mua = $koneksi->query("SELECT COUNT(*) FROM mua")->fetch_row()[0];
$jumlah_pelanggan = $koneksi->query("SELECT COUNT(*) FROM pelanggan")->fetch_row()[0];
$jumlah_booking = $koneksi->query("SELECT COUNT(*) FROM pemesanan")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #ffe4e1, #fffaf0);
            padding: 30px;
        }

        h1 {
            color: #db7093;
            margin-bottom: 20px;
            text-align: center;
        }

        .cards {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .card {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            width: 220px;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h2 {
            font-size: 40px;
            color: #db7093;
        }

        .card p {
            font-size: 18px;
            color: #444;
            margin-top: 5px;
        }

        .menu {
            display: flex;
            justify-content: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .menu a {
            display: inline-block;
            background: #fcdc56;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            color: #000;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            transition: background 0.3s;
        }

        .menu a:hover {
            background: #f7c841;
        }

        footer {
            text-align: center;
            margin-top: 50px;
            color: #888;
        }
    </style>
</head>
<body>

<h1>🎯 Dashboard Admin</h1>

<div class="cards">
    <div class="card">
        <h2><?= $jumlah_mua ?></h2>
        <p>Total MUA</p>
    </div>
    <div class="card">
        <h2><?= $jumlah_pelanggan ?></h2>
        <p>Total Pelanggan</p>
    </div>
    <div class="card">
        <h2><?= $jumlah_booking ?></h2>
        <p>Total Booking</p>
    </div>
</div>

<div class="menu">
    <a href="data_mua.php">🧑‍🎨 Kelola MUA</a>
    <a href="data_pemesanan.php">📅 Data Booking</a>
    <a href="data_pelanggan.php">👤 Data Pelanggan</a>
    <a href="logout.php">🚪 Keluar</a>
</div>

<footer>
    &copy; <?= date('Y') ?> Admin Booking MUA
</footer>

</body>
</html>
