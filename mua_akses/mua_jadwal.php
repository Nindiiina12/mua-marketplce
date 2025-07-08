<?php
session_start();
if (!isset($_SESSION['id_mua'])) {
    header("Location: mua_login.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");
$id_mua = $_SESSION['id_mua'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jadwal = trim($_POST['jadwal']);
    $koneksi->query("UPDATE mua SET jadwal_tersedia='$jadwal' WHERE id_mua=$id_mua");
    $pesan = "✅ Jadwal berhasil diperbarui!";
}

$data = $koneksi->query("SELECT jadwal_tersedia FROM mua WHERE id_mua=$id_mua")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Jadwal</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fdf4f4;
            padding: 40px;
        }
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #db7093;
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            height: 120px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: vertical;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto 0;
        }
        .msg {
            text-align: center;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 15px;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #db7093;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>✍️ Edit Jadwal</h2>
    <?php if (isset($pesan)) echo "<div class='msg'>$pesan</div>"; ?>
    <form method="POST">
        <label for="jadwal">Masukkan Jadwal Tersedia Anda:</label>
        <textarea name="jadwal" required><?= htmlspecialchars($data['jadwal_tersedia']) ?></textarea>
        <button type="submit">💾 Simpan Jadwal</button>
    </form>
    <a href="mua_dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>
</body>
</html>

<?php $koneksi->close(); ?>
