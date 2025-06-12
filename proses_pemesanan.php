<?php
session_start(); // Pastikan sesi dimulai
require "koneksi.php";

// Pastikan hanya user yang sudah login yang bisa mengakses file ini
if (!isset($_SESSION['user_login']) || $_SESSION['user_login'] !== true || $_SESSION['user_role'] !== 'user') {
    $_SESSION['booking_status'] = [
        'type' => 'danger',
        'message' => 'Anda harus login untuk melakukan pemesanan.'
    ];
    header('Location: frontend-login.php'); // Redirect ke halaman login jika belum login
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $produk_id = htmlspecialchars($_POST['produk_id']);
    $harga_produk = htmlspecialchars($_POST['harga_produk']);
    $tanggal_mulai_travel = htmlspecialchars($_POST['tanggal_mulai_travel']);
    $jumlah_peserta = htmlspecialchars($_POST['jumlah_peserta']);
    $catatan_user = htmlspecialchars($_POST['catatan_user']);

    // Validasi input
    if (empty($produk_id) || empty($harga_produk) || empty($tanggal_mulai_travel) || empty($jumlah_peserta) || $jumlah_peserta <= 0) {
        $_SESSION['booking_status'] = [
            'type' => 'danger',
            'message' => 'Semua kolom wajib diisi dan jumlah peserta harus lebih dari 0.'
        ];
        header('Location: destinations-detail.php?p=' . $produk_id); // Kembali ke detail destinasi
        exit();
    }

    // Hitung total harga
    $total_harga = $harga_produk * $jumlah_peserta;

    // Masukkan data pemesanan ke tabel `pemesanan`
    $queryInsert = mysqli_query($con, "INSERT INTO pemesanan (user_id, produk_id, tanggal_mulai_travel, jumlah_peserta, total_harga, catatan_user) VALUES ('$user_id', '$produk_id', '$tanggal_mulai_travel', '$jumlah_peserta', '$total_harga', '$catatan_user')");

    if ($queryInsert) {
        $_SESSION['booking_status'] = [
            'type' => 'success',
            'message' => 'Pemesanan berhasil diajukan! Menunggu konfirmasi admin.'
        ];
        header('Location: my_bookings.php'); // Redirect ke halaman daftar pesanan user
        exit();
    } else {
        $_SESSION['booking_status'] = [
            'type' => 'danger',
            'message' => 'Gagal melakukan pemesanan: ' . mysqli_error($con)
        ];
        header('Location: destinations-detail.php?p=' . $produk_id); // Kembali ke detail destinasi dengan error
        exit();
    }
} else {
    // Jika diakses tidak melalui POST, redirect ke halaman utama
    header('Location: index.php');
    exit();
}
?>