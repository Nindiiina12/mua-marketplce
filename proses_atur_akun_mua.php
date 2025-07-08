<?php
include 'db.php';

// Ambil data dari form
$nama = $_POST['nama'];
$no_telp = $_POST['no_telp'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$spesialisasi = $_POST['spesialisasi'];
$harga = $_POST['harga'];
$jadwal = $_POST['jadwal'];

// Upload file
$foto_salon = $_FILES['foto_salon']['name'];
$portofolio = $_FILES['portofolio']['name'];

$target_dir = "uploads/";
if (!is_dir($target_dir)) mkdir($target_dir);

move_uploaded_file($_FILES['foto_salon']['tmp_name'], $target_dir . $foto_salon);
move_uploaded_file($_FILES['portofolio']['tmp_name'], $target_dir . $portofolio);

// Simpan ke database
$sql = "INSERT INTO MUA (nama, spesialisasi, harga, jadwal_tersedia)
        VALUES ('$nama', '$spesialisasi', '$harga', '$jadwal')";

if ($conn->query($sql) === TRUE) {
    echo "Akun MUA berhasil ditambahkan!";
} else {
    echo "Gagal: " . $conn->error;
}

$conn->close();
?>
