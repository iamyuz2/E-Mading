<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: pastikan hanya siswa yang bisa masuk
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') { 
    header("Location: login.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Pengumuman | E-Mading</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --primary: #10b981; 
            --sidebar-w: 280px; 
            --dark: #0f172a;
            --warning: #f59e0b;
        }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; }
        
        /* Sidebar Styling */
        .sidebar { 
            width: var(--sidebar-w); height: 100vh; background: white; 
            position: fixed; border-right: 1px solid #e2e8f0; 
            padding: 30px 24px; z-index: 1000;
        }
        .main { margin-left: var(--sidebar-w); padding: 40px 60px; }
        .nav-link-sidebar { 
            display: flex; align-items: center; padding: 14px 18px; 
            color: #64748b; font-weight: 600; text-decoration: none; 
            border-radius: 14px; margin-bottom: 8px; transition: 0.3s; 
        }
        .nav-link-sidebar.active, .nav-link-sidebar:hover { 
            background: var(--primary); color: white !important; 
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2); 
        }
        .nav-link-sidebar i { margin-right: 15px; }

        /* Custom Tabs Styling */
        .nav-pills .nav-link {
            color: #64748b; font-weight: 600; border-radius: 12px;
            padding: 12px 24px; margin-right: 10px; transition: 0.3s;
        }
        .nav-pills .nav-link.active { background-color: var(--primary); color: white; }
        .nav-pills .nav-link:hover:not(.active) { background-color: #ecfdf5; color: var(--primary); }

        /* Card Styling for Official Announcements */
        .announcement-card {
            background: white; border-radius: 16px; border: 1px solid #f1f5f9;
            padding: 24px; margin-bottom: 20px; transition: 0.3s;
            display: flex; gap: 20px;
        }
        .announcement-card:hover { box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); transform: translateY(-3px); border-color: var(--primary); }
        .announcement-img { width: 100px; height: 100px; object-fit: cover; border-radius: 12px; }

        /* Card Styling for Emergency Notifications */
        .notif-card {
            background: white; border-radius: 16px; border: 1px solid #f1f5f9;
            border-left: 5px solid var(--warning); padding: 24px; margin-bottom: 20px;
            transition: 0.3s;
        }
        .notif-card:hover { box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.15); transform: translateX(5px); }
        .notif-icon {
            width: 45px; height: 45px; background: #fffbeb; color: var(--warning);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
        }

        @media (max-width: 992px) {
            .sidebar { display: none; }
            .main { margin-left: 0; padding: 20px; }
            .announcement-card { flex-direction: column; }
            .announcement-img { width: 100%; height: 180px; }
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="fw-800 text-primary mb-5"><i class="fa-solid fa-bolt me-2"></i>EMS PRO</h4>
        <nav>
            <a href="dashboard_siswa.php" class="nav-link-sidebar"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="pengumuman_siswa.php" class="nav-link-sidebar active"><i class="fa-solid fa-bullhorn"></i> Pengumuman</a>
            <a href="riwayat_acara.php" class="nav-link-sidebar"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Acara</a>
            <a href="logout.php" class="nav-link-sidebar text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-800 mb-1">Pusat Informasi & Pengumuman</h3>
                <p class="text-muted mb-0">Dapatkan berita resmi sekolah dan peringatan darurat secara real-time.</p>
            </div>
            <div class="bg-white p-2 rounded-pill border pe-4 shadow-sm d-flex align-items-center d-none d-md-flex">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama']); ?>&background=10b981&color=fff" class="rounded-circle me-3" width="40">
                <div>
                    <p class="mb-0 fw-bold small lh-1"><?php echo $_SESSION['nama']; ?></p>
                    <p class="mb-0 text-muted" style="font-size: 11px;">Siswa</p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <ul class="nav nav-pills mb-4 border-bottom pb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#pills-resmi" type="button" role="tab">
                    <i class="fa-solid fa-newspaper me-2"></i> Pengumuman Resmi
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-notif" type="button" role="tab">
                    <i class="fa-solid fa-bell me-2"></i> Notifikasi Darurat
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="pills-tabContent">
            
            <!-- TAB 1: PENGUMUMAN RESMI (Dari tabel events kategori Pengumuman) -->
            <div class="tab-pane fade show active" id="pills-resmi" role="tabpanel">
                <div class="row">
                    <div class="col-xl-9">
                        <?php
                        $query_pengumuman = "SELECT * FROM events WHERE kategori = 'Pengumuman' ORDER BY id DESC";
                        $result_pengumuman = mysqli_query($conn, $query_pengumuman);
                        
                        if(mysqli_num_rows($result_pengumuman) > 0):
                            while($p = mysqli_fetch_assoc($result_pengumuman)):
                        ?>
                        <div class="announcement-card">
                            <img src="uploads/<?php echo $p['poster']; ?>" class="announcement-img" alt="Poster">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold text-dark mb-0"><?php echo $p['judul_acara']; ?></h5>
                                    <span class="badge bg-primary rounded-pill px-3 py-2 fw-500">Official</span>
                                </div>
                                <p class="text-muted small mb-3"><i class="fa-regular fa-clock me-1"></i> Dipublikasikan pada: <?php echo date('d M Y, H:i', strtotime($p['tanggal_waktu'])); ?> WIB</p>
                                <p class="text-secondary small mb-0" style="line-height: 1.6;"><?php echo nl2br(substr($p['deskripsi'] ?? 'Silakan klik untuk melihat detail pengumuman.', 0, 150)); ?>...</p>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-solid fa-folder-open fs-1 mb-3 opacity-50"></i>
                            <p>Belum ada pengumuman resmi dari sekolah.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- TAB 2: NOTIFIKASI DARURAT (Dari tabel notifications) -->
            <div class="tab-pane fade" id="pills-notif" role="tabpanel">
                <div class="row">
                    <div class="col-xl-9">
                        <?php
                        // Memastikan tabel exist sebelum query agar tidak error
                        $check_table = mysqli_query($conn, "SHOW TABLES LIKE 'notifications'");
                        if(mysqli_num_rows($check_table) > 0) {
                            $query_notif = "SELECT n.*, u.nama_lengkap as nama_admin FROM notifications n LEFT JOIN users u ON n.id_pengurus = u.id ORDER BY n.id DESC";
                            $result_notif = mysqli_query($conn, $query_notif);
                            
                            if(mysqli_num_rows($result_notif) > 0):
                                while($n = mysqli_fetch_assoc($result_notif)):
                        ?>
                        <div class="notif-card">
                            <div class="d-flex gap-3">
                                <div class="notif-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        <h6 class="fw-bold text-dark mb-0 me-3"><?php echo $n['judul']; ?></h6>
                                        <span class="text-muted" style="font-size: 11px;"><i class="fa-solid fa-clock-rotate-left me-1"></i> <?php echo date('d M, H:i', strtotime($n['created_at'])); ?></span>
                                    </div>
                                    <p class="text-dark small mb-2"><?php echo nl2br($n['pesan']); ?></p>
                                    <p class="text-muted m-0" style="font-size: 10px;">Broadcast oleh: <?php echo $n['nama_admin'] ?? 'Admin Sistem'; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php 
                                endwhile;
                            else:
                        ?>
                        <div class="text-center py-5 text-muted">
                            <i class="fa-regular fa-bell-slash fs-1 mb-3 opacity-50"></i>
                            <p>Tidak ada notifikasi darurat saat ini.</p>
                        </div>
                        <?php 
                            endif;
                        } else {
                            echo "<div class='alert alert-warning'>Tabel notifications belum terbuat di database.</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
