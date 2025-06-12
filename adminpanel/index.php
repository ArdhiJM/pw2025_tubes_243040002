<?php
    require "session.php";
    require "../koneksi.php";

    $queryKategori = mysqli_query($con, "SELECT * FROM kategori");
    $jumlahKategori = mysqli_num_rows($queryKategori);

    $queryProduk = mysqli_query($con, "SELECT * FROM produk");
    $jumlahkProduk = mysqli_num_rows($queryProduk);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Admin | Muara Rahong</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
    <?php require "navbar.php"; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Dashboard Admin</h2>
        <div class="row">
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="summary-kategori p-3">
                    <div class="row">
                        <div class="col-6">
                            <i class="fas fa-boxes fa-7x text-black-50 mt-2 ms-2"></i>
                        </div>
                        <div class="col-6 text-white text-end">
                            <h3 class="fs-2">Kategori</h3>
                            <p class="fs-4"><?php echo htmlspecialchars($jumlahKategori); ?> Kategori</p>
                            <p><a href="kategori.php" class="text-white text-decoration-none">Lihat Detail</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <div class="summary-produk p-3">
                    <div class="row">
                        <div class="col-6">
                            <i class="fas fa-box fa-7x text-black-50 mt-2 ms-2"></i>
                        </div>
                        <div class="col-6 text-white text-end">
                            <h3 class="fs-2">Produk</h3>
                            <p class="fs-4"><?php echo htmlspecialchars($jumlahkProduk); ?> Produk</p>
                            <p><a href="produk.php" class="text-white text-decoration-none">Lihat Detail</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>