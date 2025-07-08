<?php
$koneksi = new mysqli("localhost", "root", "", "mua");
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $cek = $koneksi->query("SELECT * FROM admin WHERE username = '$username'");
    if ($cek->num_rows > 0) {
        $message = "❌ Username sudah terdaftar!";
    } else {
        $koneksi->query("INSERT INTO admin (username, password) VALUES ('$username', '$password')");
        // Redirect setelah berhasil daftar
        header("Location: admin_login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Admin</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            background: #fcdc56;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input {
            width: 100%;
            margin-bottom: 15px;
            padding: 8px;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
        }
        .msg {
            margin-bottom: 10px;
            font-weight: bold;
            color: #d63384;
        }
    </style>
</head>
<body>
<form method="POST">
    <h2>Daftar Admin</h2>
    <?php if ($message): ?><div class="msg"><?= $message ?></div><?php endif; ?>
    <input type="text" name="username" placeholder="Username" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Daftar</button>
</form>
</body>
</html>
