<?php
// Pastikan session sudah dimulai sebelum mengakses variabel session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg shadow-sm fixed-top" id="frontendNavbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class=""></i>Muara Rahong Hills </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#tentang-kami-section">Tentang Kami</a> </li>
                <li class="nav-item">
                    <a class="nav-link" href="kategori.php">Harga</a> </li>
                <li class="nav-item">
                    <a class="nav-link" href="blog.php">Blog</a> </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Kontak</a>
                </li>
                <?php
                // Cek apakah user sudah login di frontend (role 'user')
                if (isset($_SESSION['user_login']) && $_SESSION['user_login'] === true && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user') {
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Halo, <?php echo htmlspecialchars($_SESSION['user_username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                            <li><a class="dropdown-item" href="my_bookings.php">Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="frontend-logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <?php
                } else {
                    // Jika user belum login
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="frontend-login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light ms-2" href="frontend-register.php">Daftar</a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>