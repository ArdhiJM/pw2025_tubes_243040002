<?php
    require "session.php";
    require "../koneksi.php";

    $query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id = b.id");
    $jumlahproduk = mysqli_num_rows($query);

    $querykategori = mysqli_query($con, "SELECT * FROM kategori");

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString ='';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    if (isset($_POST['simpan'])) {
        $nama = htmlspecialchars($_POST['nama']);
        $kategori = htmlspecialchars($_POST['kategori']);
        $harga = htmlspecialchars($_POST['harga']);
        $detail = htmlspecialchars($_POST['detail']);
        $ketersediaan_stok = htmlspecialchars($_POST['ketersediaan_stok']);

        $target_dir = "../image/"; // Folder tempat menyimpan gambar (relatif dari produk.php)
        $nama_file = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $nama_file;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $image_size = $_FILES["foto"]["size"];
        $random_name = generateRandomString(20);
        $nama_file_baru = $random_name . "." . $imageFileType;

        if ($nama == '' || $kategori == '' || $harga == '') {
            echo "<div class='alert alert-danger mt-3' role='alert'>Nama, Kategori, dan Harga Wajib Diisi!</div>";
        } else {
            if ($nama_file != '') {
                if ($image_size > 2000000) { // 2 MB
                    echo "<div class='alert alert-danger mt-3' role='alert'>File Foto Tidak Boleh Lebih dari 2MB!</div>";
                } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    echo "<div class='alert alert-danger mt-3' role='alert'>Tipe File Harus JPG, JPEG, PNG, atau GIF!</div>";
                } else {
                    // Pindahkan file yang diupload ke folder target
                    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $nama_file_baru)) {
                        // File berhasil diupload, lanjutkan dengan query INSERT
                        $querySimpan = mysqli_query($con, "INSERT INTO produk (kategori_id, nama, harga, foto, detail, ketersediaan_stok) VALUES ('$kategori', '$nama', '$harga', '$nama_file_baru', '$detail', '$ketersediaan_stok')");

                        if ($querySimpan) {
                            ?>
                            <div class="alert alert-success mt-3" role="alert">Produk Berhasil Disimpan!</div>
                            <meta http-equiv="refresh" content="2; url=produk.php">
                            <?php
                            exit();
                        } else {
                            echo "<div class='alert alert-danger mt-3' role='alert'>Gagal menyimpan produk: " . mysqli_error($con) . "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger mt-3' role='alert'>Gagal mengupload foto!</div>";
                    }
                }
            } else {
                // Jika tidak ada foto diupload, gunakan 'default.png'
                $querySimpan = mysqli_query($con, "INSERT INTO produk (kategori_id, nama, harga, foto, detail, ketersediaan_stok) VALUES ('$kategori', '$nama', '$harga', 'default.png', '$detail', '$ketersediaan_stok')");

                if ($querySimpan) {
                    ?>
                    <div class="alert alert-success mt-3" role="alert">Produk Berhasil Disimpan!</div>
                    <meta http-equiv="refresh" content="2; url=produk.php">
                    <?php
                    exit();
                } else {
                    echo "<div class='alert alert-danger mt-3' role='alert'>Gagal menyimpan produk: " . mysqli_error($con) . "</div>";
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk | Admin Muara Rahong</title>
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
                <li class="breadcrumb-item active" aria-current="page">Produk</li>
            </ol>
        </nav>

        <div class="my-3 col-12 col-md-8">
            <h3>Tambah Produk</h3>
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama" class="form-label">Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="mt-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select" required>
                        <option value="">Pilih Satu</option>
                        <?php while($dataKategori = mysqli_fetch_array($querykategori)) { ?>
                            <option value="<?php echo htmlspecialchars($dataKategori['id']); ?>">
                                <?php echo htmlspecialchars($dataKategori['nama']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mt-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" required>
                </div>
                <div class="mt-3">
                    <label for="foto" class="form-label">Foto Produk</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>
                <div class="mt-3">
                    <label for="detail" class="form-label">Detail</label>
                    <textarea name="detail" id="detail" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <div class="mt-3">
                    <label for="ketersediaan_stok" class="form-label">Ketersediaan Stok</label>
                    <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-select" required>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Terbatas">Terbatas</option>
                        <option value="Tidak Tersedia">Tidak Tersedia</option>
                    </select>
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
                </div>
            </form>
        </div>

        <div class="mt-3">
            <h2>Daftar Produk</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($jumlahproduk == 0){
                        ?>
                            <tr>
                                <td colspan="6" class="text-center">Data Produk Tidak Tersedia</td>
                            </tr>
                        <?php
                            } else {
                                $jumlah = 1;
                                while($data=mysqli_fetch_array($query)) {
                        ?>
                            <tr>
                                <td><?php echo $jumlah; ?></td>
                                <td><?php echo htmlspecialchars($data['nama'])?></td>
                                <td><?php echo htmlspecialchars($data['nama_kategori'])?></td>
                                <td>Rp<?php echo number_format($data['harga'])?></td>
                                <td><?php echo htmlspecialchars($data['ketersediaan_stok'])?></td>
                                <td>
                                    <a href="produk-detail.php?p=<?php echo htmlspecialchars($data['id']); ?>"
                                    class="btn btn-info"><i class="fas fa-search"></i></a>
                                </td>
                            </tr>
                        <?php
                                $jumlah++;
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>