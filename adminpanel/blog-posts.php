<?php
require "session.php"; // Memeriksa sesi admin
require "../koneksi.php";

$queryBlogPosts = mysqli_query($con, "SELECT * FROM blog_posts ORDER BY created_at DESC");
$jumlahBlogPosts = mysqli_num_rows($queryBlogPosts);

// Logika untuk menghapus postingan
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_to_delete = htmlspecialchars($_GET['id']);

    // Ambil nama file foto sebelum dihapus dari database
    $queryFoto = mysqli_query($con, "SELECT foto_artikel FROM blog_posts WHERE id = '$id_to_delete'");
    $dataFoto = mysqli_fetch_array($queryFoto);
    $foto_artikel_lama = $dataFoto['foto_artikel'];

    // Hapus data dari database
    $queryDelete = mysqli_query($con, "DELETE FROM blog_posts WHERE id = '$id_to_delete'");

    if ($queryDelete) {
        // Hapus file foto fisik jika ada
        if ($foto_artikel_lama && file_exists("../image/" . $foto_artikel_lama)) {
            unlink("../image/" . $foto_artikel_lama);
        }
        $_SESSION['status_message'] = ['type' => 'success', 'message' => 'Postingan berhasil dihapus!'];
    } else {
        $_SESSION['status_message'] = ['type' => 'danger', 'message' => 'Gagal menghapus postingan: ' . mysqli_error($con)];
    }
    header('Location: blog-posts.php');
    exit();
}

// Untuk menampilkan pesan status (dari penambahan/pengeditan/penghapusan)
$status_message = '';
if (isset($_SESSION['status_message'])) {
    $status_message = '<div class="alert alert-' . $_SESSION['status_message']['type'] . ' alert-dismissible fade show" role="alert">' . $_SESSION['status_message']['message'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    unset($_SESSION['status_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Blog Posts - Admin Muara Rahong</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .blog-thumbnail {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php require "navbar.php"; ?>

    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Blog Posts</li>
            </ol>
        </nav>

        <h2 class="mb-4">Manajemen Postingan Blog</h2>

        <?php echo $status_message; ?>

        <div class="my-3">
            <a href="blog-posts-detail.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Postingan Baru</a>
        </div>

        <div class="table-responsive">
            <?php if ($jumlahBlogPosts == 0): ?>
                <div class="alert alert-info text-center" role="alert">
                    Belum ada postingan blog.
                </div>
            <?php else: ?>
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No.</th>
                            <th>Foto</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Tanggal Publikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($post = mysqli_fetch_array($queryBlogPosts)) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <?php if (!empty($post['foto_artikel'])): ?>
                                        <img src="../image/<?php echo htmlspecialchars($post['foto_artikel']); ?>" alt="Foto Artikel" class="blog-thumbnail">
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo htmlspecialchars($post['author']); ?></td>
                                <td><?php echo date('d M Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <a href="blog-posts-detail.php?id=<?php echo htmlspecialchars($post['id']); ?>"
                                        class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="blog-posts.php?action=delete&id=<?php echo htmlspecialchars($post['id']); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?');">
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
</body>
</html>