<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Atur Akun</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            background: linear-gradient(to bottom right, #fff5eb, #ffe0ec);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .wrapper {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 450px;
            width: 100%;
        }

        h2, h3 {
            text-align: center;
            color: #db7093;
            margin-top: 0;
        }

        form {
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-update { background: #28a745; }
        .btn-pass { background: #007bff; }
        .btn-delete { background: #dc3545; }

        .msg {
            margin-bottom: 15px;
            font-weight: bold;
            color: #d63384;
            text-align: center;
        }

        a {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #db7093;
            margin-top: 10px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <h2>⚙️ Atur Akun</h2>
    <?php if ($pesan): ?><div class="msg"><?= $pesan ?></div><?php endif; ?>

    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>

        <label>No. Telp:</label>
        <input type="text" name="no_telp" value="<?= htmlspecialchars($data['no_telp']) ?>">

        <label>Alamat:</label>
        <textarea name="alamat"><?= htmlspecialchars($data['alamat']) ?></textarea>

        <button class="btn-update" name="update">💾 Simpan Perubahan</button>
    </form>

    <form method="POST">
        <h3>🔐 Ganti Password</h3>
        <input type="password" name="password_baru" placeholder="Password Baru" required>
        <input type="password" name="konfirmasi_password" placeholder="Konfirmasi Password" required>
        <button class="btn-pass" name="ganti_password">Ganti Password</button>
    </form>

    <form method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun?')">
        <button class="btn-delete" name="hapus_akun">Hapus Akun</button>
    </form>

    <a href="index.php">← Kembali ke Beranda</a>
</div>

</body>
</html>
