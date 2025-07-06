<?php
session_start();
require_once('../config.php');

// UBAH VALIDASI LOGIN → hanya cek user_id dan role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'mahasiswa') {
    echo "Anda belum login sebagai mahasiswa.";
    exit();
}

$user_id = $_SESSION['user_id'];
$praktikum_id = $_POST['praktikum_id'] ?? null;

// DEBUG sementara
/*
echo "<pre>";
print_r($_SESSION);
print_r($_POST);
echo "</pre>";
exit();
*/

if ($praktikum_id && is_numeric($praktikum_id)) {
    // Cek apakah sudah pernah mendaftar
    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM mahasiswa_praktikum WHERE user_id = ? AND praktikum_id = ?");
    $stmt_check->bind_param("ii", $user_id, $praktikum_id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        $_SESSION['message'] = "Anda sudah terdaftar di praktikum ini.";
    } else {
        // Tambahkan pendaftaran baru
        $stmt_insert = $conn->prepare("INSERT INTO mahasiswa_praktikum (user_id, praktikum_id, tanggal_daftar) VALUES (?, ?, NOW())");
        $stmt_insert->bind_param("ii", $user_id, $praktikum_id);

        if ($stmt_insert->execute()) {
            $_SESSION['message'] = "Berhasil mendaftar ke praktikum.";
        } else {
            $_SESSION['message'] = "Gagal mendaftar: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    }
} else {
    $_SESSION['message'] = "ID praktikum tidak valid.";
}

$conn->close();
header("Location: my_courses.php");
exit();
