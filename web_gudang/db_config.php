<?php
/*
File: db_config.php
Koneksi ke database db_uts_gudang
*/

$hostname   = "localhost";
$username   = "root";
$password   = "";
$database   = "database_gudang"; // Database baru kita

$koneksi = mysqli_connect($hostname, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>