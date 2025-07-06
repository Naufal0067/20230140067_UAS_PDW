<?php
$conn = new mysqli("localhost", "root", "", "pengumpulantugas");

// Tambah Pengguna
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $conn->query("INSERT INTO pengguna (nama, email, password, role) 
                  VALUES ('$nama', '$email', '$password', '$role')");
    header("Location: kelola_pengguna.php");
    exit;
}

// Hapus Pengguna
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM pengguna WHERE id = $id");
    header("Location: kelola_pengguna.php");
    exit;
}

// Ambil Semua Pengguna
$pengguna = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #dee2e6; /* Abu-abu terang */
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-3">Kelola Akun Pengguna</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-4">← Kembali ke Dashboard</a>

    <!-- Form Tambah -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">Tambah Pengguna</div>
        <div class="card-body bg-white">
            <form method="POST">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="asisten">Asisten</option>
                    </select>
                </div>
                <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <!-- Tabel Pengguna -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">Daftar Pengguna</div>
        <div class="card-body bg-white">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($pengguna && $pengguna->num_rows > 0): $no = 1; ?>
                        <?php while ($p = $pengguna->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($p['nama']) ?></td>
                                <td><?= htmlspecialchars($p['email']) ?></td>
                                <td><?= $p['role'] ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($p['created_at'])) ?></td>
                                <td>
                                    <a href="edit_pengguna.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="kelola_pengguna.php?hapus=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Belum ada pengguna</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
