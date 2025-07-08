<?php
$koneksi = new mysqli("localhost", "root", "", "mua");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $no_telp = trim($_POST['no_telp']);

    $check = $koneksi->query("SELECT * FROM mua WHERE email='$email'");
    if ($check->num_rows > 0) {
        $msg = "❌ Email sudah terdaftar!";
    } else {
        $koneksi->query("INSERT INTO mua (nama, email, password, no_telp) VALUES ('$nama', '$email', '$password', '$no_telp')");
        header("Location: mua_login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar MUA</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #fffaf0, #ffeef5);
            margin: 0;
            padding: 50px 20px;
        }
        .container {
            max-width: 440px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #d63384;
            margin-bottom: 25px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #d63384;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }
        .msg {
            color: red;
            text-align: center;
            margin-bottom: 12px;
        }
        .link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }
        .link a {
            color: #d63384;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>✍️ Daftar Sebagai MUA</h2>
    <?php if (isset($msg)) echo "<div class='msg'>$msg</div>"; ?>
    <form method="POST">
        <input type="text" name="nama" placeholder="👩 Nama Lengkap" required>
        <input type="email" name="email" placeholder="📧 Email" required>
        <input type="password" name="password" placeholder="🔐 Password" required>
        <input type="text" name="no_telp" placeholder="📞 No Telepon" required>
        <button type="submit">Daftar</button>
    </form>
    <div class="link">
        Sudah punya akun? <a href="mua_login.php">Login di sini</a>
    </div>
</div>
</body>
</html>
