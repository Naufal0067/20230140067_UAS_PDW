<?php
session_start();
$conn = new mysqli("localhost", "root", "", "pengumpulantugas");

// Validasi session mahasiswa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    die("Akses ditolak. Harap login sebagai mahasiswa.");
}

$modul_id = intval($_POST['modul_id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($modul_id <= 0 || !isset($_FILES['file_laporan'])) {
    die("Data tidak lengkap.");
}

// Validasi file
$nama_file = $_FILES['file_laporan']['name'];
$tmp = $_FILES['file_laporan']['tmp_name'];
$ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
$allowed = ['pdf', 'docx', 'doc'];

if (!in_array($ext, $allowed)) {
    die("Format file tidak diizinkan. Gunakan PDF, DOC, atau DOCX.");
}

// Siapkan folder penyimpanan
$folder = "uploads_laporan/";
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

// Buat nama unik
$nama_baru = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $nama_file);
$tujuan = $folder . $nama_baru;

// Pindahkan dan simpan ke database
if (move_uploaded_file($tmp, $tujuan)) {
    // Cek apakah sudah pernah upload untuk modul ini
    $cek = $conn->query("SELECT id FROM laporan WHERE modul_id = $modul_id AND user_id = $user_id");
    if ($cek->num_rows > 0) {
        // Update jika sudah pernah upload
        $row = $cek->fetch_assoc();
        $conn->query("UPDATE laporan SET file_laporan = '$nama_baru', created_at = NOW(), nilai = NULL, feedback = NULL WHERE id = " . $row['id']);
    } else {
        // Insert baru
        $stmt = $conn->prepare("INSERT INTO laporan (modul_id, user_id, file_laporan) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Query gagal: " . $conn->error);
        }
        $stmt->bind_param("iis", $modul_id, $user_id, $nama_baru);
        $stmt->execute();
        $stmt->close();
    }

    // 🔁 Ambil praktikum_id untuk redirect
    $getPraktikum = $conn->query("SELECT praktikum_id FROM modul WHERE id = $modul_id");
    $praktikum = $getPraktikum->fetch_assoc();
    $praktikum_id = $praktikum['praktikum_id'];

    header("Location: mahasiswa/detail_praktikum.php?id=$praktikum_id&upload=success");
    exit;
} else {
    die("Gagal mengunggah file.");
}
?>
