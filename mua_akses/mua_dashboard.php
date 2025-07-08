<?php
session_start();
if (!isset($_SESSION['id_mua'])) {
    header("Location: mua_login.php");
    exit();
}

$nama_mua = $_SESSION['nama_mua'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard MUA</title>
    <style>
        * {
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fdf4f4;
            color: #333;
            display: flex;
            flex-direction: column;
        }
        header {
            background-color: #db7093;
            color: white;
            padding: 20px;
            text-align: center;
        }
        h1 {
            margin: 0;
        }
        .main-content {
            flex: 1;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        .card {
            background-color: #ffeef3;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            transition: 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .card:hover {
            background-color: #fcd4e0;
            transform: scale(1.02);
        }
        .card a {
            color: #db7093;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            display: block;
        }
        .icon {
            font-size: 38px;
            margin-bottom: 12px;
        }
        footer {
            text-align: center;
            padding: 15px;
            background-color: #fce2ea;
            font-size: 14px;
            border-top: 1px solid #f6c7d7;
        }
    </style>
</head>
<body>
    <header>
        <h1>Selamat Datang, <?= htmlspecialchars($nama_mua) ?>!</h1>
        <p>Dashboard Makeup Artist</p>
    </header>

    <div class="main-content">
        <div class="container">
            <div class="menu">
                <div class="card">
                    <div class="icon">📅</div>
                    <a href="mua_jadwal.php">Edit Jadwal</a>
                </div>
                <div class="card">
                    <div class="icon">✅</div>
                    <a href="mua_reminder.php">Reminder Booking</a>
                </div>
                <!-- <div class="card">
                    <div class="icon">💰</div>
                    <a href="mua_pembayaran.php">Verifikasi Pembayaran</a>
                </div> -->
                <div class="card">
                    <div class="icon">🧾</div>
                    <a href="mua_booking.php">Daftar Booking</a>
                </div>
                <div class="card">
                    <div class="icon">📋</div>
                    <a href="mua_portofolio.php">Kelola Portofolio</a>
                </div>
                <div class="card">
                    <div class="icon">📝</div>
                    <a href="mua_logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        &copy; <?= date('Y') ?> Dashboard MUA - Booking Online
    </footer>
</body>
</html>
