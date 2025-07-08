<?php
session_start();
if (!isset($_SESSION['id_mua'])) {
    header("Location: login_mua.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");
$id_mua = $_SESSION['id_mua'];

$data = $koneksi->query("SELECT * FROM portofolio WHERE id_mua = $id_mua");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Portofolio Saya</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #fffaf5; padding: 40px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { color: #db7093; text-align: center; margin-bottom: 25px; }
        .gallery { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .item { width: 230px; background: #fffefe; border: 1px solid #ddd; border-radius: 8px; padding: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); transition: transform 0.2s; }
        .item:hover { transform: scale(1.02); }
        .item img { width: 100%; height: 150px; object-fit: cover; border-radius: 6px; }
        .info { margin-top: 10px; font-size: 14px; color: #444; }
        .info strong { display: block; color: #333; margin-bottom: 4px; font-size: 15px; }
        .btn-delete, .btn-edit {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 8px;
            padding: 8px 0;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-delete { background: #dc3545; color: white; }
        .btn-edit { background: #007bff; color: white; text-decoration: none; line-height: 34px; }
        .btn-tambah {
            display: inline-block;
            margin-bottom: 25px;
            background: #db7093;
            color: white;
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 15px;
        }
        a.kembali {
            display: block;
            margin-top: 30px;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            font-size: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🎨 Portofolio Saya</h2>

    <a class="btn-tambah" href="mua_portofolio_tambah.php">+ Tambah Portofolio</a>

    <div class="gallery">
        <?php if ($data->num_rows > 0): ?>
            <?php while ($row = $data->fetch_assoc()): ?>
                <div class="item">
                    <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" alt="Foto Portofolio">
                    <div class="info">
                        <strong><?= htmlspecialchars($row['judul']) ?></strong>
                        <em><?= htmlspecialchars($row['kategori']) ?></em><br>
                        <span><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></span>
                    </div>
                    <form method="POST" action="mua_portofolio_hapus.php" onsubmit="return confirm('Hapus gambar ini?')">
                        <input type="hidden" name="id_portofolio" value="<?= $row['id_portofolio'] ?>">
                        <button class="btn-delete" type="submit">🗑️ Hapus</button>
                    </form>
                    <a href="mua_portofolio_edit.php?id=<?= $row['id_portofolio'] ?>" class="btn-edit">✏️ Edit</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; color:#999;">Belum ada portofolio yang ditambahkan.</p>
        <?php endif; ?>
    </div>

    <a class="kembali" href="mua_dashboard.php">⬅️ Kembali ke Dashboard</a>
</div>
</body>
</html>

<?php $koneksi->close(); ?>
