<?php
session_start();
require_once('../config.php');

// Autentikasi admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak.");
}

// Tambah praktikum
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    if ($nama) {
        $stmt = $conn->prepare("INSERT INTO mata_praktikum (nama_praktikum, deskripsi) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $deskripsi);
        $stmt->execute();
        $stmt->close();
    }
}

// Edit praktikum
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $stmt = $conn->prepare("UPDATE mata_praktikum SET nama_praktikum = ?, deskripsi = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nama, $deskripsi, $id);
    $stmt->execute();
    $stmt->close();
}

// Hapus praktikum
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM mata_praktikum WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Ambil semua praktikum
$praktikum = $conn->query("SELECT * FROM mata_praktikum ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kelola Mata Praktikum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <h3 class="mb-4">Kelola Mata Praktikum</h3>

    <!-- Form Tambah -->
    <form method="post" class="mb-4">
        <h5>Tambah Praktikum</h5>
        <div class="mb-2">
            <input type="text" name="nama" required placeholder="Nama Praktikum" class="form-control">
        </div>
        <div class="mb-2">
            <textarea name="deskripsi" placeholder="Deskripsi (opsional)" class="form-control"></textarea>
        </div>
        <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
    </form>

    <!-- Tabel Praktikum -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($praktikum as $p): ?>
            <tr>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <td><input type="text" name="nama" value="<?= htmlspecialchars($p['nama_praktikum']) ?>" class="form-control"></td>
                    <td><input type="text" name="deskripsi" value="<?= htmlspecialchars($p['deskripsi']) ?>" class="form-control"></td>
                    <td>
                        <button type="submit" name="edit" class="btn btn-sm btn-warning">Simpan</button>
                        <a href="?hapus=<?= $p['id'] ?>" onclick="return confirm('Hapus praktikum ini?')" class="btn btn-sm btn-danger">Hapus</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
