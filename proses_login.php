<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_telp  = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE no_telp = '$no_telp'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama_lengkap'];
            $_SESSION['role']    = $user['role'];

            if ($user['role'] === 'pengurus') {
                header("Location: dashboard_pengurus.php");
            } else {
                header("Location: dashboard_siswa.php");
            }
        } else {
            echo "<script>alert('Password salah!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Nomor telepon tidak terdaftar!'); window.history.back();</script>";
    }
}
?>