<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");

// Cek apakah ID MUA diberikan
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID tidak valid.";
    exit();
}

$id = (int)$_GET['id'];

// Ambil data MUA berdasarkan ID
$result = $koneksi->query("SELECT * FROM mua WHERE id_mua = $id");
if ($result->num_rows === 0) {
    echo "Data MUA tidak ditemukan.";
    exit();
}
$mua = $result->fetch_assoc();

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama         = $koneksi->real_escape_string($_POST['nama']);
    $spesialisasi = $koneksi->real_escape_string($_POST['spesialisasi']);
    $harga        = (int)$_POST['harga'];
    $jadwal       = $koneksi->real_escape_string($_POST['jadwal']);
    $email        = $koneksi->real_escape_string($_POST['email']);
    $no_telp      = $koneksi->real_escape_string($_POST['no_telp']);

    // Update data
    $update = $koneksi->query("UPDATE mua SET 
        nama = '$nama',
        spesialisasi = '$spesialisasi',
        harga = $harga,
        jadwal_tersedia = '$jadwal',
        email = '$email',
        no_telp = '$no_telp'
        WHERE id_mua = $id
    ");

    if ($update) {
        header("Location: data_mua.php");
        exit();
    } else {
        $error = "Gagal menyimpan perubahan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data MUA</title>
    <style>
        body { font-family: sans-serif; padding: 30px; background: #fffaf0; }
        h2 { color: #db7093; }
        form { max-width: 500px; margin: auto; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        button { margin-top: 20px; background: #db7093; color: white; padding: 10px 16px; border: none; border-radius: 4px; cursor: pointer; }
        a { text-decoration: none; color: #007bff; display: block; margin-top: 20px; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>

<h2>Edit Data MUA</h2>

<?php if (!empty($error)): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    <label>Nama</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($mua['nama']) ?>" required>

    <label>Spesialisasi</label>
    <input type="text" name="spesialisasi" value="<?= htmlspecialchars($mua['spesialisasi']) ?>" required>

    <label>Harga (Rp)</label>
    <input type="number" name="harga" value="<?= htmlspecialchars($mua['harga']) ?>" required>

    <label>Jadwal Tersedia</label>
    <input type="text" name="jadwal" value="<?= htmlspecialchars($mua['jadwal_tersedia']) ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($mua['email']) ?>" required>

    <label>No. Telepon</label>
    <input type="text" name="no_telp" value="<?= htmlspecialchars($mua['no_telp']) ?>" required>

    <button type="submit">Simpan Perubahan</button>
    <a href="data_mua.php">⬅️ Kembali ke Data MUA</a>
</form>



</body>
</html>
