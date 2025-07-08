<?php
include 'db.php';

// Cek apakah parameter id ada
if (!isset($_GET['id'])) {
    echo "<p>ID MUA tidak ditemukan.</p>";
    exit;
}

$id_mua = (int) $_GET['id'];
$mua = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM mua WHERE id_mua = $id_mua"));

if (!$mua) {
    echo "<p>Data MUA tidak ditemukan di database.</p>";
    exit;
}

// Simulasi ID pelanggan login
$id_pelanggan = 1;

// Cek apakah pelanggan sudah booking MUA ini
$q_booking = mysqli_query($conn, "SELECT * FROM pemesanan WHERE id_mua = $id_mua AND id_pelanggan = $id_pelanggan");
$booking = mysqli_fetch_assoc($q_booking);

// Buat link WhatsApp
$no_wa = preg_replace('/^0/', '+62', $mua['no_telp']);
$link_wa = "https://wa.me/" . preg_replace('/[^0-9]/', '', $no_wa);
?>

<div class="jasa-container">
  <h2>Detail Jasa MUA</h2>
  <div class="jasa-detail">
    <p><strong>Nama:</strong> <?= htmlspecialchars($mua['nama']) ?></p>
    <p><strong>Spesialisasi:</strong> <?= htmlspecialchars($mua['spesialisasi']) ?></p>
    <p><strong>Harga:</strong> Rp<?= number_format($mua['harga'], 0, ',', '.') ?></p>
    <p><strong>Jadwal Tersedia:</strong><br><?= nl2br(htmlspecialchars($mua['jadwal_tersedia'])) ?></p>

    <a href="<?= $link_wa ?>" target="_blank" class="btn">Chat MUA</a>
    <a href="profil_mua.php?id=<?= $id_mua ?>" class="btn">Cek Profil</a>

    <div class="booking-box">
      <?php if ($booking): ?>
        <p><strong>Sudah Booking:</strong></p>
        <p>Tanggal: <?= $booking['tanggal_pemesanan'] ?> | Waktu: <?= $booking['waktu_pemesanan'] ?></p>
        <a href="batalkan_booking.php?id=<?= $booking['id_pemesanan'] ?>" class="btn-cancel" onclick="return confirm('Batalkan booking ini?')">Batalkan Booking</a>
      <?php else: ?>
        <p><em>Belum melakukan booking.</em></p>
        <a href="booking.php?id=<?= $id_mua ?>" class="btn-booking">Booking Sekarang</a>
      <?php endif; ?>
    </div>
  </div>
</div>
