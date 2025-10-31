<?php
// 1. Panggil koneksi & Mulai Sesi
session_start();
include("db_config.php");

$error = "";
$sukses = "";
$token_valid = false; // Penanda token

// 2. Cek ada token-nya nggak di URL?
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 3. Cek token-nya ke database
    $sql_cek = "SELECT * FROM users WHERE reset_token = '$token'";
    $q_cek = mysqli_query($koneksi, $sql_cek);
    $n_cek = mysqli_num_rows($q_cek);

    if ($n_cek > 0) {
        $token_valid = true; // Tokennya bener!
    } else {
        $error = "Token reset password tidak valid atau sudah kedaluwarsa.";
    }
} else {
    $error = "Halaman tidak valid. Token tidak ditemukan.";
}

// 4. Cek kalo tombol 'reset' ditekan
if (isset($_POST['reset'])) {
    $pass_baru = $_POST['password_baru'];
    $pass_konfirmasi = $_POST['password_konfirmasi'];
    $token_hidden = $_POST['token_hidden']; // Ambil token dari form

    if ($pass_baru == '' || $pass_konfirmasi == '') {
        $error = "Password baru dan konfirmasi tidak boleh kosong.";
    } elseif ($pass_baru != $pass_konfirmasi) {
        $error = "Password Baru dan Konfirmasi Password tidak cocok.";
    } else {
        // Kalo semua aman, HASH password baru
        $password_hash_baru = password_hash($pass_baru, PASSWORD_DEFAULT);
        
        // UPDATE password baru DAN HAPUS token-nya
        $sql_update_pass = "UPDATE users SET 
                            password = '$password_hash_baru', 
                            reset_token = NULL 
                          WHERE reset_token = '$token_hidden'";
        
        $q_update_pass = mysqli_query($koneksi, $sql_update_pass);
        
        if ($q_update_pass) {
            $sukses = "Password berhasil diubah! Silakan login dengan password baru Anda.";
        } else {
            $error = "Gagal mengubah password: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Admin Gudang</title>
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
                <h3>Buat Password Baru</h3>
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
                        <hr>
                        <a href="index.php" class="btn btn-primary">Pergi ke Halaman Login</a>
                    </div>
                
                <?php } elseif ($token_valid) { // Tampilkan form HANYA jika token bener ?>
                
                <p>Masukkan password baru Anda.</p>
                <form action="" method="post">
                    <input type="hidden" name="token_hidden" value="<?php echo $token; ?>">
                    
                    <div class="mb-3">
                        <label for="password_baru" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password_baru" name="password_baru">
                    </div>
                    <div class="mb-3">
                        <label for="password_konfirmasi" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi">
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="reset">Simpan Password Baru</button>
                    </div>
                </form>
                
                <?php } // Penutup } else ?>

            </div>
        </div>
    </div>

</body>
</html>