<?php
// Ensure session is started before accessing session variables
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg" id="customNavbar">
    <div class="container">
        <a class="navbar-brand" href="index.php">Muara Rahong Admin</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item me-4">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link" href="kategori.php">Kategori</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link" href="produk.php">Produk</a>
                </li>
                <li class="nav-item me-4">
                    <a class="nav-link" href="pemesanan.php">Pemesanan</a> </li>

                <?php if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Halo, <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../adminpanel/logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>