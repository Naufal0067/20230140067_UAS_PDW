<?php
require_once('../config.php');
session_start();

// Contoh data kursus (bisa dari database sebenarnya)
$courses = [
    ['id' => 1, 'title' => 'Pemrograman Web Dasar',
     'desc'=> 'Praktikum dasar untuk belajar HTML, CSS, JavaScript, dan PHP.'],
    ['id' => 2, 'title' => 'Struktur Data',
     'desc'=> 'Praktikum untuk memahami berbagai struktur data dan algoritma.']
];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Katalog Mata Praktikum</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="text-center mb-4 fw-bold">Katalog Mata Praktikum</h2>

  <div class="text-center mb-4">
    <a href="../index.php" class="btn btn-outline-secondary">← Kembali ke Dashboard</a>
  </div>

  <div class="row justify-content-center g-4">

  <?php foreach ($courses as $c): ?>
    <div class="col-sm-6 col-md-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title fw-bold"><?= htmlspecialchars($c['title']) ?></h5>
          <p class="card-text flex-grow-1"><?= htmlspecialchars($c['desc']) ?></p>

          <!-- Form pendaftaran -->
          <form action="daftar.php" method="post">
            <input type="hidden" name="praktikum_id" value="<?= $c['id'] ?>">

            <?php if (empty($_SESSION['user_id'])): ?>
              <div class="mb-2">
                <input type="text" class="form-control form-control-sm mb-1"
                       name="nama" placeholder="Nama lengkap" required>
                <input type="text" class="form-control form-control-sm"
                       name="nim"  placeholder="NIM" required>
              </div>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary w-100">
              Daftar
            </button>
          </form>

        </div>
      </div>
    </div>
  <?php endforeach; ?>

  </div>
</div>
</body>
</html>
