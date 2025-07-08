<?php
session_start();
if (!isset($_SESSION['id_mua'])) {
    header("Location: login_mua.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");
$id_mua = $_SESSION['id_mua'];
$pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $koneksi->real_escape_string($_POST['judul']);
    $deskripsi = $koneksi->real_escape_string($_POST['deskripsi']);
    $kategori = $koneksi->real_escape_string($_POST['kategori']);
    $tanggal = $_POST['tanggal'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed)) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $nama_file = uniqid('img_', true) . '.' . $file_ext;
            if (move_uploaded_file($file_tmp, $upload_dir . $nama_file)) {
                $stmt = $koneksi->prepare("INSERT INTO portofolio (id_mua, gambar, judul, deskripsi, kategori, tanggal) 
                                           VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $id_mua, $nama_file, $judul, $deskripsi, $kategori, $tanggal);
                $stmt->execute();
                $pesan = "✅ Portofolio berhasil ditambahkan.";
            } else {
                $pesan = "❌ Gagal menyimpan gambar.";
            }
        } else {
            $pesan = "❌ Format file tidak didukung. Hanya JPG, PNG, dan GIF.";
        }
    } else {
        $pesan = "❌ Gambar wajib diunggah.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Portofolio</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fffaf0; padding: 40px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 12px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #db7093; margin-bottom: 20px; }
        .msg { background: #eafaf1; padding: 12px; color: #2e7d32; border-radius: 5px; text-align: center; margin-bottom: 15px; }
        .error { background: #ffe5e5; color: #a94442; }
        form label { display: block; margin-top: 12px; font-weight: bold; }
        form input[type="text"],
        form input[type="date"],
        form select,
        form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }
        input[type="file"] {
            margin-top: 5px;
        }
        .btn-upload {
            margin-top: 20px;
            background: #db7093;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            cursor: pointer;
        }
        .kembali {
            display: block;
            text-align: center;
            margin-top: 25px;
            text-decoration: none;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>📸 Tambah Portofolio Lengkap</h2>

    <?php if (!empty($pesan)): ?>
        <div class="msg <?= strpos($pesan, '❌') !== false ? 'error' : '' ?>"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="judul">Judul Makeup</label>
        <input type="text" name="judul" id="judul" required>

        <label for="deskripsi">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4" required></textarea>

        <label for="kategori">Kategori</label>
        <select name="kategori" id="kategori" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Wedding">Wedding</option>
            <option value="Graduation">Graduation</option>
            <option value="Photoshoot">Photoshoot</option>
            <option value="Casual">Casual</option>
        </select>

        <label for="tanggal">Tanggal Makeup</label>
        <input type="date" name="tanggal" id="tanggal" required>

        <label for="gambar">Foto Portofolio</label>
        <input type="file" name="gambar" id="gambar" accept="image/*" required>

        <button type="submit" class="btn-upload">Simpan Portofolio</button>
    </form>

    <a class="kembali" href="mua_portofolio.php">⬅️ Kembali ke Portofolio</a>
</div>
</body>
</html>

<?php $koneksi->close(); ?>
