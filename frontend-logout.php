<?php
session_start(); // Pastikan sesi dimulai

// Periksa apakah sesi 'user_login' ada dan bernilai true, serta role-nya 'user'
// Ini memastikan kita hanya menghapus sesi pengguna biasa, bukan admin
if (isset($_SESSION['user_login']) && $_SESSION['user_login'] === true && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user') {
    // Hapus semua variabel sesi yang terkait dengan user frontend
    unset($_SESSION['user_id']);
    unset($_SESSION['user_username']);
    unset($_SESSION['user_login']);
    unset($_SESSION['user_role']);

    // Redirect ke halaman login pengguna atau halaman utama
    header('location: frontend-login.php'); // Atau 'index.php'
    exit(); // Pastikan script berhenti setelah redirect
} else {
    // Jika tidak ada sesi user yang valid, redirect saja ke halaman utama
    // atau halaman login. Ini mencegah akses langsung ke logout tanpa login.
    header('location: index.php'); // Atau 'frontend-login.php'
    exit();
}
?>