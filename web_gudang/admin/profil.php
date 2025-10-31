<?php 
// 1. PANGGIL HEADER (UDAH TERMASUK SATPAM & KONEKSI)
include("inc_header.php"); 

// Inisialisasi variabel
$error_profil = "";
$sukses_profil = "";
$error_pass = "";
$sukses_pass = "";

// Ambil ID user yang lagi login dari Sesi (Session)
// Pastikan sesi sudah dimulai di inc_header.php
$user_id = $_SESSION['user_id'];

// 2. LOGIKA SIMPAN PROFIL (POST 'simpan_profil')
// =================================================
if (isset($_POST['simpan_profil'])) {
    $nama_lengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];

    // Cek kalo email diganti, apa udah dipake orang lain?
    $sql_cek_email = "SELECT * FROM users WHERE email = '$email' AND id != '$user_id'";
    $q_cek_email = mysqli_query($koneksi, $sql_cek_email);
    $n_cek_email = mysqli_num_rows($q_cek_email);

    if ($nama_lengkap == '' || $email == '') {
        $error_profil = "Nama Lengkap dan Email tidak boleh kosong.";
    } elseif ($n_cek_email > 0) {
        $error_profil = "Email <b>$email</b> sudah digunakan oleh akun lain.";
    } else {
        // Kalo aman, UPDATE profil
        $sql_update = "UPDATE users SET nama_lengkap = '$nama_lengkap', email = '$email' WHERE id = '$user_id'";
        $q_update = mysqli_query($koneksi, $sql_update);
        if ($q_update) {
            // Update juga sesi-nya biar namanya di sidebar ganti
            $_SESSION['user_nama'] = $nama_lengkap;
            $_SESSION['user_email'] = $email;
            $sukses_profil = "Profil berhasil diperbarui.";
        } else {
            $error_profil = "Gagal memperbarui profil: " . mysqli_error($koneksi);
        }
    }
}

// 3. LOGIKA UBAH PASSWORD (POST 'ubah_password')
// =================================================
if (isset($_POST['ubah_password'])) {
    $pass_lama = $_POST['password_lama'];
    $pass_baru = $_POST['password_baru'];
    $pass_konfirmasi = $_POST['password_konfirmasi'];

    // Ambil data user saat ini (buat ngecek password lama)
    $sql_user = "SELECT * FROM users WHERE id = '$user_id'";
    $q_user = mysqli_query($koneksi, $sql_user);
    $data_user = mysqli_fetch_assoc($q_user);

    // Validasi
    if ($pass_lama == '' || $pass_baru == '' || $pass_konfirmasi == '') {
        $error_pass = "Semua kolom password wajib diisi.";
    } 
    // Cek apakah password lama yang dimasukkan BEDA sama yang di database
    elseif (!password_verify($pass_lama, $data_user['password'])) {
        $error_pass = "Password Lama Anda salah.";
    } 
    // Cek apakah password baru & konfirmasinya BEDA
    elseif ($pass_baru != $pass_konfirmasi) {
        $error_pass = "Password Baru dan Konfirmasi Password tidak cocok.";
    } else {
        // Kalo semua aman, HASH password baru
        $password_hash_baru = password_hash($pass_baru, PASSWORD_DEFAULT);
        
        // UPDATE password baru ke database
        $sql_update_pass = "UPDATE users SET password = '$password_hash_baru' WHERE id = '$user_id'";
        $q_update_pass = mysqli_query($koneksi, $sql_update_pass);
        
        if ($q_update_pass) {
            $sukses_pass = "Password berhasil diubah.";
        } else {
            $error_pass = "Gagal mengubah password: " . mysqli_error($koneksi);
        }
    }
}

// 4. LOGIKA AMBIL DATA (READ)
// =================================================
// Ambil data terbaru user untuk ditampilkan di form
$sql_get = "SELECT * FROM users WHERE id = '$user_id'";
$q_get = mysqli_query($koneksi, $sql_get);
$data = mysqli_fetch_assoc($q_get);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Edit Profil Saya</h1>
            <p>Kelola informasi profil dan password Anda.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Profil</h3>
                </div>
                <div class="card-body">
                    <?php if ($error_profil) { ?><div class="alert alert-danger" role="alert"><?php echo $error_profil ?></div><?php } ?>
                    <?php if ($sukses_profil) { ?><div class="alert alert-success" role="alert"><?php echo $sukses_profil ?></div><?php } ?>
                    
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($data['nama_lengkap']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email (untuk Login)</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary" name="simpan_profil">Simpan Perubahan Profil</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Ubah Password</h3>
                </div>
                <div class="card-body">
                    <?php if ($error_pass) { ?><div class="alert alert-danger" role="alert"><?php echo $error_pass ?></div><?php } ?>
                    <?php if ($sukses_pass) { ?><div class="alert alert-success" role="alert"><?php echo $sukses_pass ?></div><?php } ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="password_lama" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="password_lama" name="password_lama">
                        </div>
                        <div class="mb-3">
                            <label for="password_baru" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password_baru" name="password_baru">
                        </div>
                        <div class="mb-3">
                            <label for="password_konfirmasi" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_konfirmasi" name="password_konfirmasi">
                        </div>
                        <button type="submit" class="btn btn-danger" name="ubah_password">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
// 2. PANGGIL FOOTER
include("inc_footer.php"); 
?>