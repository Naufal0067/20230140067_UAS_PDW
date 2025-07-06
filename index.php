<?php
// Pastikan untuk selalu memulai sesi di awal setiap halaman yang membutuhkan sesi
session_start();

// Sertakan file konfigurasi database
require_once 'config.php';

// Cek apakah pengguna sudah login
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Pengguna sudah login
    // Arahkan berdasarkan peran (role) pengguna
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] === 'mahasiswa') {
            header("Location: mahasiswa/dashboard.php"); // Atau halaman utama mahasiswa
            exit();
        } elseif ($_SESSION['role'] === 'asisten') {
            header("Location: asisten/dashboard.php"); // Atau halaman utama asisten
            exit();
        } else {
            // Peran tidak dikenal, arahkan ke login atau halaman default
            header("Location: login.php");
            exit();
        }
    } else {
        // Jika role tidak terset, mungkin ada masalah, arahkan ke login
        header("Location: login.php");
        exit();
    }
} else {
    // Pengguna belum login, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
?>