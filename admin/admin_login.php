<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "mua");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $koneksi->query("SELECT * FROM admin WHERE username = '$username'");
    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        }
    }
    $error = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <style>
        body { font-family: sans-serif; display: flex; height: 100vh; justify-content: center; align-items: center; background: #fcdc56; }
        form { background: white; padding: 30px; border-radius: 10px; width: 300px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; margin-bottom: 15px; padding: 8px; }
        button { background: #db7093; color: white; border: none; padding: 10px; width: 100%; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>
<form method="POST">
    <h2>Login Admin</h2>
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
    <input type="text" name="username" placeholder="Username Admin" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Masuk</button>
    <p style="text-align:center;margin-top:10px;">Belum punya akun? <a href="admin_register.php">Daftar</a></p>
</form>
</body>
</html>
