<?php
session_start();
if (!isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "ID pemesanan tidak ditemukan.";
    exit();
}

$id_pemesanan = intval($_GET['id']);
$koneksi = new mysqli("localhost", "root", "", "mua");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data booking
$sql = "SELECT p.*, m.nama AS nama_mua, m.harga FROM pemesanan p 
        JOIN mua m ON p.id_mua = m.id_mua
        WHERE p.id_pemesanan = $id_pemesanan AND p.id_pelanggan = {$_SESSION['id_pelanggan']}";
$result = $koneksi->query($sql);
if ($result->num_rows == 0) {
    echo "Data booking tidak ditemukan.";
    exit();
}
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Metode Pembayaran</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ffe3e3, #fff6f0);
            padding: 40px;
        }
        .container {
            background: white;
            padding: 30px;
            max-width: 550px;
            margin: auto;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #f4dada;
        }
        h2 {
            text-align: center;
            color: #db7093;
            margin-bottom: 25px;
            font-size: 26px;
        }
        p {
            font-size: 15px;
            margin-bottom: 10px;
            color: #333;
        }
        .highlight {
            font-weight: bold;
            color: #444;
            display: inline-block;
            min-width: 150px;
        }
        .radio-group {
            margin: 20px 0;
        }
        label {
            font-weight: 500;
        }
        input[type="radio"] {
            margin-right: 8px;
        }
        .bank-info, .qris-info {
            display: none;
            padding: 15px;
            border: 1px solid #ccc;
            margin-top: 10px;
            background: #fefefe;
            border-radius: 8px;
            font-size: 14px;
            line-height: 1.5;
        }
        .btn {
            background-color: #db7093;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: block;
            margin: 25px auto 0;
            font-size: 15px;
        }
        .btn:hover {
            background-color: #c95c83;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>💸 Metode Pembayaran</h2>

    <p><span class="highlight">👩‍🎤 Nama MUA:</span> <?= htmlspecialchars($data['nama_mua']) ?></p>
    <p><span class="highlight">📅 Tanggal Booking:</span> <?= date("d M Y", strtotime($data['tanggal_pemesanan'])) ?></p>
    <p><span class="highlight">💰 Harga:</span> <span style="color:#d63384;">Rp<?= number_format($data['harga'], 0, ',', '.') ?></span></p>

    <form action="proses_pembayaran.php" method="POST">
        <input type="hidden" name="id_pemesanan" value="<?= $id_pemesanan ?>">
        
        <div class="radio-group">
            <label><strong>Pilih Metode Pembayaran:</strong></label><br><br>
            <input type="radio" name="metode" value="cod" id="cod" required>
            <label for="cod">💵 Cash on Delivery (COD)</label><br>

            <input type="radio" name="metode" value="qris" id="qris">
            <label for="qris">📱 QRIS</label><br>

            <input type="radio" name="metode" value="transfer" id="transfer">
            <label for="transfer">🏦 Transfer Bank</label>
        </div>

        <div id="qris-info" class="qris-info">
            <strong>📷 Scan QRIS berikut:</strong><br><br>
            <img src="qris.jpg" alt="QRIS" width="200">
        </div>

        <div id="bank-info" class="bank-info">
            <strong>🏦 Transfer ke rekening berikut:</strong><br>
            BCA - <b>1234567890</b> a.n <i>PT MUA Cantik</i><br>
            Mandiri - <b>0987654321</b> a.n <i>MUA Booking Indonesia</i><br>
            BNI - <b>5678901234</b> a.n <i>MUA Online Service</i>
        </div>

        <button class="btn" type="submit">Bayar Sekarang</button>
    </form>

    <a class="back-link" href="booking_saya.php">⬅️ Kembali ke Booking Saya</a>
</div>

<script>
document.querySelectorAll('input[name="metode"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        document.getElementById('qris-info').style.display = this.value === 'qris' ? 'block' : 'none';
        document.getElementById('bank-info').style.display = this.value === 'transfer' ? 'block' : 'none';
    });
});
</script>
</body>
</html>

<?php $koneksi->close(); ?>
