<?php
require 'kirim_email.php';

$koneksi = new mysqli("localhost", "root", "", "mua");
if ($koneksi->connect_error) die("Koneksi gagal: " . $koneksi->connect_error);

// Ambil tanggal besok
$besok = date('Y-m-d', strtotime('+1 day'));

// Ambil data booking yang akan berlangsung besok dan sudah dikonfirmasi
$sql = "SELECT b.*, 
               p.email AS email_client, p.nama AS nama_client, 
               m.email AS email_mua, m.nama AS nama_mua 
        FROM booking b
        JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
        JOIN mua m ON b.id_mua = m.id_mua
        WHERE b.tanggal = ? AND b.status = 'Dikonfirmasi'";

$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $besok);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Reminder untuk MUA
    $pesanMUA = "
        Hai {$row['nama_mua']},<br><br>
        Ini adalah pengingat bahwa Anda memiliki booking dengan pelanggan <strong>{$row['nama_client']}</strong> besok:<br>
        📅 Tanggal: <strong>{$row['tanggal']}</strong><br>
        ⏰ Waktu: <strong>{$row['waktu']}</strong><br><br>
        Pastikan Anda siap dan datang tepat waktu.<br><br>
        Salam,<br><strong>Tim Booking MUA</strong>
    ";
    kirimEmail($row['email_mua'], "⏰ Reminder: Booking Besok - {$row['nama_client']}", $pesanMUA);

    // Reminder untuk Pelanggan
    $pesanClient = "
        Hai {$row['nama_client']},<br><br>
        Ini adalah pengingat bahwa Anda memiliki booking dengan <strong>{$row['nama_mua']}</strong> besok:<br>
        📅 Tanggal: <strong>{$row['tanggal']}</strong><br>
        ⏰ Waktu: <strong>{$row['waktu']}</strong><br><br>
        Harap hadir tepat waktu dan pastikan semua keperluan telah dipersiapkan.<br><br>
        Salam,<br><strong>Tim Booking MUA</strong>
    ";
    kirimEmail($row['email_client'], "⏰ Reminder: Booking Besok - {$row['nama_mua']}", $pesanClient);
}

$stmt->close();
$koneksi->close();
?>
