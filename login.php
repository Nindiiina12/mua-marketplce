<?php
session_start();

// Jika sudah login sebagai pelanggan, alihkan
if (isset($_SESSION['id_pelanggan'])) {
  header("Location: index.php");
  exit();
}

// Jika login sebagai MUA, alihkan ke dashboard MUA
if (isset($_SESSION['id_mua'])) {
  header("Location: /mua_akses/mua_dashboard.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login & Registrasi Pelanggan</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      height: 100vh;
      background: linear-gradient(to right, #72c6ef, #ac94f4);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      width: 350px;
      padding: 30px 20px;
      position: relative;
      display: none;
      animation: fade 0.3s ease;
    }

    .form-container.active {
      display: block;
    }

    .tab-group {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }

    .tab {
      flex: 1;
      padding: 10px;
      text-align: center;
      cursor: pointer;
      background: #eee;
      border: none;
      font-weight: bold;
      transition: 0.3s;
    }

    .tab.active {
      background: #0056b3;
      color: white;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 6px;
      border: 1px solid #ccc;
      text-align: center;
    }

    .form-container button.submit {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      background-color: #0056b3;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .form-container a {
      font-size: 14px;
      display: block;
      text-align: center;
      margin-top: 10px;
      color: #007bff;
      text-decoration: none;
    }

    .form-container a:hover {
      text-decoration: underline;
    }

    @keyframes fade {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>

  <!-- Login Form -->
  <div id="loginBox" class="form-container active">
    <div class="tab-group">
      <button class="tab active" onclick="showLogin()">Login</button>
      <button class="tab" onclick="showSignup()">Daftar</button>
    </div>
    <h2>Login Pelanggan</h2>
    <form action="proses_login.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <a href="#">Lupa password?</a>
        <button class="submit" type="submit">Login</button>
        <a href="#" onclick="showSignup()">Belum punya akun? Daftar sekarang</a>
    </form>
  </div>

  <!-- Signup Form -->
  <div id="signupBox" class="form-container">
    <div class="tab-group">
      <button class="tab" onclick="showLogin()">Login</button>
      <button class="tab active" onclick="showSignup()">Daftar</button>
    </div>
    <h2>Registrasi Pelanggan</h2>
    <form action="proses_signup.php" method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="username" placeholder="Nama Pengguna" required>
        <input type="password" name="password" placeholder="Password" required>
        <button class="submit" type="submit">Daftar</button>
        <a href="#" onclick="showLogin()">Sudah punya akun? Login sekarang</a>
    </form>
  </div>

  <script>
    function showLogin() {
      document.getElementById('loginBox').classList.add('active');
      document.getElementById('signupBox').classList.remove('active');
      document.querySelectorAll('.tab')[0].classList.add('active');
      document.querySelectorAll('.tab')[1].classList.remove('active');
    }

    function showSignup() {
      document.getElementById('loginBox').classList.remove('active');
      document.getElementById('signupBox').classList.add('active');
      document.querySelectorAll('.tab')[0].classList.remove('active');
      document.querySelectorAll('.tab')[1].classList.add('active');
    }
  </script>

</body>
</html>
