<?php
// 1. Mulai Sesi
session_start();
include("db_config.php"); // Sambungkan ke database

$error = "";

// 2. SATPAM HALAMAN LOGIN
// Cek apakah pengguna SUDAH LOGIN?
if ( isset($_SESSION['user_id']) ) {
    // Jika sudah, langsung arahkan ke dashboard
    header("location: admin/dashboard.php");
    exit;
}

// 3. Cek apakah tombol Login ditekan?
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 4. Validasi dasar
    if ($email == '' or $password == '') {
        $error = "Silakan masukkan Email dan Password Anda.";
    } else {
        // 5. Cek Email ke database
        $sql1 = "SELECT * FROM users WHERE email = '$email'";
        $q1 = mysqli_query($koneksi, $sql1);
        $r1 = mysqli_fetch_array($q1);
        $n1 = mysqli_num_rows($q1);

        if ($n1 < 1) {
            // Jika email tidak ditemukan
            $error = "Email tidak terdaftar.";
        } 
        // 6. Cek Password (jika email ditemukan)
        // Kita gunakan password_verify() untuk mencocokkan password yang di-hash
        elseif (!password_verify($password, $r1['password'])) {
            $error = "Password yang Anda masukkan salah.";
        } 
        // 7. Cek Status Akun (jika password benar)
        elseif ($r1['status'] == 'PENDING') {
            $error = "Akun Anda belum aktif. Silakan periksa email Anda untuk link aktivasi.";
        } 
        // 8. Jika SEMUA BENAR (Email ada, Password cocok, Status AKTIF)
        else {
            // Buat Sesi (Tiket Masuk)
            $_SESSION['user_id'] = $r1['id'];
            $_SESSION['user_email'] = $r1['email'];
            $_SESSION['user_nama'] = $r1['nama_lengkap'];
            
            // Arahkan ke dashboard admin
            // Pastikan Anda memiliki folder 'admin'
            header("location: admin/dashboard.php"); 
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 450px;
            margin-top: 100px;
        }
    </style>
</head>
<body>

    <div class="container login-container">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h3>Login Admin Gudang</h3>
            </div>
            <div class="card-body">
                
                <?php if ($error) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <form action="" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="login">Login</button>
                    </div>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <p>Belum punya akun? <a href="register.php">Registrasi di sini</a>.</p>
                    <p><a href="lupa_password.php">Lupa Password?</a></p>
                </div>

            </div>
        </div>
    </div>

</body>
</html>