<?php
session_start();
$conn = new mysqli("localhost", "root", "", "pengumpulantugas");

// Cek login
$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['role'] ?? null;

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$praktikum = $conn->query("SELECT * FROM mata_praktikum WHERE id = $id")->fetch_assoc();
$modul = $conn->query("SELECT * FROM modul WHERE praktikum_id = $id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $praktikum['nama_praktikum'] ?? 'Detail Praktikum' ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #007bff;
            color: #fff;
        }
        .card {
            background-color: #f8f9fa;
            color: #000;
        }
        .form-control {
            border-radius: 0.4rem;
        }
        .card-title i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <!-- Tombol kembali -->
    <a href="dashboard.php" class="btn btn-light mb-4">← Kembali ke Dashboard</a>

    <h2 class="font-weight-bold"><?= htmlspecialchars($praktikum['nama_praktikum'] ?? 'Tidak ditemukan') ?></h2>
    <p><?= nl2br(htmlspecialchars($praktikum['deskripsi'] ?? '')) ?></p>

    <h5 class="mt-4 mb-3">📚 Daftar Modul & Materi</h5>

    <div class="form-group">
        <input type="text" class="form-control" id="searchInput" placeholder="Cari judul modul...">
    </div>

    <div class="row" id="modulContainer">
        <?php if ($modul && $modul->num_rows > 0): ?>
            <?php while ($m = $modul->fetch_assoc()): ?>
                <div class="col-md-6 mb-4 modul-item">
                    <div class="card h-100 shadow-sm border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-primary modul-title">
                                <i class="fas fa-file-alt"></i> <?= htmlspecialchars($m['nama_modul']) ?>
                            </h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($m['deskripsi'])) ?></p>

                            <?php if ($m['file_materi']): ?>
                                <a href="../uploads/<?= $m['file_materi'] ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                                    📥 Download Materi
                                </a>
                            <?php endif; ?>

                            <!-- Form upload laporan (khusus mahasiswa) -->
                            <?php if ($user_role === 'mahasiswa'): ?>
                                <form action="../upload_laporan.php" method="POST" enctype="multipart/form-data" class="mt-3">
                                    <input type="hidden" name="modul_id" value="<?= $m['id'] ?>">
                                    <input type="hidden" name="praktikum_id" value="<?= $id ?>">
                                    <div class="form-group">
                                        <input type="file" name="file_laporan" class="form-control-file" required>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">📤 Upload Laporan</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer text-muted small">
                            Diunggah: <?= date('d M Y, H:i', strtotime($m['created_at'])) ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-light text-dark text-center">Belum ada modul untuk praktikum ini.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#searchInput').on('keyup', function () {
        var value = $(this).val().toLowerCase();
        $('#modulContainer .modul-item').filter(function () {
            $(this).toggle($(this).find('.modul-title').text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>
</body>
</html>
