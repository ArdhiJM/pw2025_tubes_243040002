<?php
require "session.php"; // Memeriksa sesi admin
require "../koneksi.php"; // Koneksi ke database

// Query untuk mengambil semua pesan kontak, diurutkan dari yang terbaru
$queryPesanKontak = mysqli_query($con, "SELECT * FROM kontak_pesan ORDER BY tanggal_kirim DESC");
$jumlahPesanKontak = mysqli_num_rows($queryPesanKontak);

// Inisialisasi variabel untuk pesan status (jika ada)
$status_message = '';

// Logika untuk menghapus pesan
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = htmlspecialchars($_GET['id']);
    $queryDelete = mysqli_query($con, "DELETE FROM kontak_pesan WHERE id = '$id_to_delete'");

    if ($queryDelete) {
        $status_message = '<div class="alert alert-success alert-dismissible fade show" role="alert">Pesan berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        // Redirect untuk membersihkan URL dari parameter GET
        echo '<meta http-equiv="refresh" content="2; url=pesan_kontak.php">';
    } else {
        $status_message = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Gagal menghapus pesan: ' . mysqli_error($con) . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Kontak - Admin Muara Rahong</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css"> <style>
        .table-responsive {
            margin-top: 20px;
        }
        .message-content {
            max-height: 100px; /* Batasi tinggi untuk pratinjau */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .message-full {
            white-space: normal;
        }
    </style>
</head>
<body>
    <?php require "navbar.php"; ?>

    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pesan Kontak</li>
            </ol>
        </nav>

        <h2 class="mb-4">Manajemen Pesan Kontak</h2>

        <?php echo $status_message; // Menampilkan pesan status ?>

        <div class="table-responsive">
            <?php if ($jumlahPesanKontak == 0): ?>
                <div class="alert alert-info text-center" role="alert">
                    Tidak ada pesan kontak yang masuk.
                </div>
            <?php else: ?>
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No.</th>
                            <th>Nama Pengirim</th>
                            <th>Email</th>
                            <th>Subjek</th>
                            <th>Pesan</th>
                            <th>Tanggal Kirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($pesan = mysqli_fetch_array($queryPesanKontak)) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($pesan['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($pesan['email']); ?></td>
                                <td><?php echo htmlspecialchars($pesan['subjek']); ?></td>
                                <td>
                                    <div class="message-content" id="message-<?php echo $pesan['id']; ?>">
                                        <?php echo nl2br(htmlspecialchars($pesan['pesan'])); ?>
                                    </div>
                                    <?php if (strlen($pesan['pesan']) > 100): // Jika pesan terlalu panjang, tambahkan tombol "Lihat Selengkapnya" ?>
                                        <button class="btn btn-sm btn-link p-0" type="button" onclick="toggleMessage('message-<?php echo $pesan['id']; ?>', this)">Lihat Selengkapnya</button>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d M Y H:i', strtotime($pesan['tanggal_kirim'])); ?></td>
                                <td>
                                    <a href="pesan_kontak.php?action=delete&id=<?php echo htmlspecialchars($pesan['id']); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script src="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script>
        // Fungsi untuk menampilkan/menyembunyikan pesan lengkap
        function toggleMessage(id, button) {
            const messageDiv = document.getElementById(id);
            if (messageDiv.classList.contains('message-content')) {
                messageDiv.classList.remove('message-content');
                messageDiv.classList.add('message-full');
                button.textContent = 'Sembunyikan';
            } else {
                messageDiv.classList.remove('message-full');
                messageDiv.classList.add('message-content');
                button.textContent = 'Lihat Selengkapnya';
            }
        }
    </script>
</body>
</html>