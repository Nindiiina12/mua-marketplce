<?php
include 'db.php';

$result = mysqli_query($conn, "SELECT * FROM mua");
?>

<h2>Daftar MUA</h2>
<div class="mua-list">
<?php while($row = mysqli_fetch_assoc($result)): ?>
  <div class="mua-box">
    <p><strong><?= $row['nama'] ?></strong></p>
    <p>Spesialisasi: <?= $row['spesialisasi'] ?></p>
    <p>Harga: Rp<?= number_format($row['harga'], 0, ',', '.') ?></p>
    <a href="detail_jasa.php?id=<?= $row['id_mua'] ?>" class="btn">Lihat Detail</a>
  </div>
<?php endwhile; ?>
</div>
