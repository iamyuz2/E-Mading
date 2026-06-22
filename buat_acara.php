<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: pastikan hanya pengurus yang bisa masuk
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pengurus') { 
    header("Location: login.php"); 
    exit(); 
}

// Proses form saat tombol simpan ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul_acara   = mysqli_real_escape_string($conn, $_POST['judul_acara']);
    $kategori      = mysqli_real_escape_string($conn, $_POST['kategori']);
    $lokasi        = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $tanggal_waktu = mysqli_real_escape_string($conn, $_POST['tanggal_waktu']);
    $deskripsi     = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    // Proses upload poster
    $poster_name = "";
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] == 0) {
        $filename    = $_FILES['poster']['name'];
        $tmp_name    = $_FILES['poster']['tmp_name'];
        $ext         = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        
        if (in_array($ext, $allowed_ext)) {
            // Membuat nama file unik agar tidak bentrok
            $poster_name = "poster_" . time() . "." . $ext;
            $target_dir  = __DIR__ . '/uploads/';
            
            // Buat folder uploads jika belum ada
            if (!is_dir($target_dir)) { 
                mkdir($target_dir, 0777, true); 
            }
            
            move_uploaded_file($tmp_name, $target_dir . $poster_name);
        } else {
            echo "<script>alert('Format gambar harus JPG, JPEG, atau PNG!'); window.history.back();</script>";
            exit();
        }
    }

    // Query simpan ke tabel events sesuai struktur database
    $query = "INSERT INTO events (judul_acara, deskripsi, kategori, poster, lokasi, tanggal_waktu) 
              VALUES ('$judul_acara', '$deskripsi', '$kategori', '$poster_name', '$lokasi', '$tanggal_waktu')";
              
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Acara baru berhasil diterbitkan!'); window.location='dashboard_pengurus.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan acara: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Acara Baru | E-Mading Pengurus</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .btn-primary-custom { background-color: #10b981; color: white; border: none; font-weight: 600; transition: 0.2s; }
        .btn-primary-custom:hover { background-color: #059669; color: white; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="dashboard_pengurus.php" class="btn btn-outline-secondary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h3 class="fw-800 text-dark m-0">Tambah Acara Mading Baru</h3>
                </div>

                <div class="card card-custom p-4 bg-white">
                    <form action="buat_acara.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-600 small text-muted">Judul Acara / Kegiatan</label>
                            <input type="text" name="judul_acara" class="form-control rounded-3"
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 small text-muted">Kategori</label>
                                <select name="kategori" class="form-select rounded-3" required>
                                    <option value="" disabled selected>-- Pilih Kategori --</option>
                                    <option value="Olahraga">Olahraga</option>
                                    <option value="E-Sports">E-Sports</option>
                                    <option value="Seni & Budaya">Seni & Budaya</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Pengumuman Resmi">Pengumuman Resmi</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 small text-muted">Lokasi / Tempat Pelaksanaan</label>
                                <input type="text" name="lokasi" class="form-control rounded-3"
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 small text-muted">Tanggal & Waktu</label>
                                <input type="datetime-local" name="tanggal_waktu" class="form-control rounded-3" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-600 small text-muted">Upload Gambar Poster (JPG/PNG)</label>
                                <input type="file" name="poster" class="form-control rounded-3" accept="image/*" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-600 small text-muted">Deskripsi Lengkap Acara</label>
                            <textarea name="deskripsi" class="form-control rounded-3" rows="5" placeholder="Tuliskan persyaratan pendaftaran, hadiah, dan detail informasi acara di sini..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-2.5 rounded-3 fs-5">
                            <i class="fa-solid fa-paper-plane me-2"></i>Terbitkan Acara Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>