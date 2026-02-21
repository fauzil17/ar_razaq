<?php
$host = "localhost";
$user = "root";        // default XAMPP / Laragon
$pass = "";            // kosongkan jika default
$db   = "ar_razaq";   // GANTI sesuai nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
