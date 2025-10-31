<?php
// 1. Mulai Sesi di baris PALING ATAS
session_start();
include("../db_config.php"); // Ambil koneksi database

// 2. INI DIA SATPAMNYA
// Cek apakah "tiket" (sesi) user_id ada?
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada, tendang dia kembali ke halaman login
    header("location: ../index.php"); // Arahkan ke index.php di folder luar
    exit;
}

// Ambil info user dari sesi
$user_id = $_SESSION['user_id'];
$user_nama = $_SESSION['user_nama'];

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Gudang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .wrapper {
            display: flex;
            flex: 1;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            /* Warna gelap */
            color: #fff;
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: #c2c7d0;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #007bff;
        }

        .content-wrapper {
            flex: 1;
            padding: 20px;
            background-color: #f4f6f9;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <aside class="sidebar p-3">
            <h5>Admin Gudang</h5>
            <hr class="text-white">
            <div class="user-panel mb-3">
                <p class="text-white">Selamat Datang,<br><strong><?php echo $user_nama; ?></strong></p>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kelola_produk.php">
                        <i class="fas fa-box fa-fw me-2"></i> Kelola Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profil.php">
                        <i class="fas fa-user-edit fa-fw me-2"></i> Edit Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">
                        <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>

        <main class="content-wrapper">
        </main>
    </div>

    <footer class="bg-dark text-white text-center p-3">
        Copyright &copy; <?php echo date("Y"); ?> - Manajemen Gudang
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>