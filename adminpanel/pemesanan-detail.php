<?php
require "session.php"; // Memeriksa sesi admin
require "../koneksi.php";

$booking_id = $_GET['id'];

// Ambil data pemesanan berdasarkan ID
$queryBooking = mysqli_query($con, "
    SELECT p.*, u.username AS nama_user, u.email AS email_user, pr.nama AS nama_produk, pr.foto AS foto_produk, pr.harga AS harga_satuan_produk
    FROM pemesanan p
    JOIN user u ON p.user_id = u.id
    JOIN produk pr ON p.produk_id = pr.id
    WHERE p.id = '$booking_id'
");
$dataBooking = mysqli_fetch_array($queryBooking);

if (!$dataBooking) {
    // Jika pemesanan tidak ditemukan, redirect ke halaman pemesanan dengan pesan error
    $_SESSION['admin_pemesanan_status_message'] = [
        'type' => 'danger',
        'message' => 'Pemesanan tidak ditemukan!'
    ];
    header('Location: pemesanan.php');
    exit();
}

$status_update_message = ''; // Ini tidak akan dipakai lagi untuk ditampilkan di halaman ini

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_status'])) {
        $new_status = htmlspecialchars($_POST['status_pemesanan']);
        $valid_statuses = ['Pending', 'Confirmed', 'Cancelled', 'Completed'];

        if (in_array($new_status, $valid_statuses)) {
            $queryUpdate = mysqli_query($con, "UPDATE pemesanan SET status_pemesanan='$new_status' WHERE id='$booking_id'");

            if ($queryUpdate) {
                // *** Perubahan di sini ***
                // Simpan pesan sukses ke session
                $_SESSION['admin_pemesanan_status_message'] = [
                    'type' => 'success',
                    'message' => 'Status pemesanan berhasil diperbarui!'
                ];
                // Lakukan redirect ke halaman pemesanan.php
                header('Location: pemesanan.php');
                exit(); // Penting: Hentikan eksekusi skrip setelah redirect
            } else {
                // Simpan pesan error ke session jika update gagal
                $_SESSION['admin_pemesanan_status_message'] = [
                    'type' => 'danger',
                    'message' => 'Gagal memperbarui status: ' . mysqli_error($con)
                ];
                // Lakukan redirect ke halaman pemesanan.php meskipun gagal
                header('Location: pemesanan.php');
                exit();
            }
        } else {
            // Simpan pesan peringatan ke session jika status tidak valid
            $_SESSION['admin_pemesanan_status_message'] = [
                'type' => 'warning',
                'message' => 'Status tidak valid!'
            ];
            // Lakukan redirect ke halaman pemesanan.php
            header('Location: pemesanan.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - Admin Muara Rahong</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .booking-info p {
            margin-bottom: 0.5rem;
        }
        .status-Pending { color: #ffc107; font-weight: bold; }
        .status-Confirmed { color: #28a745; font-weight: bold; }
        .status-Cancelled { color: #dc3545; font-weight: bold; }
        .status-Completed { color: #007bff; font-weight: bold; } /* Misalnya biru untuk Completed */
    </style>
</head>
<body>
    <?php require "navbar.php"; ?>

    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="pemesanan.php" class="text-decoration-none">Pemesanan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pemesanan</li>
            </ol>
        </nav>

        <h2>Detail Pemesanan #<?php echo htmlspecialchars($dataBooking['id']); ?></h2>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Informasi Pemesanan</h4>
                        <div class="booking-info">
                            <p><strong>Produk:</strong> <?php echo htmlspecialchars($dataBooking['nama_produk']); ?></p>
                            <p><strong>Pelanggan:</strong> <?php echo htmlspecialchars($dataBooking['nama_user']); ?> (<?php echo htmlspecialchars($dataBooking['email_user']); ?>)</p>
                            <p><strong>Tanggal Pemesanan:</strong> <?php echo date('d F Y H:i', strtotime($dataBooking['tanggal_pemesanan'])); ?></p>
                            <p><strong>Tanggal Mulai Travel:</strong> <?php echo date('d F Y', strtotime($dataBooking['tanggal_mulai_travel'])); ?></p>
                            <p><strong>Jumlah Peserta:</strong> <?php echo htmlspecialchars($dataBooking['jumlah_peserta']); ?></p>
                            <p><strong>Harga Satuan:</strong> Rp<?php echo number_format($dataBooking['harga_satuan_produk']); ?></p>
                            <p><strong>Total Harga:</strong> <span class="fw-bold text-success">Rp<?php echo number_format($dataBooking['total_harga']); ?></span></p>
                            <p><strong>Status Saat Ini:</strong> <span class="booking-status status-<?php echo htmlspecialchars($dataBooking['status_pemesanan']); ?>"><?php echo htmlspecialchars($dataBooking['status_pemesanan']); ?></span></p>
                            <?php if (!empty($dataBooking['catatan_user'])): ?>
                                <p><strong>Catatan Pelanggan:</strong> <?php echo nl2br(htmlspecialchars($dataBooking['catatan_user'])); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($dataBooking['bukti_transfer'])): ?>
                                <p><strong>Bukti Transfer:</strong> <a href="../image/<?php echo htmlspecialchars($dataBooking['bukti_transfer']); ?>" target="_blank">Lihat Bukti</a></p>
                                <img src="../image/<?php echo htmlspecialchars($dataBooking['bukti_transfer']); ?>" alt="Bukti Transfer" class="img-fluid mt-2" style="max-width: 300px;">
                            <?php else: ?>
                                <p><strong>Bukti Transfer:</strong> Belum ada</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Ubah Status Pemesanan</h4>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="status_pemesanan" class="form-label">Ubah Status</label>
                                <select class="form-select" id="status_pemesanan" name="status_pemesanan" required>
                                    <option value="Pending" <?php echo ($dataBooking['status_pemesanan'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Confirmed" <?php echo ($dataBooking['status_pemesanan'] == 'Confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="Cancelled" <?php echo ($dataBooking['status_pemesanan'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    <option value="Completed" <?php echo ($dataBooking['status_pemesanan'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                                </select>
                            </div>
                            <button type="submit" name="update_status" class="btn btn-primary w-100">Simpan Perubahan Status</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>