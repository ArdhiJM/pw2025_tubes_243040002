<?php
    require "session.php";
    require "../koneksi.php";

    $id = $_GET['p'];

    $query = mysqli_query($con, "SELECT * FROM kategori WHERE id='$id'");
    $data = mysqli_fetch_array($query);

    if (!$data) {
        echo "<div class='alert alert-danger mt-3' role='alert'>Kategori tidak ditemukan!</div>";
        echo "<meta http-equiv='refresh' content='2; url=kategori.php'>";
        exit();
    }

    if(isset($_POST['editBtn'])){
        $kategori = htmlspecialchars($_POST['kategori']);

        if(empty($kategori)){
            ?>
                <div class="alert alert-danger mt-3" role="alert">
                    Nama Kategori tidak boleh kosong!
                </div>
            <?php
        }
        else{
            $queryCekNama = mysqli_query($con, "SELECT * FROM kategori WHERE nama='$kategori' AND id!='$id'");
            $jumlahDataKategori = mysqli_num_rows($queryCekNama);

            if($jumlahDataKategori > 0){
                ?>
                    <div class="alert alert-warning mt-3" role="alert">
                        Kategori sudah ada!
                    </div>
                <?php
            }
            else{
                $queryUpdate = mysqli_query($con, "UPDATE kategori SET nama='$kategori' WHERE id='$id'");
                if($queryUpdate){
                    ?>
                        <div class="alert alert-success mt-3" role="alert">
                            Kategori berhasil Diperbarui!
                        </div>
                        <meta http-equiv="refresh" content="2; url=kategori.php">
                    <?php
                    exit(); // Penting
                }
                else{
                    echo mysqli_error($con);
                }
            }
        }
    }

    if(isset($_POST['deleteBtn'])){
        // Check if category is used in products
        $queryCheckProduk = mysqli_query($con, "SELECT * FROM produk WHERE kategori_id='$id'");
        $dataCount = mysqli_num_rows($queryCheckProduk);

        if($dataCount > 0){
            ?>
                <div class="alert alert-warning mt-3" role="alert">
                    Kategori tidak bisa Dihapus karena sudah digunakan di Produk !!!
                </div>
            <?php
            exit(); // Penting: Hentikan eksekusi
        }

        $queryDelete = mysqli_query($con, "DELETE FROM kategori WHERE id='$id'");

        if($queryDelete){
            ?>
                <div class="alert alert-success mt-3" role="alert">
                    Kategori berhasil Dihapus!!!
                </div>
                <meta http-equiv="refresh" content="2; url=kategori.php">
            <?php
            exit(); // Penting
        }
        else{
            echo mysqli_error($con);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kategori | Admin Muara Rahong</title>
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
                <li class="breadcrumb-item"><a href="kategori.php" class="text-decoration-none">Kategori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Kategori</li>
            </ol>
        </nav>

        <h2>Detail Kategori</h2>

        <div class="col-12 col-md-6">
            <form action="" method="post">
                <div>
                    <label for="kategori" class="form-label">Nama Kategori</label>
                    <input type="text" name="kategori" id="kategori" class="form-control" value="<?php echo htmlspecialchars($data['nama']); ?>">
                </div>
                <div class="mt-3 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary" name="editBtn">Update</button>
                    <button type="submit" class="btn btn-danger" name="deleteBtn" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>