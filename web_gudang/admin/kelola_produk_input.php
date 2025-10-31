<?php
// 1. PANGGIL HEADER (UDAH TERMASUK SATPAM)
include("inc_header.php"); 

$error = "";
$sukses = "";

// Inisialisasi variabel untuk form
$nama_produk = "";
$deskripsi = "";
$stok = "";
$harga = "";

// 2. LOGIKA MODE EDIT (Ambil data jika ada ID)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = "";
}

if ($id != "") { // Jika ID ada (mode Edit)
    $sql_get = "SELECT * FROM produk WHERE id_produk = '$id'";
    $q_get = mysqli_query($koneksi, $sql_get);
    $data = mysqli_fetch_assoc($q_get);
    if ($data) {
        $nama_produk = $data['nama_produk'];
        $deskripsi = $data['deskripsi'];
        $stok = $data['stok'];
        $harga = $data['harga'];
    } else {
        $error = "Data produk tidak ditemukan.";
    }
}

// 3. LOGIKA SAAT TOMBOL "SIMPAN" DITEKAN (POST)
if (isset($_POST['simpan'])) {
    $nama_produk = $_POST['nama_produk'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // Validasi dasar
    if ($nama_produk == '' || $stok == '' || $harga == '') {
        $error = "Kolom Wajib (Nama, Stok, Harga) tidak boleh kosong.";
    }

    // Jika tidak ada error, lanjutkan simpan ke DB
    if (empty($error)) {
        if ($id == "") { // Mode CREATE (Tambah Baru)
            $sql_insert = "INSERT INTO produk (nama_produk, deskripsi, stok, harga) 
                           VALUES ('$nama_produk', '$deskripsi', '$stok', '$harga')";
            $q_insert = mysqli_query($koneksi, $sql_insert);
            if ($q_insert) {
                $sukses = "Produk baru berhasil ditambahkan.";
            } else {
                $error = "Gagal menambahkan produk: " . mysqli_error($koneksi);
            }
        } else { // Mode UPDATE (Edit)
            $sql_update = "UPDATE produk SET 
                            nama_produk = '$nama_produk', 
                            deskripsi = '$deskripsi', 
                            stok = '$stok', 
                            harga = '$harga'
                          WHERE id_produk = '$id'";
            $q_update = mysqli_query($koneksi, $sql_update);
            if ($q_update) {
                $sukses = "Data produk berhasil diperbarui.";
            } else {
                $error = "Gagal memperbarui produk: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1><?php echo ($id) ? 'Edit' : 'Tambah'; ?> Produk</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    <p>
                        <a href="kelola_produk.php" class="btn btn-sm btn-secondary">
                            &lt;&lt; Kembali ke Daftar Produk
                        </a>
                    </p>

                    <?php if ($error) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error ?>
                        </div>
                    <?php } ?>
                    <?php if ($sukses) { ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $sukses ?>
                            <meta http-equiv="refresh" content="2;url=kelola_produk.php">
                        </div>
                    <?php } ?>

                    <form action="" method="post">
                        
                        <div class="mb-3 row">
                            <label for="nama_produk" class="col-sm-2 col-form-label">Nama Produk*</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama_produk" value="<?php echo $nama_produk; ?>" name="nama_produk">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="stok" class="col-sm-2 col-form-label">Stok*</Tlabel>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="stok" value="<?php echo $stok; ?>" name="stok">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="harga" class="col-sm-2 col-form-label">Harga*</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="harga" value="<?php echo $harga; ?>" name="harga" placeholder="Contoh: 15000.00">
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                            <div class="col-sm-10">    
                                <textarea class="form-control" name="deskripsi" rows="4"><?php echo $deskripsi; ?></textarea>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <div class="col-sm-2"></div>
                            <div class="col-sm-10">
                                <input type="submit" name="simpan" class="btn btn-primary" value="Simpan Produk"/>
                            </div>
                        </div>
                    </form>

                </div> </div> </div> </div> </div>
<?php 
// 2. PANGGIL FOOTER
include("inc_footer.php"); 
?>