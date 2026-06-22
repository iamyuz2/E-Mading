<?php
session_start();
include 'koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard_siswa.php");
    exit();
}

$id_acara = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = $_SESSION['user_id'];

// Ambil data acara
$query_acara = mysqli_query($conn, "SELECT * FROM events WHERE id = '$id_acara'");
$acara = mysqli_fetch_assoc($query_acara);

if (!$acara) {
    echo "<script>alert('Acara tidak ditemukan!'); window.location='dashboard_siswa.php';</script>";
    exit();
}

// Ambil data siswa untuk pre-fill form
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query_user);

// Cek apakah sudah mendaftar
$cek = mysqli_query($conn, "SELECT id FROM registrations WHERE id_siswa = '$user_id' AND id_acara = '$id_acara'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Anda sudah mendaftar di acara ini!'); window.location='riwayat_acara.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pendaftaran | E-Mading</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #10b981; --dark: #0f172a; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; }
        .reg-container { max-width: 800px; margin: 50px auto; }
        .card-confirm { background: white; border-radius: 24px; border: none; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .event-header { background: var(--dark); color: white; padding: 40px; }
        .form-section { padding: 40px; }
        .form-control { border-radius: 12px; padding: 12px 18px; background: #f9fafb; border: 1px solid #e5e7eb; }
        .btn-confirm { background: var(--primary); color: white; border: none; border-radius: 12px; padding: 15px; font-weight: 700; width: 100%; transition: 0.3s; }
        .btn-confirm:hover { background: #059669; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="container reg-container">
        <a href="dashboard_siswa.php" class="btn btn-link text-dark text-decoration-none mb-3"><i class="fa-solid fa-arrow-left me-2"></i> Kembali ke Dashboard</a>
        
        <div class="card card-confirm">
            <div class="event-header">
                <span class="badge bg-success mb-2"><?php echo $acara['kategori']; ?></span>
                <h2 class="fw-800 mb-0"><?php echo $acara['judul_acara']; ?></h2>
                <p class="mb-0 opacity-75 mt-2"><i class="fa-solid fa-location-dot me-2"></i> <?php echo $acara['lokasi']; ?></p>
            </div>
            
            <div class="form-section">
                <h5 class="fw-700 mb-4">Konfirmasi Data Diri</h5>
                <form action="proses_daftar.php" method="POST">
                    <input type="hidden" name="id_acara" value="<?php echo $acara['id']; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama" value="<?php echo $user['nama_lengkap']; ?>" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">Kelas</label>
                            <input type="text" class="form-control" name="kelas" value="<?php echo $user['kelas']; ?>" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">Jurusan</label>
                            <input type="text" class="form-control" name="jurusan" value="<?php echo $user['jurusan']; ?>" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">Nomor Telepon / WA</label>
                            <input type="text" class="form-control" name="no_telp" value="<?php echo $user['no_telp']; ?>" required readonly>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4 border-0 rounded-4">
                        <small><i class="fa-solid fa-circle-info me-2"></i> Data di atas diambil otomatis dari profil Anda. Pastikan data sudah benar sebelum mendaftar.</small>
                    </div>

                    <button type="submit" class="btn-confirm mt-3">Konfirmasi & Daftar Sekarang</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>