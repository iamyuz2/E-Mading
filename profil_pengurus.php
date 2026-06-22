<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: pastikan hanya pengurus yang bisa masuk
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pengurus') { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];

// Proses saat pengurus mengunggah foto baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto_profil'])) {
    $filename = $_FILES['foto_profil']['name'];
    $tmp_name = $_FILES['foto_profil']['tmp_name'];
    
    if ($filename != '') {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        
        if (in_array($ext, $allowed_ext)) {
            // Membuat nama file unik
            $new_name = "profil_" . $user_id . "_" . time() . "." . $ext;
            
            // Path absolut untuk mencegah error folder tidak ditemukan
            $target_dir = __DIR__ . '/uploads/';
            if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
            
            $target_file = $target_dir . $new_name;
            
            // Memindahkan gambar dan mengupdate database
            if (move_uploaded_file($tmp_name, $target_file)) {
                mysqli_query($conn, "UPDATE users SET foto_profil = '$new_name' WHERE id = $user_id");
                echo "<script>alert('Foto profil berhasil diperbarui!'); window.location='profil_pengurus.php';</script>";
            } else {
                echo "<script>alert('Gagal mengunggah foto. Pastikan folder uploads tersedia.');</script>";
            }
        } else {
            echo "<script>alert('Format file ditolak! Hanya boleh JPG, JPEG, atau PNG.');</script>";
        }
    }
}

// Mengambil data lengkap pengurus dari database
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($query_user);

// Cek ketersediaan foto profil (gunakan inisial jika belum ada)
$foto_url = "https://ui-avatars.com/api/?name=" . urlencode($user_data['nama_lengkap']) . "&background=10b981&color=fff&size=200";
if ($user_data['foto_profil'] != NULL && file_exists('uploads/' . $user_data['foto_profil'])) {
    $foto_url = 'uploads/' . $user_data['foto_profil'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengurus | E-Mading Pro</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- CSS RESET (Memperbaiki ruang putih di atas layar) --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-soft: #ecfdf5;
            --dark: #0f172a;
            --slate-500: #64748b;
            --slate-100: #f1f5f9;
            --radius-20: 20px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; 
            color: var(--dark); 
        }
        
        /* --- Sidebar Modern --- */
        .sidebar { 
            width: 280px; 
            height: 100vh; 
            background: var(--dark); 
            position: fixed; 
            top: 0;   /* Dikunci ke atas */
            left: 0;  /* Dikunci ke kiri */
            padding: 30px 24px; 
            color: white; 
            z-index: 1000; 
        }
        .sidebar-logo { 
            font-size: 1.6rem; 
            font-weight: 800; 
            color: var(--primary); 
            text-decoration: none; 
            display: flex; 
            align-items: center; 
            margin-bottom: 45px; 
        }
        .nav-menu { list-style: none; padding: 0; }
        .nav-item { margin-bottom: 8px; }
        .nav-link { 
            display: flex; 
            align-items: center; 
            padding: 12px 16px; 
            color: #94a3b8; 
            text-decoration: none; 
            border-radius: 12px; 
            font-weight: 600; 
            transition: var(--transition); 
        }
        .nav-link i { margin-right: 14px; font-size: 1.1rem; }
        .nav-link:hover { 
            background: rgba(16, 185, 129, 0.1); 
            color: var(--primary) !important; 
        }

        /* --- Main Content --- */
        .main-wrapper { 
            margin-left: 280px; 
            padding: 45px 50px; 
            min-height: 100vh; 
        }
        
        /* --- Card Styles --- */
        .profile-card { 
            background: white; 
            border-radius: var(--radius-20); 
            border: 1px solid #f1f5f9; 
            padding: 40px; 
            box-shadow: var(--shadow-sm); 
            height: 100%; 
            transition: var(--transition);
        }
        .profile-card:hover {
            box-shadow: var(--shadow-md);
            border-color: transparent;
        }
        
        /* --- Image Upload Area --- */
        .avatar-preview-container { 
            position: relative; 
            width: 180px; 
            height: 180px; 
            margin: 0 auto 25px; 
            border-radius: 50%; 
            padding: 5px; 
            background: linear-gradient(135deg, var(--primary), #3b82f6); 
            box-shadow: var(--shadow-md);
        }
        .avatar-preview { 
            width: 100%; 
            height: 100%; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 4px solid white; 
            background: white; 
        }
        
        .upload-btn-wrapper { 
            position: relative; 
            overflow: hidden; 
            display: inline-block; 
            width: 100%; 
            text-align: center; 
        }
        .upload-btn-wrapper input[type=file] { 
            font-size: 100px; 
            position: absolute; 
            left: 0; 
            top: 0; 
            opacity: 0; 
            cursor: pointer; 
            height: 100%; 
        }
        .btn-upload { 
            background: var(--primary-soft); 
            color: var(--primary); 
            border: 1px dashed var(--primary); 
            padding: 12px 20px; 
            border-radius: 12px; 
            font-weight: 600; 
            width: 100%; 
            transition: var(--transition); 
            cursor: pointer; 
        }
        .upload-btn-wrapper:hover .btn-upload { 
            background: var(--primary); 
            color: white; 
        }

        /* --- Data List --- */
        .data-label { 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            color: var(--slate-500); 
            font-weight: 700; 
            margin-bottom: 6px; 
        }
        .data-value { 
            font-size: 1.1rem; 
            font-weight: 600; 
            color: var(--dark); 
            margin-bottom: 25px; 
            padding-bottom: 15px; 
            border-bottom: 1px solid #f8fafc; 
        }
        
        /* --- Button Submit --- */
        .btn-save { 
            background: var(--primary); 
            color: white; 
            font-weight: 700; 
            padding: 14px 24px; 
            border-radius: 12px; 
            border: none; 
            width: 100%; 
            transition: var(--transition); 
        }
        .btn-save:hover { 
            background: var(--primary-dark); 
            transform: translateY(-2px); 
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3); 
        }

        /* Responsivitas Layar Kecil */
        @media (max-width: 1200px) {
            .sidebar { width: 80px; padding: 30px 15px; }
            .sidebar-logo span, .nav-link span { display: none; }
            .main-wrapper { margin-left: 80px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar Menu -->
    <aside class="sidebar">
        <a href="dashboard_pengurus.php" class="sidebar-logo">
            <i class="fa-solid fa-bolt-lightning me-3"></i><span>EMS PRO</span>
        </a>
        <ul class="nav-menu">
            <li class="nav-item"><a href="dashboard_pengurus.php" class="nav-link"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
            <li class="nav-item"><a href="buat_acara.php" class="nav-link"><i class="fa-solid fa-calendar"></i> <span>Manajemen</span></a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link mt-5 text-danger"><i class="fa-solid fa-power-off"></i> <span>Keluar</span></a></li>
        </ul>
    </aside>

    <!-- Konten Utama -->
    <main class="main-wrapper">
        
        <!-- Header Halaman -->
        <header class="mb-5 d-flex align-items-center">
            <a href="dashboard_pengurus.php" class="btn btn-light rounded-circle shadow-sm me-4" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-arrow-left text-dark"></i>
            </a>
            <div>
                <h2 class="fw-800 mb-1">Profil Admin</h2>
                <p class="text-muted small mb-0">Kelola informasi identitas dan ubah foto profil Anda di sini.</p>
            </div>
        </header>

        <div class="row g-4">
            
            <!-- Kolom Kiri: Ubah Foto -->
            <div class="col-lg-4">
                <div class="profile-card text-center">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <!-- Preview Foto -->
                        <div class="avatar-preview-container">
                            <img src="<?php echo $foto_url; ?>" alt="Foto Profil" class="avatar-preview" id="img-preview">
                        </div>
                        
                        <h5 class="fw-bold mb-1 text-dark"><?php echo $user_data['nama_lengkap']; ?></h5>
                        <p class="text-primary small fw-bold mb-4 px-3 py-1 bg-success bg-opacity-10 d-inline-block rounded-pill">
                            <i class="fa-solid fa-shield-check me-1"></i> Admin Terverifikasi
                        </p>
                        
                        <!-- Tombol Pilih Gambar -->
                        <div class="upload-btn-wrapper mb-3">
                            <button type="button" class="btn-upload"><i class="fa-solid fa-camera me-2"></i> Pilih Foto Baru</button>
                            <!-- Script onChange memicu preview langsung -->
                            <input type="file" name="foto_profil" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)" required>
                        </div>
                        <p class="small text-muted mb-4">Format: JPG/PNG. Disarankan rasio kotak (1:1).</p>
                        
                        <button type="submit" class="btn-save"><i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Foto Profil</button>
                    </form>
                </div>
            </div>

            <!-- Kolom Kanan: Rincian Biodata Lengkap -->
            <div class="col-lg-8">
                <div class="profile-card">
                    <h4 class="fw-800 mb-4 pb-3 border-bottom"><i class="fa-solid fa-address-card text-primary me-2"></i> Detail Biodata Sekolah</h4>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="data-label">Nama Lengkap</div>
                            <div class="data-value"><?php echo $user_data['nama_lengkap']; ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="data-label">Nomor Telepon / Akun ID</div>
                            <div class="data-value"><?php echo $user_data['no_telp']; ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="data-label">Kelas Saat Ini</div>
                            <div class="data-value"><?php echo $user_data['kelas']; ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="data-label">Program Keahlian (Jurusan)</div>
                            <div class="data-value"><?php echo $user_data['jurusan']; ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="data-label">Hak Akses Sistem</div>
                            <div class="data-value text-uppercase"><i class="fa-solid fa-key text-warning me-2"></i><?php echo $user_data['role']; ?> </div>
                        </div>
                        <div class="col-md-6">
                            <div class="data-label">Tanggal Bergabung</div>
                            <div class="data-value"><?php echo date('d F Y', strtotime($user_data['created_at'])); ?></div>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </main>

    <!-- Script Javascript untuk Live Preview Gambar (Real-time) -->
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('img-preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>