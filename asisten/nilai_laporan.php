<?php
session_start();
$conn = new mysqli("localhost", "root", "", "pengumpulantugas");

// Cek apakah parameter ID diberikan
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("ID laporan tidak valid.");
}

// Ambil data laporan
$laporan = $conn->query("SELECT l.*, u.nama AS nama_mahasiswa, m.nama_modul 
                         FROM laporan l
                         JOIN users u ON l.user_id = u.id
                         JOIN modul m ON l.modul_id = m.id
                         WHERE l.id = $id")->fetch_assoc();

if (!$laporan) {
    die("Laporan tidak ditemukan.");
}

// Proses form penilaian
if (isset($_POST['simpan'])) {
    $nilai = floatval($_POST['nilai']);
    $feedback = trim($_POST['feedback']);

    $stmt = $conn->prepare("UPDATE laporan SET nilai = ?, feedback = ? WHERE id = ?");
    $stmt->bind_param("dsi", $nilai, $feedback, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: laporan.php?sukses=1");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nilai Laporan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <a href="laporan.php" class="btn btn-secondary mb-3">← Kembali ke Laporan Masuk</a>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong>Penilaian Laporan</strong>
        </div>
        <div class="card-body">
            <p><strong>Mahasiswa:</strong> <?= htmlspecialchars($laporan['nama_mahasiswa']) ?></p>
            <p><strong>Modul:</strong> <?= htmlspecialchars($laporan['nama_modul']) ?></p>
            <p><strong>File:</strong> <a href="../uploads_laporan/<?= $laporan['file_laporan'] ?>" target="_blank">Download</a></p>

            <form method="POST">
                <div class="form-group">
                    <label>Nilai (0 - 100)</label>
                    <input type="number" name="nilai" class="form-control" min="0" max="100" step="0.1" required value="<?= $laporan['nilai'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Feedback</label>
                    <textarea name="feedback" class="form-control" rows="4"><?= htmlspecialchars($laporan['feedback']) ?></textarea>
                </div>
                <button type="submit" name="simpan" class="btn btn-success">💾 Simpan Penilaian</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
