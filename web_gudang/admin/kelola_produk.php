<?php 
// 1. PANGGIL HEADER (UDAH TERMASUK SATPAM)
include("inc_header.php"); 

$sukses = "";
$error = ""; 
$katakunci = isset($_GET['katakunci']) ? $_GET['katakunci'] : "";

// 2. LOGIKA HAPUS DATA
if (isset($_GET['op']) && $_GET['op'] == 'hapus') {
    $id = $_GET['id_produk'];
    
    // Hapus data dari database
    $sql_delete = "DELETE FROM produk WHERE id_produk = '$id'";
    $q_delete = mysqli_query($koneksi, $sql_delete);
    
    if ($q_delete) {
        $sukses = "Produk berhasil dihapus.";
    } else {
        $error = "Gagal menghapus produk.";
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1>Kelola Data Produk</h1>
            <p>Di sini Anda bisa menambah, mengubah, dan menghapus data produk di gudang.</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    
                    <?php if ($sukses) { ?>
                        <div class="alert alert-success" role="alert"><?php echo $sukses ?></div>
                    <?php } ?>
                    <?php if ($error) { ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error ?></div>
                    <?php } ?>
                    
                    <p>
                        <a href="kelola_produk_input.php">
                            <input type="button" class="btn btn-primary" value="Tambah Produk Baru" />
                        </a>
                    </p>
                    <form class="row g-3 mb-3" method="get">
                        <div class="col-auto">
                            <input type="text" class="form-control" placeholder="Cari Nama Produk..." name="katakunci" value="<?php echo $katakunci ?>" />
                        </div>
                        <div class="col-auto">
                            <input type="submit" name="Cari" value="Cari" class="btn btn-secondary" />
                        </div>
                    </form>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="col-1">#</th>
                                <th class="col-3">Nama Produk</th>
                                <th class="col-1">Stok</th>
                                <th class="col-2">Harga</th>
                                <th class="col-3">Deskripsi</th>
                                <th class="col-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Logika Pencarian & Pagination
                            $sqltambahan = "";
                            $per_halaman = 5; // Tampilkan 5 data per halaman
                            if ($katakunci != '') {
                                $sqltambahan = " WHERE nama_produk LIKE '%" . $katakunci . "%'";
                            }
                            
                            $sql1 = "SELECT * FROM produk $sqltambahan";
                            $q_total = mysqli_query($koneksi, $sql1);
                            if (!$q_total) { die("Query total gagal: " . mysqli_error($koneksi)); }
                            $total = mysqli_num_rows($q_total);
                            
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $mulai = ($page > 1) ? ($page * $per_halaman) - $per_halaman : 0;
                            $pages = ceil($total / $per_halaman);
                            $Nomor = $mulai + 1; 

                            $sql_final = $sql1 . " ORDER BY id_produk DESC LIMIT $mulai, $per_halaman";
                            $q_final = mysqli_query($koneksi, $sql_final);

                            while ($r1 = mysqli_fetch_array($q_final)) {
                            ?>
                                <tr>
                                    <td><?php echo $Nomor++ ?></td>
                                    <td><?php echo $r1['nama_produk'] ?></td>
                                    <td><?php echo $r1['stok'] ?></td>
                                    <td>Rp <?php echo number_format($r1['harga'], 2, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars(substr($r1['deskripsi'], 0, 50)); ?>...</td>
                                    <td>
                                        <a href="kelola_produk_input.php?id=<?php echo $r1['id_produk'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="kelola_produk.php?op=hapus&id_produk=<?php echo $r1['id_produk'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">Delete</a>
                                    </td>
                                </tr>
                            <?php
                            } 
                            ?>
                        </tbody>
                    </table>
                    
                    <nav>
                        <ul class="pagination">
                            <?php
                            $cari = isset($_GET['cari']) ? $_GET['cari'] : "";
                            for ($i = 1; $i <= $pages; $i++) {
                            ?>
                                <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                                    <a class="page-link" href="kelola_produk.php?katakunci=<?php echo $katakunci ?>&cari=<?php echo $cari ?>&page=<?php echo $i ?>">
                                        <?php echo $i ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>

                </div> </div> </div> </div> </div>
</div>
<?php 
// 2. PANGGIL FOOTER
include("inc_footer.php"); 
?>