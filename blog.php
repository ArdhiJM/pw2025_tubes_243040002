<?php
// Pastikan session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require "koneksi.php"; // Koneksi ke database

// Ambil semua postingan blog dari database
$queryBlogPosts = mysqli_query($con, "SELECT * FROM blog_posts ORDER BY created_at DESC");
$blogPosts = [];
while ($post = mysqli_fetch_array($queryBlogPosts)) {
    $blogPosts[] = $post;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Muara Rahong Hills</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Optional: Custom style for blog page if needed */
        .blog-card {
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            height: 100%; /* Ensure cards have same height */
        }
        .blog-card:hover {
            transform: translateY(-5px);
        }
        .blog-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .blog-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .blog-card .card-title {
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 0.75rem;
        }
        .blog-card .card-text {
            font-size: 0.95rem;
            color: #555;
        }
        .blog-meta {
            font-size: 0.85rem;
            color: #888;
            margin-top: auto; /* Push meta info to bottom */
        }
    </style>
</head>
<body>
    <?php require "includes/frontend-navbar.php"; ?>

    <div class="container my-5 pt-5">
        <h2 class="text-center section-heading mb-4">Blog Terbaru</h2>
        <p class="text-center mb-5">Baca artikel-artikel menarik seputar glamping, wisata Pangalengan, dan tips liburan!</p>

        <?php if (empty($blogPosts)): ?>
            <div class="alert alert-info text-center" role="alert">
                Belum ada artikel blog yang tersedia saat ini.
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($blogPosts as $post): ?>
                    <div class="col">
                        <div class="card blog-card">
                            <?php if (!empty($post['image'])): ?>
                                <img src="image/blog_images/<?php echo htmlspecialchars($post['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($post['title']); ?>">
                            <?php else: ?>
                                <img src="image/placeholder.png" class="card-img-top" alt="Placeholder Image">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                                <p class="card-text">
                                    <?php
                                        // Tampilkan sebagian kecil konten tanpa tag HTML
                                        $snippet = strip_tags($post['content']);
                                        echo htmlspecialchars(substr($snippet, 0, 150));
                                        if (strlen($snippet) > 150) {
                                            echo '...';
                                        }
                                    ?>
                                </p>
                                <div class="blog-meta mt-3">
                                    Ditulis oleh: <?php echo htmlspecialchars($post['author']); ?><br>
                                    Dipublikasikan: <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                                </div>
                                <a href="blog-detail.php?s=<?php echo htmlspecialchars($post['slug']); ?>" class="btn btn-primary mt-3">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>