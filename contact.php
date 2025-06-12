<?php
    // session_start();  untuk menyimpan data formulir kontak ke sesi atau menampilkan pesan berdasarkan sesi.
    require "koneksi.php"; // Pastikan koneksi.php ada di root
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami - Muara Rahong Travel</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require "includes/frontend-navbar.php"; ?>

    <div class="container my-5 pt-5">
        <h2 class="text-center section-heading">Hubungi Kami</h2>
        <p class="text-center mb-4">Kami senang mendengar dari Anda! Silakan kirimkan pertanyaan atau masukan Anda.</p>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="contact-section">
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek</label>
                            <input type="text" class="form-control" id="subject" name="subject">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="contact-section">
                    <h4 class="mb-3">Informasi Kontak Kami</h4>
                    <ul class="list-unstyled contact-info">
                        <li><i class="fas fa-map-marker-alt me-2 text-primary"></i> Jl. Rahong, Pulosari, Kec. Pangalengan, Kabupaten Bandung, Jawa Barat 40378</li>
                        <li><i class="fas fa-phone-alt me-2 text-primary"></i> +62 812 3456 7890</li>
                        <li><i class="fas fa-envelope me-2 text-primary"></i> info@muararahongtravel.com</li>
                        <li><i class="fas fa-clock me-2 text-primary"></i> Senin - Jumat: 09:00 - 17:00 WIB</li>
                    </ul>

                    <h4 class="mt-4 mb-3">Lokasi Kami</h4>
                    <div class="embed-responsive embed-responsive-16by9" style="height: 300px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.5016295066484!2d107.54282757414559!3d-7.183456770498972!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e689181a98a71a9%3A0x1e83a8c14aceb6e7!2sMUARA%20RAHONG%20HILL&#39;S!5e0!3m2!1sen!2sid!4v1749670066035!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>