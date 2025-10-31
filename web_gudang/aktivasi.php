<?php
// 1. Memanggil file koneksi database
include("db_config.php");

$error = "";
$sukses = "";

// 2. Memeriksa apakah ada token di URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // 3. Memvalidasi token ke database
    // Memeriksa apakah token ada dan status akun masih 'PENDING'
    $sql_cek = "SELECT * FROM users WHERE activation_token = '$token' AND status = 'PENDING'";
    $q_cek = mysqli_query($koneksi, $sql_cek);
    $n_cek = mysqli_num_rows($q_cek);

    // 4. Logika Aktivasi Akun
    if ($n_cek < 1) {
        // Jika token tidak ditemukan atau akun sudah diaktivasi sebelumnya
        $error = "Token aktivasi tidak valid atau telah kedaluwarsa.";
    } else {
        // Jika token ditemukan, update status akun menjadi 'AKTIF'
        // dan hapus token agar tidak bisa dipakai lagi
        $sql_update = "UPDATE users SET status = 'AKTIF', activation_token = NULL WHERE activation_token = '$token'";
        $q_update = mysqli_query($koneksi, $sql_update);

        if ($q_update) {
            $sukses = "Aktivasi akun Anda berhasil! Silakan login untuk melanjutkan.";
        } else {
            $error = "Terjadi kesalahan saat mengaktifkan akun: " . mysqli_error($koneksi);
        }
    }

} else {
    // Jika halaman diakses langsung tanpa token
    $error = "Halaman tidak valid. Token aktivasi tidak ditemukan.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivasi Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container" style="margin-top: 50px; max-width: 600px;">
        <div class="card">
            <div class="card-header">
                <h3>Aktivasi Akun Admin Gudang</h3>
            </div>
            <div class="card-body">

                <?php if ($error) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                        <hr>
                        <a href="register.php" class="alert-link">Kembali ke halaman Registrasi</a>
                    </div>
                <?php } ?>
                
                <?php if ($sukses) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses; ?>
                        <hr>
                        <a href="index.php" class="btn btn-primary">Pergi ke Halaman Login</a>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

</body>
</html>