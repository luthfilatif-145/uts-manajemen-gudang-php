<?php
// 1. Mulai sesi yang udah ada
session_start();

// 2. Hancurkan semua data sesi (tiketnya disobek)
session_destroy();

// 3. Tendang dia balik ke halaman login (di folder luar)
header("location: ../index.php");
exit;
?>