<?php
    // Pastikan session sudah dimulai sebelum mengakses variabel session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require "koneksi.php";

    $queryProduk = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id = b.id ORDER BY b.nama ASC, a.id DESC LIMIT 5");
    $dataProduk = [];
    while($produk = mysqli_fetch_array($queryProduk)) {
        $dataProduk[] = $produk;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muara Rahong Hils - Muara Rahong Hills - Wisata Glamping Camping Outbound Rafting Flying Fox Paintball, Off Road Pangalengan Bandung Selatan</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require "includes/frontend-navbar.php"; ?>

    <section class="hero-section">
        <div class="hero-content">
            <h1>Muara Rahong Hills</h1>
            <p>Pengalaman Glamping Tak Terlupakan di Pangalengan, Pangalengan</p>
            <a href="kategori.php" class="btn btn-light btn-lg">Jelajahi Semua Kategori</a>
        </div>
    </section>

    <section id="tentang-kami-section" class="about-section text-center py-5">
        <div class="container">
            <h2 class="section-heading">Tentang Muara Rahong</h2>
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="" alt="Muara Rahong View" class="img-fluid rounded-3 shadow">
                </div>
                <div class="col-md-6">
                    <p class="lead">Muara Rahong Hills adalah destinasi glamping menakjubkan yang terletak di tepi sungai yang jernih, dikelilingi oleh hutan pinus yang tenang di Pangalengan, Kabupaten Bandung.</p>
                    <p>Kami berkomitmen untuk menyediakan pengalaman perjalanan yang tak terlupakan, memadukan kenyamanan modern dengan keindahan alam yang asli. Nikmati berbagai aktivitas seru mulai dari berkemah mewah, rafting, hingga outbond.</p>
                    <a href="contact.php" class="btn btn-primary mt-3">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>

    <section class="featured-produk py-5">
        <div class="container">
            <h2 class="text-center section-heading mb-3">Produk Unggulan Kami</h2>
            <p class="text-center lead mb-5">
                Jelajahi pilihan paket glamping dan aktivitas terbaik yang kami tawarkan.
                Setiap paket dirancang untuk memberikan pengalaman tak terlupakan.
            </p>

            <div class="row">
                <?php if (empty($dataProduk)): ?>
                    <div class="col-12 text-center">
                        <div class="alert alert-info" role="alert">
                            Belum ada destinasi yang tersedia.
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($dataProduk as $produk): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card destination-card h-100">
                                <img src="image/<?php echo htmlspecialchars($produk['foto']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produk['nama']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($produk['nama']); ?></h5>
                                    <p class="card-text text-muted">Kategori: <?php echo htmlspecialchars($produk['nama_kategori']); ?></p>
                                    <p class="card-text fw-bold text-primary">Harga: Rp<?php echo number_format($produk['harga']); ?></p>
                                    <p class="card-text">
                                        <?php
                                            $deskripsiSingkat = substr(strip_tags($produk['detail']), 0, 100);
                                            echo htmlspecialchars($deskripsiSingkat);
                                            if (strlen(strip_tags($produk['detail'])) > 100) {
                                                echo '...';
                                            }
                                        ?>
                                    </p>
                                    <a href="kategori-detail.php?p=<?php echo htmlspecialchars($produk['id']); ?>" class="btn btn-primary">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a href="kategori.php" class="btn btn-outline-dark btn-lg">Lihat Semua Kategori</a>
            </div>
        </section>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>