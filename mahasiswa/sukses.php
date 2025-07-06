<?php
// ─────────────────────────────────────────────────────────
//  mahasiswa/sukses.php
//  Halaman konfirmasi pendaftaran berhasil
// ─────────────────────────────────────────────────────────
session_start();

// (Opsional) tampilkan nama atau kursus terakhir—
// …jika sebelumnya disimpan di $_SESSION:
$nama        = $_SESSION['nama']         ?? '';
$lastCourse  = $_SESSION['last_course']  ?? '';  // simpan sendiri di daftar.php jika mau
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Pendaftaran Berhasil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card shadow-sm border-success">
        <div class="card-body text-center">
          
          <h3 class="card-title text-success mb-3">🎉 Pendaftaran Berhasil!</h3>
          
          <?php if ($nama || $lastCourse): ?>
            <p>Terima kasih <strong><?= htmlspecialchars($nama) ?></strong>, 
               pendaftaran untuk <strong><?= htmlspecialchars($lastCourse) ?></strong> telah kami terima.</p>
          <?php else: ?>
            <p>Terima kasih, data pendaftaran kamu sudah tersimpan di sistem.</p>
          <?php endif; ?>
          
          <a href="courses.php" class="btn btn-primary mt-3">Kembali ke Katalog</a>
        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>
