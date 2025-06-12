<?php
session_start(); // Pastikan ini ada di baris pertama
require "koneksi.php";

$id = $_GET['p']; // Mengambil ID produk dari parameter GET

// Ambil data produk beserta nama kategori
$queryProduk = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id = b.id WHERE a.id='$id'");
$data = mysqli_fetch_array($queryProduk);

// Jika produk tidak ditemukan, redirect atau tampilkan pesan error
if (!$data) {
    echo "<div class='alert alert-danger text-center mt-5' role='alert'>Destinasi tidak ditemukan!</div>";
    echo "<meta http-equiv='refresh' content='2; url=kategori.php'>";
    exit();
}

$harga_per_orang = $data['harga'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Destinasi - <?php echo htmlspecialchars($data['nama']); ?></title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .destination-detail-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-booking {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .form-booking .form-control {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php require "includes/frontend-navbar.php"; ?>

    <div class="container my-5 pt-5">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <img src="image/<?php echo htmlspecialchars($data['foto']); ?>" class="destination-detail-img mb-4" alt="<?php echo htmlspecialchars($data['nama']); ?>">
                <div class="destination-detail-info">
                    <h2><?php echo htmlspecialchars($data['nama']); ?></h2>
                    <h5>Kategori: <?php echo htmlspecialchars($data['nama_kategori']); ?></h5>
                    <p class="fs-5 fw-bold text-primary">Harga: Rp<?php echo number_format($data['harga']); ?> / orang</p>
                    <p><?php echo nl2br(htmlspecialchars($data['detail'])); ?></p>

                    <?php if (!empty($data['ketersediaan_stok'])): // Menggunakan ketersediaan_stok sebagai deskripsi tambahan sementara ?>
                        <h4 class="mt-4">Ketersediaan/Informasi Penting</h4>
                        <p><?php echo nl2br(htmlspecialchars($data['ketersediaan_stok'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-4 shadow-sm mb-4">
                    <h4 class="mb-3">Informasi Cepat</h4>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2 text-primary"></i>Lokasi: Muara Rahong</li>
                        <li><i class="fas fa-tags me-2 text-primary"></i>Kategori: <?php echo htmlspecialchars($data['nama_kategori']); ?></li>
                        <li><i class="fas fa-dollar-sign me-2 text-primary"></i>Harga Mulai: Rp<?php echo number_format($data['harga']); ?></li>
                        <li><i class="fas fa-check-circle me-2 text-success"></i>Status: Tersedia</li>
                    </ul>
                </div>

                <?php if (isset($_SESSION['user_login']) && $_SESSION['user_login'] === true && $_SESSION['user_role'] === 'user'): ?>
                    <div class="card p-4 shadow-sm form-booking">
                        <h4 class="mb-3">Form Pemesanan</h4>
                        <?php if (isset($_SESSION['booking_status'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['booking_status']['type']; ?>" role="alert">
                                <?php echo $_SESSION['booking_status']['message']; ?>
                            </div>
                            <?php unset($_SESSION['booking_status']); // Hapus pesan setelah ditampilkan ?>
                        <?php endif; ?>
                        <form action="proses_pemesanan.php" method="POST">
                            <input type="hidden" name="produk_id" value="<?php echo htmlspecialchars($data['id']); ?>">
                            <input type="hidden" name="harga_produk" value="<?php echo htmlspecialchars($data['harga']); ?>">
                            <div class="mb-3">
                                <label for="tanggal_mulai_travel" class="form-label">Tanggal Mulai Camp</label>
                                <input type="date" class="form-control" id="tanggal_mulai_travel" name="tanggal_mulai_travel" required min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="jumlah_peserta" class="form-label">Jumlah Tenda</label>
                                <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="1" value="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="catatan_user" class="form-label">Catatan Tambahan (Opsional)</label>
                                <textarea class="form-control" id="catatan_user" name="catatan_user" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Total Harga:</label>
                                <p class="fs-4 fw-bold text-success" id="totalHargaDisplay">Rp<?php echo number_format($data['harga']); ?></p>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100">Pesan Sekarang</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="card p-4 shadow-sm text-center">
                        <p class="text-muted mb-3">Login untuk melakukan pemesanan destinasi ini.</p>
                        <a href="frontend-login.php" class="btn btn-primary btn-lg w-100">Login Sekarang</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        // JavaScript untuk menghitung total harga secara dinamis
        const hargaPerOrang = <?php echo json_encode($harga_per_orang); ?>;
        const jumlahPesertaInput = document.getElementById('jumlah_peserta');
        const totalHargaDisplay = document.getElementById('totalHargaDisplay');

        function updateTotalHarga() {
            const jumlah = parseInt(jumlahPesertaInput.value);
            if (!isNaN(jumlah) && jumlah > 0) {
                const total = hargaPerOrang * jumlah;
                totalHargaDisplay.textContent = 'Rp' + total.toLocaleString('id-ID');
            } else {
                totalHargaDisplay.textContent = 'Rp0';
            }
        }

        jumlahPesertaInput.addEventListener('input', updateTotalHarga);
        // Pastikan total harga diperbarui saat halaman dimuat (jika ada nilai default)
        document.addEventListener('DOMContentLoaded', updateTotalHarga);
    </script>
</body>
</html>