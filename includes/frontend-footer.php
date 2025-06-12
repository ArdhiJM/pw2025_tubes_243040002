<?php
// Tidak perlu session_start() di sini, karena biasanya sudah dimulai oleh halaman utama atau navbar
?>
<footer class="bg-dark text-white pt-5 pb-4 mt-5">
    <div class="container-fluid text-center text-md-start px-md-5 px-3">
        <div class="row text-center text-md-start">

<div class="col-md-4 col-lg-4 col-xl-4 mt-3"> <h5 class="text-uppercase mb-4 fw-bold">Muara Rahong Hills</h5>
    <p>Jelajahi keindahan Pangalengan dengan Muara Rahong Travel. Kami menawarkan paket glamping dan petualangan tak terlupakan untuk liburan Anda.</p>
    <div class="mt-3">
        <a href="#" class="text-white mx-2"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
        <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
    </div>
</div>

<div class="col-md-2 col-lg-2 col-xl-2 mt-3"> <h5 class="text-uppercase mb-4 fw-bold">Tautan Cepat</h5>
    <p><a href="index.php" class="text-white text-decoration-none">Home</a></p>
    <p><a href="destinations.php" class="text-white text-decoration-none">Kategori</a></p>
    <p><a href="contact.php" class="text-white text-decoration-none">Kontak</a></p>
    <?php
    if (isset($_SESSION['user_login']) && $_SESSION['user_login'] === true && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user') {
    ?>
        <p><a href="my_bookings.php" class="text-white text-decoration-none">Pesanan Saya</a></p>
        <p><a href="frontend-logout.php" class="text-white text-decoration-none">Logout</a></p>
    <?php
    } else {
    ?>
        <p><a href="frontend-login.php" class="text-white text-decoration-none">Login</a></p>
        <p><a href="frontend-register.php" class="text-white text-decoration-none">Daftar</a></p>
    <?php
    }
    ?>
</div>

<div class="col-md-6 col-lg-6 col-xl-6 mt-3"> <h5 class="text-uppercase mb-4 fw-bold">Hubungi Kami</h5>
    <p><i class="fas fa-home me-3"></i> Jl. Rahong, Pulosari, Kec. Pangalengan, Kabupaten Bandung, Jawa Barat 40378</p>
    <p><i class="fas fa-envelope me-3"></i> info@muararahonghills.com</p>
    <p><i class="fas fa-phone-alt me-3"></i> +62 8** **** ****</p>
    <p><i class="fas fa-clock me-3"></i> Senin - Jumat: 09:00 - 17:00 WIB</p>
</div>

        </div>

        <hr class="mb-4">

        <div class="row align-items-center">
            <div class="col-md-7 col-lg-8 mx-auto">
                <p class="mb-0 text-center text-md-center">&copy; <?php echo date('Y'); ?> Muara Rahong Hilss. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>