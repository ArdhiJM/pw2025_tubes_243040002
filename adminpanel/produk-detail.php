<?php
    require "session.php";
    require "../koneksi.php";

    $id = $_GET['p'];

    $query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id = b.id WHERE a.id='$id'");
    $data = mysqli_fetch_array($query);

    if (!$data) {
        echo "<div class='alert alert-danger mt-3' role='alert'>Produk tidak ditemukan!</div>";
        echo "<meta http-equiv='refresh' content='2; url=produk.php'>";
        exit();
    }

    $querykategori = mysqli_query($con, "SELECT * FROM kategori WHERE id!='$data[kategori_id]'");

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString ='';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $foto_lama = $data['foto'];

    if(isset($_POST['editBtn'])){
        $nama = htmlspecialchars($_POST['nama']);
        $kategori = htmlspecialchars($_POST['kategori']);
        $harga = htmlspecialchars($_POST['harga']);
        $detail = htmlspecialchars($_POST['detail']);
        $ketersediaan_stok = htmlspecialchars($_POST['ketersediaan_stok']);

        $nama_file = basename($_FILES["foto"]["name"]);
        $target_dir = "../image/"; // Path relatif ke folder image dari adminpanel
        $target_file = $target_dir . $nama_file;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $image_size = $_FILES["foto"]["size"];
        $random_name = generateRandomString(20);
        $nama_file_baru = $random_name . "." . $imageFileType;

        $berhasilUpload = false;

        if ($nama_file != '') { // Jika ada file foto baru diupload
            if ($image_size > 2000000) {
                echo "<div class='alert alert-danger mt-3' role='alert'>File Foto Tidak Boleh Lebih dari 2MB!</div>";
            } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo "<div class='alert alert-danger mt-3' role='alert'>Tipe File Harus JPG, JPEG, PNG, atau GIF!</div>";
            } else {
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $nama_file_baru)) {
                    $berhasilUpload = true;
                } else {
                    echo "<div class='alert alert-danger mt-3' role='alert'>Gagal mengupload foto baru!</div>";
                }
            }
        } else { // Jika tidak ada foto baru diupload
            $nama_file_baru = $foto_lama; // Tetap gunakan foto lama
            $berhasilUpload = true; // Anggap berhasil karena tidak ada upload baru
        }

        if ($berhasilUpload) {
            if ($foto_lama != "default.png" && $nama_file_baru != $foto_lama && file_exists($target_dir . $foto_lama)) {
                unlink($target_dir . $foto_lama); // Hapus foto lama jika diganti dan bukan default.png
            }

            $queryUpdate = mysqli_query($con, "UPDATE produk SET kategori_id='$kategori', nama='$nama', harga='$harga', foto='$nama_file_baru', detail='$detail', ketersediaan_stok='$ketersediaan_stok' WHERE id='$id'");

            if ($queryUpdate) {
                ?>
                <div class="alert alert-success mt-3" role="alert">
                    Produk Berhasil Diperbarui!
                </div>
                <meta http-equiv="refresh" content="2; url=produk.php">
                <?php
                exit();
            } else {
                echo "<div class='alert alert-danger mt-3' role='alert'>Gagal memperbarui produk: " . mysqli_error($con) . "</div>";
            }
        }
    }

    if(isset($_POST['deleteBtn'])){
        // Ambil nama file foto produk sebelum dihapus dari database
        $queryGetFoto = mysqli_query($con, "SELECT foto FROM produk WHERE id='$id'");
        $dataFoto = mysqli_fetch_array($queryGetFoto);
        $foto_to_delete = $dataFoto['foto'];

        // Periksa apakah foto yang akan dihapus bukan 'default.png' dan file-nya ada
        if ($foto_to_delete != "default.png" && file_exists("../image/" . $foto_to_delete)) {
            unlink("../image/" . $foto_to_delete); // Hapus file gambar dari server
        }

        // Query DELETE untuk menghapus produk dari database
        $queryDelete = mysqli_query($con, "DELETE FROM produk WHERE id='$id'");

        if($queryDelete){
            ?>
                <div class="alert alert-success mt-3" role="alert">
                    Produk Berhasil Dihapus!
                </div>
                <meta http-equiv="refresh" content="2; url=produk.php">
            <?php
            exit();
        } else {
            ?>
                <div class="alert alert-danger mt-3" role="alert">
                    Gagal Menghapus Produk! Error: <?php echo mysqli_error($con); ?>
                </div>
            <?php
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk | Admin Muara Rahong</title>
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
                <li class="breadcrumb-item"><a href="produk.php" class="text-decoration-none">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
            </ol>
        </nav>

        <h2>Detail Produk</h2>

        <div class="col-12 col-md-8 mb-5">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select name="kategori" id="kategori" class="form-select" required>
                        <option value="<?php echo htmlspecialchars($data['kategori_id']); ?>"><?php echo htmlspecialchars($data['nama_kategori']); ?></option>
                        <?php while($dataKategori = mysqli_fetch_array($querykategori)) { ?>
                            <option value="<?php echo htmlspecialchars($dataKategori['id']); ?>">
                                <?php echo htmlspecialchars($dataKategori['nama']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" value="<?php echo htmlspecialchars($data['harga']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="current_foto" class="form-label d-block">Foto Saat Ini</label>
                    <?php if ($data['foto'] == '' || $data['foto'] == 'default.png') : ?>
                        <img src="../image/default.png" alt="Default Image" width="100px">
                    <?php else : ?>
                        <img src="../image/<?php echo htmlspecialchars($data['foto']); ?>" alt="<?php echo htmlspecialchars($data['nama']); ?>" width="100px">
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Ganti Foto (Opsional)</label>
                    <input type="file" name="foto" id="foto" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="detail" class="form-label">Detail</label>
                    <textarea name="detail" id="detail" cols="30" rows="10" class="form-control"><?php echo htmlspecialchars($data['detail']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="ketersediaan_stok" class="form-label">Ketersediaan Stok</label>
                    <select name="ketersediaan_stok" id="ketersediaan_stok" class="form-select" required>
                        <option value="Tersedia" <?php echo ($data['ketersediaan_stok'] == 'Tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                        <option value="Terbatas" <?php echo ($data['ketersediaan_stok'] == 'Terbatas') ? 'selected' : ''; ?>>Terbatas</option>
                        <option value="Tidak Tersedia" <?php echo ($data['ketersediaan_stok'] == 'Tidak Tersedia') ? 'selected' : ''; ?>>Tidak Tersedia</option>
                    </select>
                </div>
                <div class="mt-3 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" name="editBtn">Update</button>
                    <button type="submit" class="btn btn-danger" name="deleteBtn" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>