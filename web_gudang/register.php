<?php
// 1. Panggil koneksi
include("db_config.php");

$error = "";
$sukses = "";

// 2. Cek kalo tombol 'register' ditekan
if (isset($_POST['register'])) {
    $nama = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $pass_konfirmasi = $_POST['password_konfirmasi'];

    // 3. Validasi (Cek)
    if ($nama == '' || $email == '' || $pass == '' || $pass_konfirmasi == '') {
        $error = "Semua kolom wajib diisi";
    } 
    // Cek password harus sama
    elseif ($pass != $pass_konfirmasi) {
        $error = "Password konfirmasi beda";
    } 
    // Cek email udah ada apa belom
    else {
        $sql_cek_email = "SELECT * FROM users WHERE email = '$email'";
        $q_cek_email = mysqli_query($koneksi, $sql_cek_email);
        $n_cek_email = mysqli_num_rows($q_cek_email);

        if ($n_cek_email > 0) { // Kalo email udah ada
            $error = "Email <b>$email</b> udah kedaftar, pake email lain!";
        } else {
            // 4. KALO SEMUA AMAN: Siapin data
            
            // Bikin password acak (Token)
            // Ini adalah "kode unik" buat aktivasi
            $token_aktivasi = md5(rand(0, 9999));
            
            // Enkripsi password (PENTING!)
            // Jangan simpen password polos anjing, HARUS di-hash
            $password_hash = password_hash($pass, PASSWORD_DEFAULT);
            
            // 5. Masukin ke database (INSERT)
            $sql_insert = "INSERT INTO users (email, password, nama_lengkap, status, activation_token) 
                           VALUES ('$email', '$password_hash', '$nama', 'PENDING', '$token_aktivasi')";
            $q_insert = mysqli_query($koneksi, $sql_insert);

            if ($q_insert) {
                // 6. INI DIA JALUR TIKUS-NYA
                // Kita nggak kirim email, kita TAMPILIN link-nya
                
                $link_aktivasi = "http://localhost/web_gudang/aktivasi.php?token=" . $token_aktivasi;
                
                $sukses = "Registrasi Berhasil! Akun lu statusnya 'PENDING'.<br><br>
                           <br>
                           <a href='$link_aktivasi'>$link_aktivasi</a><br><br>
                           Klik link di atas buat aktivasi akun lu.";
            } else {
                $error = "Gagal registrasi ke database: " . mysqli_error($koneksi);
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
    <title>Registrasi Admin Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container" style="margin-top: 50px; max-width: 600px;">
        <div class="card">
            <div class="card-header">
                <h3>Form Registrasi Pengguna</h3>
                <p>Daftar sebagai Admin Gudang</p>
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
                
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email (buat Login)</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="password_konfirmasi" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi">
                    </div>
                    <button type="submit" class="btn btn-primary" name="register">Daftar Sekarang</button>
                    <hr>
                    <p>Sudah punya akun? <a href="index.php">Login di sini</a>.</p>
                </form>
                
                <?php } // Penutup } else dari $sukses ?>

            </div>
        </div>
    </div>

</body>
</html>