<?php
$conn = new mysqli("localhost", "root", "", "pengumpulantugas");

// Tambah atau Update Modul
if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $praktikum_id = $_POST['praktikum'];
    $nama_modul = $_POST['judul'];
    $deskripsi = $_POST['deskripsi'];
    $nama_file = null;

    if (!empty($_FILES['file']['name'])) {
        $nama_file = time() . "_" . basename($_FILES['file']['name']);
        $tmp_file = $_FILES['file']['tmp_name'];
        move_uploaded_file($tmp_file, "../uploads/" . $nama_file);
    }

    if ($id == "") {
        $conn->query("INSERT INTO modul (praktikum_id, nama_modul, deskripsi, file_materi, created_at) 
                      VALUES ('$praktikum_id', '$nama_modul', '$deskripsi', '$nama_file', NOW())");
    } else {
        $update_sql = "UPDATE modul SET 
                        praktikum_id='$praktikum_id', 
                        nama_modul='$nama_modul', 
                        deskripsi='$deskripsi'";
        if ($nama_file) {
            $update_sql .= ", file_materi='$nama_file'";
        }
        $update_sql .= " WHERE id = '$id'";
        $conn->query($update_sql);
    }

    header("Location: modul.php");
    exit;
}

// Hapus
if (isset($_GET['hapus'])) {
    $conn->query("DELETE FROM modul WHERE id = " . intval($_GET['hapus']));
    header("Location: modul.php");
    exit;
}

// Ambil data untuk edit
$edit = null;
if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit = $conn->query("SELECT * FROM modul WHERE id = $edit_id")->fetch_assoc();
}

// Ambil data dropdown dan tabel
$praktikum = $conn->query("SELECT * FROM mata_praktikum");
$modul = $conn->query("SELECT modul.*, mata_praktikum.nama_praktikum AS nama_praktikum 
                       FROM modul 
                       JOIN mata_praktikum ON modul.praktikum_id = mata_praktikum.id 
                       ORDER BY modul.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Modul</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background-color:#f2f2f2">
<div class="container mt-4">
    <a href="dashboard.php" class="btn btn-secondary mb-4">← Kembali ke Dashboard</a>

    <h2 class="mb-4">Manajemen Modul</h2>

    <!-- Form Tambah/Edit -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white"><?= $edit ? 'Edit Modul' : 'Tambah Modul' ?></div>
        <div class="card-body bg-white">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
                <div class="form-group">
                    <label>Mata Praktikum</label>
                    <select name="praktikum" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <?php while($p = $praktikum->fetch_assoc()): ?>
                            <option value="<?= $p['id'] ?>" <?= ($edit && $edit['praktikum_id'] == $p['id']) ? 'selected' : '' ?>>
                                <?= $p['nama_praktikum'] ?? $p['nama'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Judul Modul</label>
                    <input type="text" name="judul" class="form-control" required value="<?= $edit['nama_modul'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"><?= $edit['deskripsi'] ?? '' ?></textarea>
                </div>
                <div class="form-group">
                    <label>File Materi (PDF/DOCX)</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.docx">
                    <?php if ($edit && $edit['file_materi']): ?>
                        <small class="text-muted">File saat ini: <?= $edit['file_materi'] ?></small>
                    <?php endif; ?>
                </div>
                <button type="submit" name="simpan" class="btn btn-primary">
                    <?= $edit ? 'Update' : 'Simpan' ?>
                </button>
                <?php if ($edit): ?>
                    <a href="modul.php" class="btn btn-secondary ml-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tabel Modul -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">Daftar Modul</div>
        <div class="card-body bg-white">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Mata Praktikum</th>
                        <th>Judul Modul</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while($row = $modul->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $row['nama_praktikum'] ?></td>
                            <td><?= $row['nama_modul'] ?></td>
                            <td>
                                <?php if ($row['file_materi']): ?>
                                    <a href="../uploads/<?= $row['file_materi'] ?>" target="_blank">Download</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="modul.php?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="modul.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($modul->num_rows == 0): ?>
                        <tr><td colspan="5" class="text-center">Belum ada modul.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
