<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
        header("Location: login.php");
        exit();
    }

    $id_siswa = $_SESSION['user_id'];
    $id_acara = mysqli_real_escape_string($conn, $_POST['id_acara']);

    // Cek apakah sudah mendaftar (keamanan ganda)
    $cek = mysqli_query($conn, "SELECT id FROM registrations WHERE id_siswa = '$id_siswa' AND id_acara = '$id_acara'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Anda sudah mendaftar!'); window.location='riwayat_acara.php';</script>";
        exit();
    }

    // Insert ke tabel registrations
    $query = "INSERT INTO registrations (id_siswa, id_acara) VALUES ('$id_siswa', '$id_acara')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Pendaftaran Berhasil!'); window.location='riwayat_acara.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
