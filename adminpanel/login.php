<?php
session_start();
require "../koneksi.php"; // Perhatikan path koneksi.php

// Jika admin sudah login, redirect ke halaman utama admin
if (isset($_SESSION['login']) && $_SESSION['login'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('location: index.php'); // Atau sesuaikan ke path dashboard admin Anda
    exit();
}

$login_error = '';

if (isset($_POST['loginbtn'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($con, "SELECT * FROM user WHERE username='$username' AND role='admin'"); // Tambahkan kondisi role
    $count = mysqli_num_rows($query);

    if ($count > 0) {
        $data = mysqli_fetch_array($query);
        if (password_verify($password, $data['password'])) {
            $_SESSION['username'] = $data['username'];
            $_SESSION['login'] = true;
            $_SESSION['role'] = $data['role']; // Simpan role juga
            header('location: index.php'); // Redirect ke halaman utama admin
            exit();
        } else {
            $login_error = "Password salah!";
        }
    } else {
        $login_error = "Akun Admin tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="../bootstrap/bootstrap-5.3.6-dist/bootstrap-5.3.6-dist/css/bootstrap.min.css">
</head>
<style>
    .main{
        height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .login-box {
        width: 500px;
        height: 300px;
        box-sizing: border-box;
        border-radius: 10px;
    }
</style>

<body>
    <div class="main">
        <div class="login-box p-5 shadow bg-white">
            <form action="" method="post">
                <div>
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" id="username">
                </div>
                <div>
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div>
                    <button class="btn btn-success form-control mt-3" type="submit" name="loginbtn">Login</button>
                </div>
            </form>
            <?php if ($login_error): ?>
                <div class="alert alert-warning mt-3" role="alert">
                    <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>