<?php
session_start(); // Pastikan sesi dimulai

require "koneksi.php"; // Koneksi ke database

$login_error = '';

// Tidak perlu pengalihan awal di sini, karena kita ingin memproses login untuk kedua role.
// Jika admin sudah login di panel admin, mereka mungkin masih bisa mengakses halaman ini,
// tapi logika di bawah akan menanganinya jika mereka mencoba login lagi.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = htmlspecialchars($_POST['username_or_email']);
    $password = $_POST['password'];

    if (empty($username_or_email) || empty($password)) {
        $login_error = "Username/Email dan Password harus diisi!";
    } else {
        // Hapus filter `AND role = 'user'` dari query
        // Query ini akan mencari akun user atau admin berdasarkan username/email
        $query = mysqli_query($con, "SELECT * FROM user WHERE username = '$username_or_email' OR email = '$username_or_email'");
        $count = mysqli_num_rows($query);

        if ($count > 0) {
            $data = mysqli_fetch_array($query);
            if (password_verify($password, $data['password'])) {
                // Password benar, sekarang cek role dari user yang login
                if ($data['role'] === 'admin') {
                    // Login sebagai admin
                    $_SESSION['username'] = $data['username']; // Sesuaikan dengan yang dipakai adminpanel
                    $_SESSION['login'] = true; // Sesuaikan dengan yang dipakai adminpanel
                    $_SESSION['role'] = $data['role']; // 'admin'
                    header("Location: adminpanel/index.php"); // Arahkan ke dashboard admin
                    exit();
                } elseif ($data['role'] === 'user') {
                    // Login sebagai user biasa (logika yang sudah ada)
                    $_SESSION['user_id'] = $data['id'];
                    $_SESSION['user_username'] = $data['username'];
                    $_SESSION['user_login'] = true;
                    $_SESSION['user_role'] = $data['role']; // 'user'
                    header("Location: index.php"); // Arahkan ke halaman utama frontend
                    exit();
                } else {
                    // Jika ada role yang tidak dikenali (opsional, untuk keamanan)
                    $login_error = "Peran pengguna tidak dikenali.";
                }
            } else {
                $login_error = "Password salah!";
            }
        } else {
            $login_error = "Akun tidak ditemukan!"; // Pesan error lebih umum
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Muara Rahong Travel</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
    </style>
</head>
<body>
    <?php require "includes/frontend-navbar.php"; ?>

    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Login ke Akun Anda</h2>
            <?php if ($login_error): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="username_or_email" class="form-label">Username atau Email</label>
                    <input type="text" class="form-control" id="username_or_email" name="username_or_email" required value="<?php echo isset($_POST['username_or_email']) ? htmlspecialchars($_POST['username_or_email']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
                <p class="text-center mt-3">Belum punya akun? <a href="frontend-register.php">Daftar di sini</a></p>
            </form>
        </div>
    </div>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>