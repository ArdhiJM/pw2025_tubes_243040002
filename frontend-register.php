<?php
session_start(); // Diperlukan untuk menampilkan pesan register
require "koneksi.php"; // Koneksi ke database

$register_success = false;
$register_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $register_error = "Semua kolom harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_error = "Format email tidak valid!";
    } elseif ($password !== $confirm_password) {
        $register_error = "Konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $register_error = "Password minimal 6 karakter!";
    } else {
        // Cek apakah username atau email sudah terdaftar di tabel `user`
        $queryCheck = mysqli_query($con, "SELECT * FROM user WHERE username = '$username' OR email = '$email'");
        if (mysqli_num_rows($queryCheck) > 0) {
            $existing_user = mysqli_fetch_assoc($queryCheck);
            if ($existing_user['username'] == $username) {
                $register_error = "Username sudah terdaftar!";
            } else {
                $register_error = "Email sudah terdaftar!";
            }
        } else {
            // Hash password sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Masukkan data ke tabel `user` dengan role 'user'
            $queryInsert = mysqli_query($con, "INSERT INTO user (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'user')");

            if ($queryInsert) {
                $register_success = true;
            } else {
                $register_error = "Terjadi kesalahan saat registrasi: " . mysqli_error($con);
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
    <title>Daftar Akun - Muara Rahong Travel</title>
    <link rel="stylesheet" href="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .register-container h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <?php require "includes/frontend-navbar.php"; ?>

    <div class="container">
        <div class="register-container">
            <h2>Daftar Akun Baru</h2>
            <?php if ($register_success): ?>
                <div class="alert alert-success text-center" role="alert">
                    Registrasi berhasil! Silakan <a href="frontend-login.php">login</a>.
                </div>
            <?php elseif ($register_error): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo htmlspecialchars($register_error); ?>
                </div>
            <?php endif; ?>
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Daftar Sekarang</button>
                <p class="text-center mt-3">Sudah punya akun? <a href="frontend-login.php">Login di sini</a></p>
            </form>
        </div>
    </div>

    <?php require "includes/frontend-footer.php"; ?>

    <script src="bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/fontawesome-free-6.7.2-web/fontawesome-free-6.7.2-web/js/all.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>