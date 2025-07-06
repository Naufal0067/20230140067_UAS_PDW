<?php
require_once('../config.php');
session_start();

if (empty($_SESSION['user_id'])) {
    die('Harus login terlebih dahulu.');
}

$user_id = (int)$_SESSION['user_id'];

// Ambil praktikum yang diikuti
$sql = "SELECT mp.id, mp.nama_praktikum, mp.deskripsi, mh.tanggal_daftar
        FROM mahasiswa_praktikum mh
        JOIN mata_praktikum mp ON mp.id = mh.praktikum_id
        WHERE mh.user_id = ?
        ORDER BY mh.tanggal_daftar DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Praktikum Saya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="fw-bold text-center mb-4">Praktikum yang Diikuti</h2>
  <div class="text-center mb-4">
    <a href="courses.php" class="btn btn-outline-secondary">← Kembali ke Katalog</a>
  </div>

  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info text-center">
      <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
  <?php endif; ?>

  <?php if (!$rows): ?>
      <div class="alert alert-info text-center">Belum ada pendaftaran praktikum.</div>
  <?php else: ?>
      <div class="row g-4 justify-content-center">
      <?php foreach ($rows as $p): ?>
        <div class="col-sm-6 col-md-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
              <h5 class="fw-bold"><?= htmlspecialchars($p['nama_praktikum']) ?></h5>
              <p class="flex-grow-1"><?= nl2br(htmlspecialchars($p['deskripsi'])) ?></p>
              <p class="small text-muted">Daftar: <?= $p['tanggal_daftar'] ?></p>
              <a href="detail_praktikum.php?id=<?= $p['id'] ?>" class="btn btn-primary w-100">
                Lihat Detail &amp; Tugas
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      </div>
  <?php endif; ?>
</div>

</body>
</html>
