<?php
// session_start();
    require "session.php";
    require "../koneksi.php";

    // --- Start Pagination Logic ---
    $items_per_page = 4; // Jumlah kategori per halaman

    // Dapatkan halaman saat ini dari URL, default ke 1
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($current_page < 1) {
        $current_page = 1;
    }

    // Hitung offset
    $offset = ($current_page - 1) * $items_per_page;

    // Query untuk menghitung total jumlah kategori
    $queryTotalKategori = mysqli_query($con, "SELECT COUNT(*) AS total FROM kategori");
    $dataTotalKategori = mysqli_fetch_assoc($queryTotalKategori);
    $total_items = $dataTotalKategori['total'];

    // Hitung total halaman
    $total_pages = ceil($total_items / $items_per_page);

    // Pastikan halaman yang diminta tidak melebihi total halaman
    if ($current_page > $total_pages && $total_pages > 0) {
        $current_page = $total_pages;
        $offset = ($current_page - 1) * $items_per_page; // Perbarui offset jika halaman diubah
    } elseif ($total_pages == 0) {
        $current_page = 1; // Jika tidak ada data, tetap di halaman 1
        $offset = 0;
    }

    // Modifikasi query untuk mengambil data kategori dengan LIMIT dan OFFSET
    $querykategori = mysqli_query($con, "SELECT * FROM kategori ORDER BY id ASC LIMIT $items_per_page OFFSET $offset");
    $jumlahkategori = mysqli_num_rows($querykategori); // Jumlah data yang diambil untuk halaman ini
    // --- End Pagination Logic ---

    $nama_kategori_baru = "";
    if (isset($_POST['tambah_kategori'])) {
        $nama_kategori_baru = htmlspecialchars($_POST['kategori']);

        if (empty($nama_kategori_baru)) {
            echo "<div class='alert alert-danger mt-3' role='alert'>Nama kategori tidak boleh kosong!</div>";
        } else {
            // Gunakan prepared statement untuk mencegah SQL Injection
            $stmtCheck = mysqli_prepare($con, "SELECT nama FROM kategori WHERE nama = ?");
            mysqli_stmt_bind_param($stmtCheck, "s", $nama_kategori_baru);
            mysqli_stmt_execute($stmtCheck);
            mysqli_stmt_store_result($stmtCheck); // Simpan hasil untuk mysqli_stmt_num_rows
            $jumlahDataKategoriBaru = mysqli_stmt_num_rows($stmtCheck);
            mysqli_stmt_close($stmtCheck);

            if ($jumlahDataKategoriBaru > 0) {
                echo "<div class='alert alert-warning mt-3' role='alert'>Kategori sudah ada!</div>";
            } else {
                $stmtInsert = mysqli_prepare($con, "INSERT INTO kategori (nama) VALUES (?)");
                mysqli_stmt_bind_param($stmtInsert, "s", $nama_kategori_baru);
                if (mysqli_stmt_execute($stmtInsert)) {
                    echo "<div class='alert alert-success mt-3' role='alert'>Kategori berhasil ditambahkan!</div>";
                    echo "<meta http-equiv='refresh' content='2; url=kategori.php'>";
                    exit();
                } else {
                    echo "<div class='alert alert-danger mt-3' role='alert'>Gagal menambahkan kategori: " . mysqli_error($con) . "</div>";
                }
                mysqli_stmt_close($stmtInsert);
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori | Admin Muara Rahong</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php require "navbar.php"; ?>

    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Kategori</li>
            </ol>
        </nav>

        <div class="my-5 col-12 col-md-6">
            <h3>Tambah Kategori</h3>
            <form action="" method="post">
                <div class="input-group">
                    <input type="text" id="kategori" name="kategori" class="form-control" placeholder="Nama Kategori" value="<?php echo htmlspecialchars($nama_kategori_baru); ?>" required>
                    <button class="btn btn-primary" type="submit" name="tambah_kategori">Tambah</button>
                </div>
            </form>
        </div>

        <div class="mt-3">
            <h2>List Kategori</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($jumlahkategori == 0){
                        ?>
                            <tr>
                                <td colspan="3" class="text-center">Data Kategori Tidak Tersedia</td>
                            </tr>
                        <?php
                            } else {
                                $nomor_awal = ($current_page - 1) * $items_per_page + 1;
                                while($data=mysqli_fetch_array($querykategori)){
                        ?>
                            <tr>
                                <td><?php echo $nomor_awal++; ?></td>
                                <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                <td>
                                    <a href="kategori-detail.php?p=<?php echo htmlspecialchars($data['id']); ?>"
                                    class="btn btn-info"><i class="fas fa-search"></i></a>
                                </td>
                            </tr>
                        <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php if ($current_page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            </div>
    </div>

    <script src="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>