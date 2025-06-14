<?php
require "session.php"; // Memeriksa sesi admin
require "../koneksi.php";

$id_post = null;
$data_post = [
    'title' => '',
    'slug' => '',
    'content' => '',
    'foto_artikel' => '', // Pastikan kolom ini ada di database
    'author' => '',
];
$form_title = "Tambah Postingan Blog Baru";
$is_edit_mode = false;

// Fungsi untuk membuat slug dari judul
function generateSlug($string) {
    $string = strtolower($string); // Ubah ke huruf kecil
    $string = preg_replace('/[^a-z0-9\-]/', '', $string); // Hapus karakter non-alphanumeric kecuali strip
    $string = preg_replace('/[\s_]+/', '-', $string); // Ganti spasi/underscore dengan strip
    $string = preg_replace('/-+/', '-', $string); // Hapus duplikat strip
    return trim($string, '-'); // Hapus strip di awal/akhir
}

// Fungsi bantu untuk generate random string (sama seperti di produk.php)
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Cek apakah mode edit
if (isset($_GET['id'])) {
    $id_post = htmlspecialchars($_GET['id']);
    $query = mysqli_query($con, "SELECT * FROM blog_posts WHERE id='$id_post'");
    $data_post_fetched = mysqli_fetch_array($query);

    if ($data_post_fetched) {
        $data_post = $data_post_fetched; // Timpa dengan data dari database
        $form_title = "Edit Postingan Blog";
        $is_edit_mode = true;
    } else {
        $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Postingan tidak ditemukan!'];
        header('Location: blog-posts.php');
        exit();
    }
}

$status_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan tombol 'simpanBtn' yang ditekan
    if (isset($_POST['simpanBtn'])) {
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $author = htmlspecialchars($_POST['author']);
        $slug = generateSlug($title); // Generate slug dari judul

        $foto_artikel = $data_post['foto_artikel']; // Default ke foto lama jika edit atau kosong jika tambah baru

        $uploadOk = 1; // Diasumsikan upload OK

        // Penanganan upload foto
        // Cek apakah ada file baru yang diunggah dan tidak ada error
        if (isset($_FILES["foto_artikel"]) && $_FILES["foto_artikel"]["error"] === UPLOAD_ERR_OK) {
            $target_dir = "../image/"; // Folder tempat menyimpan gambar (relatif dari adminpanel/)
            $nama_file_upload = basename($_FILES["foto_artikel"]["name"]);
            $imageFileType = strtolower(pathinfo($nama_file_upload, PATHINFO_EXTENSION));

            // Cek ukuran file
            if ($_FILES["foto_artikel"]["size"] > 5000000) { // 5mB
                $status_message = '<div class="alert alert-danger" role="alert">Maaf, ukuran file terlalu besar. (Maks 500KB)</div>';
                $uploadOk = 0;
            }

            // Izinkan format file tertentu
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowed_ext)) {
                $status_message = '<div class="alert alert-danger" role="alert">Maaf, hanya file JPG, JPEG, PNG, & GIF yang diizinkan.</div>';
                $uploadOk = 0;
            }

            // Jika semua cek lolos, proses upload
            if ($uploadOk == 1) {
                // Generate nama file unik untuk menghindari tabrakan
                $random_name = generateRandomString(15);
                $foto_artikel = $random_name . "." . $imageFileType;
                $target_file_path = $target_dir . $foto_artikel;

                if (move_uploaded_file($_FILES["foto_artikel"]["tmp_name"], $target_file_path)) {
                    // Hapus foto lama jika ini mode edit dan ada foto baru
                    if ($is_edit_mode && !empty($data_post['foto_artikel']) && file_exists("../image/" . $data_post['foto_artikel'])) {
                        unlink("../image/" . $data_post['foto_artikel']);
                    }
                } else {
                    $status_message = '<div class="alert alert-danger" role="alert">Maaf, ada kesalahan saat mengunggah file gambar.</div>';
                    $uploadOk = 0; // Set uploadOk menjadi 0 jika gagal move
                }
            }
        } elseif (isset($_FILES["foto_artikel"]) && $_FILES["foto_artikel"]["error"] === UPLOAD_ERR_NO_FILE) {
            // Tidak ada file baru yang diunggah. Jika edit, pertahankan foto lama.
            // Jika tambah baru, foto_artikel tetap null/kosong (sesuai default inisialisasi)
            if ($is_edit_mode) {
                $foto_artikel = $data_post['foto_artikel'];
            } else {
                $foto_artikel = null;
            }
        } else if (isset($_FILES["foto_artikel"]) && $_FILES["foto_artikel"]["error"] !== UPLOAD_ERR_NO_FILE) {
            // Ada error upload selain 'tidak ada file'
            $status_message = '<div class="alert alert-danger" role="alert">Terjadi kesalahan upload: Error Code ' . $_FILES["foto_artikel"]["error"] . '.</div>';
            $uploadOk = 0;
        }

        // Lanjutkan proses simpan/update ke database hanya jika uploadOK (atau tidak ada file baru yang diunggah)
        if ($uploadOk) {
            if ($is_edit_mode) {
                // Cek apakah slug sudah ada dan bukan untuk postingan ini sendiri
                $queryCekSlug = mysqli_query($con, "SELECT slug FROM blog_posts WHERE slug = '$slug' AND id != '$id_post'");
                if (mysqli_num_rows($queryCekSlug) > 0) {
                    $status_message = '<div class="alert alert-warning" role="alert">Judul sudah ada (slug duplikat). Silakan gunakan judul lain.</div>';
                } else {
                    $stmt = mysqli_prepare($con, "UPDATE blog_posts SET title = ?, slug = ?, content = ?, foto_artikel = ?, author = ? WHERE id = ?");
                    mysqli_stmt_bind_param($stmt, "sssssi", $title, $slug, $content, $foto_artikel, $author, $id_post);

                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['status_message'] = ['type' => 'success', 'message' => 'Postingan berhasil diperbarui!'];
                        header('Location: blog-posts.php');
                        exit();
                    } else {
                        $status_message = '<div class="alert alert-danger" role="alert">Gagal memperbarui postingan: ' . mysqli_error($con) . '</div>';
                    }
                    mysqli_stmt_close($stmt);
                }
            } else { // Mode tambah baru
                // Cek apakah slug sudah ada (untuk tambah baru)
                $queryCekSlug = mysqli_query($con, "SELECT slug FROM blog_posts WHERE slug = '$slug'");
                if (mysqli_num_rows($queryCekSlug) > 0) {
                    $status_message = '<div class="alert alert-warning" role="alert">Judul sudah ada (slug duplikat). Silakan gunakan judul lain.</div>';
                } else {
                    $stmt = mysqli_prepare($con, "INSERT INTO blog_posts (title, slug, content, foto_artikel, author) VALUES (?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmt, "sssss", $title, $slug, $content, $foto_artikel, $author);

                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['status_message'] = ['type' => 'success', 'message' => 'Postingan berhasil ditambahkan!'];
                        header('Location: blog-posts.php');
                        exit();
                    } else {
                        $status_message = '<div class="alert alert-danger" role="alert">Gagal menambahkan postingan: ' . mysqli_error($con) . '</div>';
                    }
                    mysqli_stmt_close($stmt);
                }
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
    <title><?php echo htmlspecialchars($form_title); ?> - Admin Muara Rahong</title>
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
                <li class="breadcrumb-item"><a href="blog-posts.php" class="text-decoration-none">Blog Posts</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($form_title); ?></li>
            </ol>
        </nav>

        <h2 class="mb-4"><?php echo htmlspecialchars($form_title); ?></h2>

        <?php echo $status_message; ?>

        <div class="col-12 col-md-8">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Postingan</label>
                    <input type="text" class="form-control" id="title" name="title" required
                        value="<?php echo htmlspecialchars($data_post['title']); ?>">
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Konten Postingan</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($data_post['content']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="author" class="form-label">Penulis</label>
                    <input type="text" class="form-control" id="author" name="author" required
                            value="<?php echo htmlspecialchars($data_post['author']); ?>">
                </div>
                <div class="mb-3">
                    <label for="foto_artikel" class="form-label">Foto Artikel</label>
                    <?php if (!empty($data_post['foto_artikel'])): ?>
                        <div class="mb-2">
                            <img src="../image/<?php echo htmlspecialchars($data_post['foto_artikel']); ?>" alt="Foto Artikel Lama" style="max-width: 200px; border-radius: 5px;">
                            <small class="text-muted d-block mt-1">Foto saat ini</small>
                        </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="foto_artikel" name="foto_artikel" accept="image/*">
                    <div class="form-text">Maks. ukuran 5mb. Format: JPG, JPEG, PNG, GIF.</div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" name="simpanBtn">Simpan</button>
                    <a href="blog-posts.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script src="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>