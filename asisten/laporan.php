<?php
session_start();
$conn = new mysqli("localhost", "root", "", "pengumpulantugas");

// Ambil semua data laporan (tanpa filter dulu)
$query = "SELECT l.*, u.nama AS nama_mahasiswa, m.nama_modul 
          FROM laporan l
          JOIN users u ON l.user_id = u.id
          JOIN modul m ON l.modul_id = m.id
           ORDER BY l.tanggal_pengumpulan DESC";

$laporan = $conn->query($query);

if (!$laporan) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Masuk</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2>Laporan Masuk Mahasiswa</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <div class="card">
        <div class="card-header bg-primary text-white">Daftar Laporan</div>
        <div class="card-body">
            <table class="table table-bordered table-striped bg-white">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Modul</th>
                        <th>File</th>
                        <th>Nilai</th>
                        <th>Feedback</th>
                        <th>Waktu Upload</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($laporan->num_rows > 0): $no = 1; ?>
                        <?php while ($row = $laporan->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['nama_mahasiswa']) ?></td>
                                <td><?= htmlspecialchars($row['nama_modul']) ?></td>
                                <td>
                                    <a href="../uploads_laporan/<?= htmlspecialchars($row['file_laporan']) ?>" target="_blank">
                                        Download
                                    </a>
                                </td>
                                <td><?= $row['nilai'] ?? '-' ?></td>
                                <td><?= nl2br(htmlspecialchars($row['feedback'])) ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($row['tanggal_pengumpulan'])) ?></td>
                                <td>
                                    <a href="nilai_laporan.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Nilai</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">Belum ada laporan masuk.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
