<?php
session_start();

// Cegah pelanggan login masuk ke login MUA
if (isset($_SESSION['id_pelanggan'])) {
    header("Location: index.php");
    exit();
}

if (isset($_SESSION['id_mua'])) {
    header("Location: mua_dashboard.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "mua");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = $koneksi->query("SELECT * FROM mua WHERE email='$email' AND password='$password'");
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $_SESSION['id_mua'] = $data['id_mua'];
        $_SESSION['nama_mua'] = $data['nama'];
        header("Location: mua_dashboard.php");
        exit();
    } else {
        $error = "❌ Email atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login MUA</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #ffeef5, #fffaf0);
            margin: 0;
            padding: 50px 20px;
        }
        .container {
            max-width: 420px;
            margin: auto;
            background: #fff;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #c2185b;
            margin-bottom: 25px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #c2185b;
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
            color: #c2185b;
            text-decoration: none;
        }
        .link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>👩‍🎤 Login MUA</h2>
    <?php if (isset($error)) echo "<div class='msg'>$error</div>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="📧 Email" required>
        <input type="password" name="password" placeholder="🔐 Password" required>
        <button type="submit">Login</button>
    </form>
    <div class="link">
        Belum punya akun? <a href="mua_register.php">Daftar di sini</a>
    </div>
</div>
</body>
</html>
