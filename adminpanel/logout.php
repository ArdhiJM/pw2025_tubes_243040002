<?php
    // Memulai sesi PHP
    session_start();
    // Menghapus semua variabel sesi
    session_unset();
    // Menghancurkan sesi yang aktif
    session_destroy();
    // Mengarahkan pengguna kembali ke halaman login.php
    header('location: ../index.php');
    exit(); // Pastikan script berhenti setelah redirect
?>