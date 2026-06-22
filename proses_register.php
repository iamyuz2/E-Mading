<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $kelas   = mysqli_real_escape_string($conn, $_POST['kelas']);
    $jurusan = mysqli_real_escape_string($conn, $_POST['jurusan']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $role    = mysqli_real_escape_string($conn, $_POST['role']);
    $pass    = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($pass !== $confirm) {
        echo "<script>alert('Konfirmasi password tidak cocok!'); window.history.back();</script>";
        exit;
    }

    $cek_telp = mysqli_query($conn, "SELECT no_telp FROM users WHERE no_telp = '$no_telp'");
    if (mysqli_num_rows($cek_telp) > 0) {
        echo "<script>alert('Nomor telepon sudah terdaftar!'); window.history.back();</script>";
        exit;
    }

    $password_hash = password_hash($pass, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO users (nama_lengkap, kelas, jurusan, no_telp, password, role) 
              VALUES ('$nama', '$kelas', '$jurusan', '$no_telp', '$password_hash', '$role')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Daftar Berhasil! Silakan Login.'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>