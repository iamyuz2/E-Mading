<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: pastikan hanya pengurus yang bisa memproses aksi ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pengurus') { 
    header("Location: login.php"); 
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_pengurus = $_SESSION['user_id'];
    $judul       = mysqli_real_escape_string($conn, $_POST['judul']);
    $pesan       = mysqli_real_escape_string($conn, $_POST['pesan']);

    // Mencegah input kosong
    if (empty($judul) || empty($pesan)) {
        echo "<script>alert('Judul dan pesan tidak boleh kosong!'); window.history.back();</script>";
        exit();
    }

    // Memasukkan data notifikasi ke dalam database
    $query = "INSERT INTO notifications (judul, pesan, id_pengurus) 
              VALUES ('$judul', '$pesan', '$id_pengurus')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Berhasil! Notifikasi darurat telah disiarkan ke dasbor seluruh siswa.'); window.location='dashboard_pengurus.php';</script>";
    } else {
        echo "<script>alert('Gagal mengirim notifikasi: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
} else {
    // Jika file diakses langsung tanpa submit form
    header("Location: dashboard_pengurus.php");
    exit();
}
?>
