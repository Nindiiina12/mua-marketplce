<!DOCTYPE html>
<html>
<head>
    <title>Login MUA</title>
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
<form action="proses_login.php" method="post">
    <button type="submit">LOGIN</button>
    <input type="text" name="email" placeholder="EMAIL/PENGGUNA" required>
    <input type="password" name="password" placeholder="PASSWORD" required>
</form>
<p>
    Belum punya akun?<br>
    <a href="signup.php">Sign Up disini</a>
</p>

<footer>
    FOOTER
</footer>
</body>
</html>
