<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
// Set header untuk memberitahu browser bahwa responsnya adalah JSON
header('Content-Type: application/json');

// Siapkan array untuk respons
$response = ['success' => false, 'message' => 'Terjadi kesalahan tidak diketahui.'];

// Cek jika user sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    $response['message'] = 'Sesi tidak valid. Silakan login kembali.';
    echo json_encode($response);
    exit();
}

require 'kirim_email.php'; // Pastikan file ini ada dan terkonfigurasi dengan benar

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pelanggan = $_SESSION['id_pelanggan'];
    $id_mua = intval($_POST['id_mua']);
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];

    $koneksi = new mysqli("localhost", "root", "", "mua");
    if ($koneksi->connect_error) {
        $response['message'] = 'Koneksi ke database gagal.';
        echo json_encode($response);
        exit();
    }

    // Validasi: cek jika waktu sudah dipesan
    $cek = $koneksi->prepare("SELECT id_pemesanan FROM pemesanan WHERE id_mua = ? AND tanggal_pemesanan = ? AND waktu_pemesanan = ?");
    $cek->bind_param("iss", $id_mua, $tanggal, $waktu);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows > 0) {
        $response['message'] = 'Jadwal pada tanggal dan waktu ini sudah dibooking!';
        echo json_encode($response);
        $koneksi->close();
        exit();
    }
    $cek->close();

    // Simpan booking
    $stmt = $koneksi->prepare("INSERT INTO pemesanan (tanggal_pemesanan, waktu_pemesanan, id_pelanggan, id_mua) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $tanggal, $waktu, $id_pelanggan, $id_mua);

    if ($stmt->execute()) {
        // Ambil data untuk email
        $pelanggan = $koneksi->query("SELECT nama, email FROM pelanggan WHERE id_pelanggan = $id_pelanggan")->fetch_assoc();
        $mua = $koneksi->query("SELECT nama, email FROM mua WHERE id_mua = $id_mua")->fetch_assoc();

        // 🔔 Kirim Email Notifikasi
        // Pesan untuk pelanggan
        $subjekPelanggan = "📅 Konfirmasi Booking Anda dengan {$mua['nama']}";
        $pesanPelanggan = "Halo {$pelanggan['nama']},<br><br>Booking Anda dengan <strong>{$mua['nama']}</strong> telah berhasil dikonfirmasi untuk:<br>📆 Tanggal: <strong>$tanggal</strong><br>🕒 Waktu: <strong>$waktu</strong><br><br>Terima kasih.";
        
        // Pesan untuk MUA
        $subjekMUA = "📢 Booking Baru dari {$pelanggan['nama']}";
        $pesanMUA = "Hai {$mua['nama']},<br><br>Anda mendapat booking baru dari <strong>{$pelanggan['nama']}</strong>.<br>📆 Tanggal: <strong>$tanggal</strong><br>🕒 Waktu: <strong>$waktu</strong>.";

        // Kirim email dan PERIKSA hasilnya
        $kirim1 = kirimEmail($pelanggan['email'], $subjekPelanggan, $pesanPelanggan);
        $kirim2 = kirimEmail($mua['email'], $subjekMUA, $pesanMUA);

        if ($kirim1 && $kirim2) {
            // ✅ Jika kedua email berhasil dikirim
            $response['success'] = true;
            $response['message'] = 'Booking berhasil! Notifikasi telah dikirim.';
        } else {
            // ❌ Jika salah satu atau kedua email gagal
            $response['message'] = 'Booking berhasil disimpan, tetapi GAGAL mengirim email notifikasi. Silakan hubungi admin.';
            // Di sini Anda bisa menambahkan log error untuk tahu email mana yang gagal
        }

    } else {
        $response['message'] = 'Gagal menyimpan data booking ke database.';
    }

    $stmt->close();
    $koneksi->close();

    // Kirim respons JSON ke browser
    echo json_encode($response);
    exit();
}
?>