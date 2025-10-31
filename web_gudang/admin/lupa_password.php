<?php
// 1. Panggil koneksi & Mulai Sesi
session_start();
include("db_config.php");

$error = "";
$sukses = "";

// Cek kalo tombol 'kirim' ditekan
if (isset($_POST['kirim'])) {
    $email = $_POST['email'];

    if ($email == '') {
        $error = "Silakan masukkan email Anda.";
    } else {
        // Cek emailnya ada di database nggak?
        $sql_cek = "SELECT * FROM users WHERE email = '$email'";
        $q_cek = mysqli_query($koneksi, $sql_cek);
        $n_cek = mysqli_num_rows($q_cek);

        if ($n_cek < 1) {
            $error = "Email <b>$email</b> tidak terdaftar di sistem kami.";
        } else {
            // Kalo emailnya ADA, kita buat token reset
            $token_reset = md5(rand(0, 9999));
            
            // Simpen token ini ke database
            $sql_update = "UPDATE users SET reset_token = '$token_reset' WHERE email = '$email'";
            $q_update = mysqli_query($koneksi, $sql_update);

            if ($q_update) {
                // 2. INI DIA JALUR TIKUS-NYA
                // Kita nggak kirim email, kita TAMPILIN link-nya
                
                $link_reset = "http://localhost/WEB_GUDANG/reset_password.php?token=" . $token_reset;
                
                $sukses = "Permintaan reset berhasil.<br><br>
                           INI PURA-PURANYA LINK DI EMAIL LU (karena deadline mepet):<br>
                           <a href='$link_reset'>$link_reset</a><br><br>
                           Klik link di atas buat ganti password lu.";
            } else {
                $error = "Gagal membuat token reset: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Admin Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .reset-container { max-width: 450px; margin-top: 100px; }
    </style>
</head>
<body>

    <div class="container reset-container">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h3>Lupa Password</h3>
            </div>
            <div class="card-body">
                
                <?php if ($error) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                
                <?php if ($sukses) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses; ?>
                    </div>
                <?php } else { ?>

                <p>Masukkan email Anda. Kami akan mengirimkan tautan untuk me-reset password Anda.</p>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="kirim">Kirim Tautan Reset</button>
                    </div>
                </form>
                
                <?php } // Penutup } else dari $sukses ?>
                
                <hr>
                <div class="text-center">
                    <p>Sudah ingat? <a href="index.php">Login di sini</a>.</p>
                </div>

            </div>
        </div>
    </div>

</body>
</html>