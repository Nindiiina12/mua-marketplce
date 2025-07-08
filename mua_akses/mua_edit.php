<?php
session_start();
if (!isset($_SESSION['id_mua'])) {
    header("Location: mua_login.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");
$id_mua = $_SESSION['id_mua'];

// Ambil data profil MUA
$data = $koneksi->query("SELECT * FROM mua WHERE id_mua = $id_mua")->fetch_assoc();

$pesan = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profil'])) {
        $nama = trim($_POST['nama']);
        $spesialisasi = trim($_POST['spesialisasi']);
        $jadwal = trim($_POST['jadwal_tersedia']);
        $harga = intval($_POST['harga']);

        $stmt = $koneksi->prepare("UPDATE mua SET nama=?, spesialisasi=?, jadwal_tersedia=?, harga=? WHERE id_mua=?");
        $stmt->bind_param("sssii", $nama, $spesialisasi, $jadwal, $harga, $id_mua);
        $stmt->execute();

        $pesan = "✅ Profil berhasil diperbarui!";
        $data = $koneksi->query("SELECT * FROM mua WHERE id_mua = $id_mua")->fetch_assoc();
    }

    if (isset($_POST['upload_portofolio']) && isset($_FILES['gambar'])) {
        $file = $_FILES['gambar'];
        $namaFile = basename($file['name']);
        $tujuan = "portofolio/" . $namaFile;

        if (move_uploaded_file($file['tmp_name'], $tujuan)) {
            $koneksi->query("INSERT INTO portofolio (id_mua, gambar) VALUES ($id_mua, '$namaFile')");
            $pesan = "✅ Portofolio berhasil diupload.";
        } else {
            $pesan = "❌ Gagal upload gambar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil MUA</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #fffaf0;
      padding: 40px;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #db7093;
    }
    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }
    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }
    .btn {
      background: #db7093;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      margin-top: 20px;
      cursor: pointer;
    }
    .msg {
      background: #e6ffe6;
      border: 1px solid #28a745;
      padding: 10px;
      border-radius: 5px;
      margin-top: 15px;
      color: #155724;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>🎨 Edit Profil MUA</h2>
  <?php if ($pesan): ?><div class="msg"><?= $pesan ?></div><?php endif; ?>

  <form method="POST">
    <label>Nama:</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

    <label>Spesialisasi:</label>
    <input type="text" name="spesialisasi" value="<?= htmlspecialchars($data['spesialisasi']) ?>" required>

    <label>Jadwal Tersedia:</label>
    <textarea name="jadwal_tersedia" rows="3"><?= htmlspecialchars($data['jadwal_tersedia']) ?></textarea>

    <label>Harga (Rp):</label>
    <input type="number" name="harga" value="<?= $data['harga'] ?>" required>

    <button class="btn" name="update_profil">💾 Simpan Perubahan</button>
  </form>

  <hr style="margin:30px 0;">

  <form method="POST" enctype="multipart/form-data">
    <label>Upload Gambar Portofolio:</label>
    <input type="file" name="gambar" accept="image/*" required>
    <button class="btn" name="upload_portofolio">📤 Upload</button>
  </form>
</div>
</body>
</html>
