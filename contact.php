<?php
session_start(); // Pastikan sesi dimulai di baris paling atas
require "koneksi.php"; // Pastikan koneksi.php ada di root

$contact_status = []; // Array untuk menyimpan pesan sukses/error

// Cek apakah user sudah login sebagai 'user'
$is_user_logged_in = isset($_SESSION['user_login']) && $_SESSION['user_login'] === true && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Jika user belum login, tampilkan pesan error dan hentikan proses
    if (!$is_user_logged_in) {
        $_SESSION['contact_redirect_message'] = [
            'type' => 'warning',
            'message' => 'Anda harus login terlebih dahulu untuk mengirim pesan!'
        ];
        header('Location: frontend-login.php'); // Arahkan ke halaman login
        exit();
    }

    // Ambil data dari form dan sanitasi
    $nama_lengkap = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subjek = htmlspecialchars($_POST['subject']); // Sesuaikan jika tidak ada input subjek
    $pesan = htmlspecialchars($_POST['message']);

    // Validasi input
    if (empty($nama_lengkap) || empty($email) || empty($pesan)) {
        $contact_status = [
            'type' => 'danger',
            'message' => 'Nama lengkap, email, dan pesan wajib diisi.'
        ];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contact_status = [
            'type' => 'danger',
            'message' => 'Format email tidak valid.'
        ];
    } else {
        // Masukkan data ke tabel kontak_pesan (gunakan Prepared Statements untuk keamanan)
        $stmt = mysqli_prepare($con, "INSERT INTO kontak_pesan (nama_lengkap, email, subjek, pesan) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nama_lengkap, $email, $subjek, $pesan);

        if (mysqli_stmt_execute($stmt)) {
            $contact_status = [
                'type' => 'success',
                'message' => 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.'
            ];
            // Opsional: kosongkan input form setelah sukses
            $_POST = array(); // Clear POST data to prevent re-submission on refresh
        } else {
            $contact_status = [
                'type' => 'danger',
                'message' => 'Gagal mengirim pesan: ' . mysqli_error($con)
            ];
        }
        mysqli_stmt_close($stmt);
    }
}

// Cek jika ada pesan redirect dari halaman login/register
if (isset($_SESSION['contact_redirect_message'])) {
    $contact_status = $_SESSION['contact_redirect_message'];
    unset($_SESSION['contact_redirect_message']); // Hapus pesan setelah ditampilkan
}

// Untuk mengisi otomatis nama dan email jika user sudah login
$default_name = '';
$default_email = '';
if ($is_user_logged_in) {
    // Asumsi di sesi ada user_username dan user_email
    // Jika di tabel user Anda menyimpan nama lengkap, Anda bisa ambil dari sana
    // Untuk contoh ini, saya asumsikan user_username sebagai nama dan user_email dari tabel user
    // Anda mungkin perlu query database jika hanya user_id yang disimpan di sesi
    $default_name = isset($_SESSION['user_username']) ? $_SESSION['user_username'] : ''; // Sesuaikan jika ada kolom nama_lengkap di sesi
    // Ambil email dari database berdasarkan user_id
    if (isset($_SESSION['user_id'])) {
        $user_id_from_session = $_SESSION['user_id'];
        $queryUserEmail = mysqli_query($con, "SELECT email FROM user WHERE id = '$user_id_from_session'");
        $dataUserEmail = mysqli_fetch_array($queryUserEmail);
        if ($dataUserEmail) {
            $default_email = $dataUserEmail['email'];
        }
    }
}
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
                    <?php
                    // Tampilkan pesan status (sukses/error)
                    if (!empty($contact_status)) {
                        echo '<div class="alert alert-' . htmlspecialchars($contact_status['type']) . ' alert-dismissible fade show" role="alert">';
                        echo htmlspecialchars($contact_status['message']);
                        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                        echo '</div>';
                    }
                    ?>

                    <?php if ($is_user_logged_in): ?>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($default_name); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($default_email); ?>">
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek (Opsional)</label>
                                <input type="text" class="form-control" id="subject" name="subject"
                                       value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan Anda</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info text-center" role="alert">
                            Anda harus <a href="frontend-login.php">login</a> terlebih dahulu untuk mengirim pesan.
                        </div>
                        <div class="d-grid gap-2">
                            <a href="frontend-login.php" class="btn btn-primary btn-lg">Login Sekarang</a>
                            <p class="text-center mt-3">Belum punya akun? <a href="frontend-register.php">Daftar di sini</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="contact-info bg-light p-4 rounded shadow-sm">
                    <h4 class="mb-3">Informasi Kontak</h4>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt me-2 text-primary"></i> Jl. Rahong, Pulosari, Kec. Pangalengan, Kabupaten Bandung, Jawa Barat 40378</li>
                        <li><i class="fas fa-phone-alt me-2 text-primary"></i> +62 8** **** ****</li>
                        <li><i class="fas fa-envelope me-2 text-primary"></i> info@muararahongtravel.com</li>
                        <li><i class="fas fa-clock me-2 text-primary"></i> Senin - Jumat: 09:00 - 17:00 WIB</li>
                    </ul>

                    <h4 class="mt-4 mb-3">Lokasi Kami</h4>
                    <div class="embed-responsive embed-responsive-16by9" style="height: 300px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3958.5016292497626!2d107.5428276!3d-7.1834568!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e689181a98a71a9%3A0x1e83a8c14aceb6e7!2sMUARA%20RAHONG%20HILL&#39;S!5e0!3m2!1sen!2sid!4v1749871626448!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
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