<?php
// Pastikan session sudah dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require "koneksi.php"; // Koneksi ke database

$post_slug = '';
if (isset($_GET['s'])) {
    $post_slug = htmlspecialchars($_GET['s']);
}

$blogPost = null;
if (!empty($post_slug)) {
    $queryPost = mysqli_query($con, "SELECT * FROM blog_posts WHERE slug = '$post_slug'");
    $blogPost = mysqli_fetch_array($queryPost);
}

// Jika artikel tidak ditemukan
if (!$blogPost) {
    echo "<div class='alert alert-danger text-center mt-5' role='alert'>Artikel blog tidak ditemukan!</div>";
    echo "<meta http-equiv='refresh' content='2; url=blog.php'>"; // Redirect kembali ke daftar blog
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blogPost['title']); ?> - Muara Rahong Hills Blog</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .blog-detail-img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .blog-content p {
            line-height: 1.8;
            margin-bottom: 1rem;
        }
        .blog-meta {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 1.5rem;
        }
        .blog-content h1, .blog-content h2, .blog-content h3, .blog-content h4, .blog-content h5, .blog-content h6 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }
        .blog-content ul, .blog-content ol {
            margin-left: 20px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php require "includes/frontend-navbar.php"; ?>

    <div class="container my-5 pt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="blog.php" class="text-decoration-none">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($blogPost['title']); ?></li>
            </ol>
        </nav>

        <article class="blog-post mt-4">
            <h1 class="mb-3"><?php echo htmlspecialchars($blogPost['title']); ?></h1>
            <div class="blog-meta">
                Ditulis oleh: <?php echo htmlspecialchars($blogPost['author']); ?> pada <?php echo date('d M Y H:i', strtotime($blogPost['created_at'])); ?>
            </div>

            <?php if (!empty($blogPost['image'])): ?>
                <img src="image/blog_images/<?php echo htmlspecialchars($blogPost['image']); ?>" class="blog-detail-img" alt="<?php echo htmlspecialchars($blogPost['title']); ?>">
            <?php else: ?>
                <img src="image/placeholder.png" class="blog-detail-img" alt="Placeholder Image">
            <?php endif; ?>

            <div class="blog-content">
                <?php echo $blogPost['content']; // Konten sudah aman dari database ?>
            </div>

            <div class="mt-5 text-center">
                <a href="blog.php" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-2"></i>Kembali ke Blog</a>
            </div>
        </article>
    </div>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
</body>
</html>