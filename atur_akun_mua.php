<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Atur Akun MUA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <a href="#">contact us</a>
        <a href="#">atur akun</a>
        <a href="#">booking now</a>
    </nav>
</header>

<h3 style="margin-left: 50px;">ATUR AKUN MUA ANDA</h3>

<form action="proses_atur_akun_mua.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; align-items: flex-start; margin-left: 50px;">
    <label>nama</label>
    <input type="text" name="nama" required>

    <label>nomor hp</label>
    <input type="text" name="no_telp" required>

    <label>email</label>
    <input type="email" name="email" required>

    <label>password</label>
    <input type="password" name="password" required>

    <label>jenis layanan</label>
    <input type="text" name="spesialisasi" required>

    <label>harga layanan</label>
    <input type="text" name="harga" required>

    <label>atur jadwal calendly anda</label>
    <input type="text" name="jadwal" placeholder="link calendly" required>

    <label>masukkan foto salon</label>
    <input type="file" name="foto_salon">

    <label>masukkan portofolio (bila ada)</label>
    <input type="file" name="portofolio">

    <br>
    <button type="submit">upload</button>
</form>

<footer>
    FOOTER
</footer>
</body>
</html>
