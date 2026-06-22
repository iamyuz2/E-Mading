<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: pastikan hanya pengurus yang bisa masuk
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pengurus') { 
    header("Location: login.php"); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siarkan Pengumuman Penting | E-Mading</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .btn-warning-custom { background-color: #f59e0b; color: white; border: none; font-weight: 600; transition: 0.2s; }
        .btn-warning-custom:hover { background-color: #d97706; color: white; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center mb-4">
                    <a href="dashboard_pengurus.php" class="btn btn-outline-secondary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h3 class="fw-800 text-dark m-0">Siarkan Pengumuman Penting</h3>
                </div>

                <div class="card card-custom p-4 bg-white border-top border-warning border-4">
                    <form action="proses_notifikasi.php" method="POST">
                        <p class="text-muted small mb-4">
                            <i class="fa-solid fa-circle-info text-warning me-1"></i> 
                            Gunakan fitur broadcast ini untuk menyiarkan informasi darurat atau perubahan jadwal mendadak langsung ke halaman dashboard seluruh siswa.
                        </p>

                        <div class="mb-3">
                            <label class="form-label fw-600 small text-muted">Subjek / Judul Pengumuman</label>
                            <input type="text" name="judul" class="form-control rounded-3 py-2" placeholder="Contoh: Perubahan Lokasi Lomba atau Libur Dadakan" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600 small text-muted">Isi Pesan Pengumuman</label>
                            <textarea name="pesan" class="form-control rounded-3" rows="6" placeholder="Ketik rincian pesan darurat/penting di sini secara jelas..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-warning-custom w-100 py-2.5 rounded-3 fs-5 shadow-sm">
                            <i class="fa-solid fa-bullhorn me-2"></i>Siarkan ke Seluruh Siswa Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>