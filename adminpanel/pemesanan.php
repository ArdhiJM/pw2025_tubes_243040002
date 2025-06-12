<?php
require "session.php"; // Memeriksa sesi admin
require "../koneksi.php";

$queryPemesanan = mysqli_query($con, "
    SELECT p.*, u.username AS nama_user, pr.nama AS nama_produk
    FROM pemesanan p
    JOIN user u ON p.user_id = u.id
    JOIN produk pr ON p.produk_id = pr.id
    ORDER BY p.tanggal_pemesanan DESC
");
$jumlahPemesanan = mysqli_num_rows($queryPemesanan);

// Inisialisasi pesan
$status_message = '';

// *** Perubahan di sini ***
// Cek apakah ada pesan dari session
if (isset($_SESSION['admin_pemesanan_status_message'])) {
    $msg_type = $_SESSION['admin_pemesanan_status_message']['type'];
    $msg_content = $_SESSION['admin_pemesanan_status_message']['message'];
    $status_message = "<div class='alert alert-$msg_type mt-3' role='alert'>$msg_content</div>";
    unset($_SESSION['admin_pemesanan_status_message']); // Hapus pesan dari session setelah ditampilkan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pemesanan - Admin Muara Rahong</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
        <link rel="stylesheet" href="../css/style.css">

    <style>
        .table-responsive {
            margin-top: 20px;
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
                <li class="breadcrumb-item active" aria-current="page">Pemesanan</li>
            </ol>
        </nav>

        <h2>Manajemen Pemesanan</h2>

        <?php echo $status_message; // Tampilkan pesan status ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ID Pemesanan</th>
                        <th>User</th>
                        <th>Produk</th>
                        <th>Tgl. Pemesanan</th>
                        <th>Tgl. Mulai Travel</th>
                        <th>Jumlah Peserta</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($jumlahPemesanan == 0) {
                    ?>
                        <tr>
                            <td colspan="10" class="text-center">Belum ada data pemesanan.</td>
                        </tr>
                    <?php
                    } else {
                        $counter = 1;
                        while ($data = mysqli_fetch_array($queryPemesanan)) {
                    ?>
                            <tr>
                                <td><?php echo $counter++; ?></td>
                                <td><?php echo htmlspecialchars($data['id']); ?></td>
                                <td><?php echo htmlspecialchars($data['nama_user']); ?></td>
                                <td><?php echo htmlspecialchars($data['nama_produk']); ?></td>
                                <td><?php echo date('d M Y H:i', strtotime($data['tanggal_pemesanan'])); ?></td>
                                <td><?php echo date('d M Y', strtotime($data['tanggal_mulai_travel'])); ?></td>
                                <td><?php echo htmlspecialchars($data['jumlah_peserta']); ?></td>
                                <td>Rp<?php echo number_format($data['total_harga']); ?></td>
                                <td><span class="status-<?php echo htmlspecialchars($data['status_pemesanan']); ?>"><?php echo htmlspecialchars($data['status_pemesanan']); ?></span></td>
                                <td>
                                    <a href="pemesanan-detail.php?id=<?php echo htmlspecialchars($data['id']); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>