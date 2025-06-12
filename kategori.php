<?php
    // session_start(); // untuk ada fitur seperti "favorit" yang terkait dengan user login.
    require "koneksi.php";

    $nama_produk_cari = "";
    $kategori_filter = "";

    if (isset($_GET['keyword'])) {
        $nama_produk_cari = htmlspecialchars($_GET['keyword']);
    }

    if (isset($_GET['kategori'])) {
        $kategori_filter = htmlspecialchars($_GET['kategori']);
    }

    $whereClause = "";
    if (!empty($nama_produk_cari)) {
        $whereClause .= " AND a.nama LIKE '%$nama_produk_cari%'";
    }
    if (!empty($kategori_filter) && $kategori_filter != "all") {
        // Temukan ID kategori berdasarkan nama
        $queryKategoriId = mysqli_query($con, "SELECT id FROM kategori WHERE nama = '$kategori_filter'");
        $dataKategoriId = mysqli_fetch_array($queryKategoriId);
        if ($dataKategoriId) {
            $kategori_id_filter = $dataKategoriId['id'];
            $whereClause .= " AND a.kategori_id = '$kategori_id_filter'";
        }
    }

    // MODIFY THIS LINE: Order by category ID
    $queryProduk = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id = b.id WHERE 1=1 $whereClause ORDER BY b.id ASC, a.nama ASC");
    $dataProduk = [];
    while($produk = mysqli_fetch_array($queryProduk)) {
        $dataProduk[] = $produk;
    }

    $queryKategori = mysqli_query($con, "SELECT * FROM kategori ORDER BY nama ASC");
    $dataKategori = [];
    while($kategori = mysqli_fetch_array($queryKategori)) {
        $dataKategori[] = $kategori;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Destinasi - Muara Rahong Travel</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

<style>
    /* Gaya tambahan untuk meniru tampilan Muara Rahong Hills Harga */
    .destination-card {
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }
    .destination-card .card-img-top {
        /* Hapus height tetap atau jadikan auto */
        height: auto; /* Membiarkan tinggi disesuaikan dengan rasio aspek gambar */
        width: 100%; /* Memastikan gambar mengisi lebar yang tersedia */
        object-fit: contain; /* Menampilkan seluruh gambar tanpa terpotong */
        /* Jika gambar memiliki rasio aspek yang sangat berbeda, mungkin akan ada ruang kosong di atas/bawah atau kiri/kanan jika Anda mempertahankan height */
        /* background-color: #f8f9fa; /* Opsional: warna latar belakang untuk ruang kosong jika object-fit: contain digunakan */
    }
    .destination-card .card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
    }
    .destination-card .card-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .destination-card .package-price {
        font-size: 1.25rem;
        font-weight: 600;
        color: #007bff; /* Bootstrap primary blue */
        margin-bottom: 1rem;
    }
    .destination-card .package-features ul {
        list-style: none;
        padding: 0;
        margin-bottom: 1.5rem;
        flex-grow: 1; /* Agar daftar fitur memenuhi ruang yang tersedia */
    }
    .destination-card .package-features ul li {
        font-size: 1rem;
        margin-bottom: 0.5rem;
        color: #555;
    }
    .destination-card .package-features ul li i {
        color: #28a745; /* Green checkmark */
        margin-right: 0.5rem;
    }
    .destination-card .btn-primary {
        width: 100%;
        font-size: 1.1rem;
        padding: 0.75rem 1rem;
    }
</style>

</head>
<body>
    <?php require "includes/frontend-navbar.php"; ?>

    <div class="container my-5 pt-5">
        <h2 class="text-center section-heading mb-4">Jelajahi Semua Destinasi Kami</h2>

        <form action="" method="get" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari destinasi..." value="<?php echo htmlspecialchars($nama_produk_cari); ?>">
                </div>
                <div class="col-md-4">
                    <select name="kategori" class="form-select">
                        <option value="all">Semua Kategori</option>
                        <?php foreach ($dataKategori as $kategori): ?>
                            <option value="<?php echo htmlspecialchars($kategori['nama']); ?>" <?php echo ($kategori_filter == $kategori['nama']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kategori['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Cari & Filter</button>
                </div>
            </div>
        </form>

        <div class="row">
            <?php if (empty($dataProduk)): ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info" role="alert">
                        Tidak ada destinasi yang ditemukan.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($dataProduk as $produk): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card destination-card h-100">
                            <img src="image/<?php echo htmlspecialchars($produk['foto']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produk['nama']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($produk['nama']); ?></h5>
                                <p class="package-price">Harga: Rp<?php echo number_format($produk['harga']); ?></p>

                                <div class="package-features">
                                    <h6>Fitur Termasuk:</h6>
                                    <ul>
                                        <?php
                                            // Memecah detail menjadi baris dan menampilkan hingga 3-4 fitur pertama
                                            $features = array_filter(explode("\n", strip_tags($produk['detail'])));
                                            $displayedFeatures = 0;
                                            foreach ($features as $feature) {
                                                $feature = trim($feature);
                                                if (!empty($feature) && $displayedFeatures < 4) { // Batasi hingga 4 fitur
                                                    echo '<li><i class="fas fa-check"></i> ' . htmlspecialchars($feature) . '</li>';
                                                    $displayedFeatures++;
                                                }
                                            }
                                            if ($displayedFeatures === 0) {
                                                // Jika tidak ada fitur yang terpecah, tampilkan deskripsi singkat sebagai fitur
                                                echo '<li><i class="fas fa-check"></i> ' . htmlspecialchars(substr(strip_tags($produk['detail']), 0, 80)) . (strlen(strip_tags($produk['detail'])) > 80 ? '...' : '') . '</li>';
                                            }
                                        ?>
                                        <li><i class="fas fa-check"></i> Pengalaman tak terlupakan</li>
                                        <li><i class="fas fa-check"></i> Pemandu Profesional</li>
                                        <li><i class="fas fa-check"></i> Asuransi Perjalanan</li>
                                    </ul>
                                </div>
                                <a href="kategori-detail.php?p=<?php echo htmlspecialchars($produk['id']); ?>" class="btn btn-primary mt-auto">Lihat Detail Paket</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>