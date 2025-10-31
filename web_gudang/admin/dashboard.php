<?php 
// 1. Panggil Header (Otomatis Satpamnya jalan)
include("inc_header.php"); 
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Dashboard Admin Gudang</h1>
            <p>Selamat datang di halaman utama. Silakan kelola data produk atau profil Anda melalui menu di samping.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kelola Produk</h5>
                    <p class="card-text">Atur, tambah, edit, atau hapus data produk di gudang.</p>
                    <a href="kelola_produk.php" class="btn btn-primary">Pergi ke Kelola Produk</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profil Anda</h5>
                    <p class="card-text">Perbarui informasi data diri dan password Anda.</p>
                    <a href="profil.php" class="btn btn-secondary">Pergi ke Edit Profil</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
// 2. Panggil Footer
include("inc_footer.php"); 
?>