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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $ulasan = trim($_POST['ulasan']);
    $id_pelanggan = $_SESSION['id_pelanggan'];

    if ($rating >= 1 && $rating <= 5) {
        $stmt = $koneksi->prepare("INSERT INTO rating (id_mua, id_pelanggan, rating, ulasan) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $id_mua, $id_pelanggan, $rating, $ulasan);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
        exit();
    } else {
        $error = "Rating harus antara 1 sampai 5.";
    }
}

// Ambil nama MUA untuk ditampilkan
$stmt = $koneksi->prepare("SELECT nama FROM mua WHERE id_mua = ?");
$stmt->bind_param("i", $id_mua);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    die("MUA tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Beri Rating - <?= htmlspecialchars($data['nama']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff0f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        h2 {
            color: #db7093;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input[type="number"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        .btn {
            margin-top: 20px;
            background-color: #db7093;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #c15f84;
        }

        .back {
            display: inline-block;
            margin-top: 15px;
            color: #555;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Beri Ulasan untuk <?= htmlspecialchars($data['nama']) ?></h2>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="rating">Rating (1 - 5):</label>
        <input type="number" name="rating" id="rating" min="1" max="5" required>

        <label for="ulasan">Ulasan:</label>
        <textarea name="ulasan" id="ulasan" placeholder="Tulis ulasan Anda..." required></textarea>

        <button type="submit" class="btn">Kirim</button>
    </form>
    <a href="index.php" class="back">&larr; Kembali ke Beranda</a>
</div>
</body>
</html>

<?php $koneksi->close(); ?>
