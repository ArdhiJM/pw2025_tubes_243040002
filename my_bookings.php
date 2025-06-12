<?php
session_start(); // Pastikan sesi dimulai
require "koneksi.php";

// Pastikan hanya user yang sudah login yang bisa mengakses halaman ini
if (!isset($_SESSION['user_login']) || $_SESSION['user_login'] !== true || $_SESSION['user_role'] !== 'user') {
    header('Location: frontend-login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pemesanan user dari database
$queryBookings = mysqli_query($con, "
    SELECT p.*, pr.nama AS nama_produk, pr.foto AS foto_produk
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
        <p class="text-center text-muted mb-5">Berikut adalah daftar pemesanan destinasi Anda.</p>

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
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>