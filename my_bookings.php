<?php
session_start(); // Pastikan sesi dimulai
require "koneksi.php"; // Ini akan menggunakan file koneksi.php Anda

// Pastikan hanya user yang sudah login yang bisa mengakses halaman ini
if (!isset($_SESSION['user_login']) || $_SESSION['user_login'] !== true || $_SESSION['user_role'] !== 'user') {
    header('Location: frontend-login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pemesanan user dari database
// Query ini mengambil detail produk (nama, foto, deskripsi, harga satuan) yang terkait dengan setiap pemesanan
$queryBookings = mysqli_query($con, "
    SELECT p.*, pr.nama AS nama_produk, pr.foto AS foto_produk, pr.detail AS deskripsi_produk, pr.harga AS harga_satuan_produk
    FROM pemesanan p
    JOIN produk pr ON p.produk_id = pr.id
    WHERE p.user_id = '$user_id'
    ORDER BY p.tanggal_pemesanan DESC
");
$dataBookings = [];
while ($booking = mysqli_fetch_array($queryBookings)) {
    $dataBookings[] = $booking;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Muara Rahong Travel</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .booking-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .booking-card img {
            max-width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .booking-status {
            font-weight: bold;
        }
        .status-Pending { color: #ffc107; } /* Kuning */
        .status-Confirmed { color: #28a745; } /* Hijau */
        .status-Cancelled { color: #dc3545; } /* Merah */
        .status-Completed { color: #007bff; } /* Biru */
    </style>
</head>
<body>
    <?php require "includes/frontend-navbar.php"; ?>

    <div class="container my-5 pt-5">
        <h2 class="text-center mb-4 section-heading">Daftar Pesanan Saya</h2>
        <p class="text-center text-muted mb-5">Berikut adalah daftar pemesanan Anda.</p>

        <?php if (isset($_SESSION['booking_status'])): ?>
            <div class="alert alert-<?php echo $_SESSION['booking_status']['type']; ?> text-center" role="alert">
                <?php echo $_SESSION['booking_status']['message']; ?>
            </div>
            <?php unset($_SESSION['booking_status']); ?>
        <?php endif; ?>

        <?php if (empty($dataBookings)): ?>
            <div class="alert alert-info text-center" role="alert">
                Anda belum memiliki pesanan untuk booking. Ayo <a href="destinations.php">pilih kategori untuk di booking</a>!
            </div>
        <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <?php foreach ($dataBookings as $booking): ?>
                        <div class="booking-card d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img src="image/<?php echo htmlspecialchars($booking['foto_produk']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($booking['nama_produk']); ?>">
                            </div>
                            <div class="flex-grow-1">
                                <h5><?php echo htmlspecialchars($booking['nama_produk']); ?></h5>
                                <p class="mb-1">Tanggal Pesan: <?php echo date('d M Y H:i', strtotime($booking['tanggal_pemesanan'])); ?></p>
                                <p class="mb-1">Mulai Travel: <?php echo date('d M Y', strtotime($booking['tanggal_mulai_travel'])); ?></p>
                                <p class="mb-1">Jumlah Peserta: <?php echo htmlspecialchars($booking['jumlah_peserta']); ?></p>
                                <p class="mb-1">Total Harga: <span class="fw-bold text-success">Rp<?php echo number_format($booking['total_harga']); ?></span></p>
                                <p class="mb-0">Status: <span class="booking-status status-<?php echo htmlspecialchars($booking['status_pemesanan']); ?>"><?php echo htmlspecialchars($booking['status_pemesanan']); ?></span></p>
                                <?php if (!empty($booking['catatan_user'])): ?>
                                    <small class="text-muted">Catatan: <?php echo htmlspecialchars($booking['catatan_user']); ?></small>
                                <?php endif; ?>
                                <button type="button" class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#bookingDetailModal"
                                    data-nama-produk="<?php echo htmlspecialchars($booking['nama_produk']); ?>"
                                    data-foto-produk="image/<?php echo htmlspecialchars($booking['foto_produk']); ?>"
                                    data-tanggal-pesan="<?php echo date('d M Y H:i', strtotime($booking['tanggal_pemesanan'])); ?>"
                                    data-tanggal-mulai-travel="<?php echo date('d M Y', strtotime($booking['tanggal_mulai_travel'])); ?>"
                                    data-jumlah-peserta="<?php echo htmlspecialchars($booking['jumlah_peserta']); ?>"
                                    data-harga-satuan-produk="Rp<?php echo number_format($booking['harga_satuan_produk']); ?>"
                                    data-total-harga="Rp<?php echo number_format($booking['total_harga']); ?>"
                                    data-status-pemesanan="<?php echo htmlspecialchars($booking['status_pemesanan']); ?>"
                                    data-catatan-user="<?php echo htmlspecialchars($booking['catatan_user']); ?>"
                                    data-deskripsi-produk="<?php echo htmlspecialchars($booking['deskripsi_produk']); ?>">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingDetailModalLabel">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="detailFotoProduk" src="" class="img-fluid rounded mb-3" alt="Produk" style="max-width: 180px;">
                            <h5 id="detailNamaProduk" class="mb-3"></h5>
                        </div>
                        <div class="col-md-8">
                            <p><strong>Tanggal Pesan:</strong> <span id="detailTanggalPesan"></span></p>
                            <p><strong>Mulai Camp:</strong> <span id="detailTanggalMulaiTravel"></span></p>
                            <p><strong>Jumlah Peserta:</strong> <span id="detailJumlahPeserta"></span></p>
                            <p><strong>Harga Satuan Produk:</strong> <span id="detailHargaSatuanProduk" class="fw-bold text-muted"></span></p>
                            <p><strong>Total Harga:</strong> <span id="detailTotalHarga" class="fw-bold text-success"></span></p>
                            <p><strong>Status:</strong> <span id="detailStatusPemesanan" class="booking-status"></span></p>
                            <p><strong>Deskripsi Produk:</strong> <br><span id="detailDeskripsiProduk" class="text-muted"></span></p>
                            <p id="detailCatatanUserContainer" style="display: none;"><strong>Catatan Anda:</strong> <br><span id="detailCatatanUser" class="text-info"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var bookingDetailModal = document.getElementById('bookingDetailModal');
            bookingDetailModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                var button = event.relatedTarget;

                // Extract info from data-bs-* attributes
                var namaProduk = button.getAttribute('data-nama-produk');
                var fotoProduk = button.getAttribute('data-foto-produk');
                var tanggalPesan = button.getAttribute('data-tanggal-pesan');
                var tanggalMulaiTravel = button.getAttribute('data-tanggal-mulai-travel');
                var jumlahPeserta = button.getAttribute('data-jumlah-peserta');
                var hargaSatuanProduk = button.getAttribute('data-harga-satuan-produk');
                var totalHarga = button.getAttribute('data-total-harga');
                var statusPemesanan = button.getAttribute('data-status-pemesanan');
                var catatanUser = button.getAttribute('data-catatan-user');
                var deskripsiProduk = button.getAttribute('data-deskripsi-produk');

                // Update the modal's content.
                var modalTitle = bookingDetailModal.querySelector('.modal-title');
                var detailFotoProduk = bookingDetailModal.querySelector('#detailFotoProduk');
                var detailNamaProduk = bookingDetailModal.querySelector('#detailNamaProduk');
                var detailTanggalPesan = bookingDetailModal.querySelector('#detailTanggalPesan');
                var detailTanggalMulaiTravel = bookingDetailModal.querySelector('#detailTanggalMulaiTravel');
                var detailJumlahPeserta = bookingDetailModal.querySelector('#detailJumlahPeserta');
                var detailHargaSatuanProduk = bookingDetailModal.querySelector('#detailHargaSatuanProduk');
                var detailTotalHarga = bookingDetailModal.querySelector('#detailTotalHarga');
                var detailStatusPemesanan = bookingDetailModal.querySelector('#detailStatusPemesanan');
                var detailCatatanUserContainer = bookingDetailModal.querySelector('#detailCatatanUserContainer');
                var detailCatatanUser = bookingDetailModal.querySelector('#detailCatatanUser');
                var detailDeskripsiProduk = bookingDetailModal.querySelector('#detailDeskripsiProduk');


                modalTitle.textContent = 'Detail Pesanan: ' + namaProduk;
                detailFotoProduk.src = fotoProduk;
                detailNamaProduk.textContent = namaProduk;
                detailTanggalPesan.textContent = tanggalPesan;
                detailTanggalMulaiTravel.textContent = tanggalMulaiTravel;
                detailJumlahPeserta.textContent = jumlahPeserta;
                detailHargaSatuanProduk.textContent = hargaSatuanProduk;
                detailTotalHarga.textContent = totalHarga;
                detailStatusPemesanan.textContent = statusPemesanan;
                detailStatusPemesanan.className = 'booking-status status-' + statusPemesanan; // Apply status color class
                detailDeskripsiProduk.textContent = deskripsiProduk;


                if (catatanUser) {
                    detailCatatanUserContainer.style.display = 'block';
                    detailCatatanUser.textContent = catatanUser;
                } else {
                    detailCatatanUserContainer.style.display = 'none';
                    detailCatatanUser.textContent = '';
                }
            });
        });
    </script>
</body>
</html>