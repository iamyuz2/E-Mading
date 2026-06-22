<?php
session_start();
include 'koneksi.php';

// Proteksi halaman: pastikan hanya siswa yang bisa masuk
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'siswa') { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Acara | E-Mading</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --primary: #10b981; 
            --sidebar-w: 280px; 
            --dark: #0f172a;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background: #f8fafc; 
            margin: 0; 
        }
        
        .sidebar { 
            width: var(--sidebar-w); 
            height: 100vh; 
            background: white; 
            position: fixed; 
            border-right: 1px solid #e2e8f0; 
            padding: 30px 24px; 
            z-index: 1000;
        }
        .main { 
            margin-left: var(--sidebar-w); 
            padding: 40px 60px; 
        }
        .nav-link { 
            display: flex; 
            align-items: center; 
            padding: 14px 18px; 
            color: #64748b; 
            font-weight: 600; 
            text-decoration: none; 
            border-radius: 14px; 
            margin-bottom: 8px; 
            transition: 0.3s; 
        }
        .nav-link.active, .nav-link:hover { 
            background: var(--primary); 
            color: white !important; 
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2); 
        }
        .nav-link i { margin-right: 15px; }

        .history-card { 
            background: white; 
            border-radius: 20px; 
            border: 1px solid #f1f5f9; 
            padding: 20px; 
            transition: 0.3s; 
            display: flex; 
            align-items: center; 
            gap: 25px; 
            margin-bottom: 20px; 
        }
        .history-card:hover { 
            box-shadow: 0 15px 30px -10px rgba(0,0,0,0.08); 
            transform: translateY(-5px); 
            border-color: var(--primary); 
        }
        .history-img { 
            width: 140px; 
            height: 140px; 
            border-radius: 16px; 
            object-fit: cover; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .status-badge { 
            padding: 8px 16px; 
            border-radius: 10px; 
            font-size: 0.8rem; 
            font-weight: 800; 
            background: #ecfdf5; 
            color: #10b981; 
            display: inline-flex;
            align-items: center;
        }
        
        @media (max-width: 992px) {
            .sidebar { display: none; }
            .main { margin-left: 0; padding: 20px; }
            .history-card { flex-direction: column; text-align: center; }
            .history-img { width: 100%; height: 200px; }
        }
    </style>
</head>
<body>
    
    <div class="sidebar">
        <h4 class="fw-800 text-primary mb-5"><i class="fa-solid fa-bolt me-2"></i>EMS PRO</h4>
        <nav>
            <a href="dashboard_siswa.php" class="nav-link"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="pengumuman_siswa.php" class="nav-link"><i class="fa-solid fa-bullhorn"></i> Pengumuman</a>
            <a href="riwayat_acara.php" class="nav-link active"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Acara</a>
            <a href="logout.php" class="nav-link text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </div>

    <div class="main">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-800 mb-1">Riwayat Partisipasi</h3>
                <p class="text-muted mb-0">Daftar semua acara dan kegiatan yang pernah kamu ikuti.</p>
            </div>
            <div class="bg-white p-2 rounded-pill border pe-4 shadow-sm d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama']); ?>&background=10b981&color=fff" class="rounded-circle me-3" width="40">
                <div>
                    <p class="mb-0 fw-bold small lh-1"><?php echo $_SESSION['nama']; ?></p>
                    <p class="mb-0 text-muted" style="font-size: 11px;">Siswa</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 col-xl-9">
                <?php
                $query = "SELECT e.* FROM events e 
                          JOIN registrations r ON e.id = r.id_acara 
                          WHERE r.id_siswa = $user_id 
                          ORDER BY r.id DESC"; 
                $result = mysqli_query($conn, $query);
                
                if(mysqli_num_rows($result) > 0):
                    while($row = mysqli_fetch_assoc($result)):
                ?>
                
                <div class="history-card">
                    <img src="uploads/<?php echo $row['poster']; ?>" class="history-img" alt="Poster Acara">
                    
                    <div class="flex-grow-1 d-flex flex-column justify-content-between py-2">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-light border text-primary px-3 py-2 rounded-pill shadow-sm">
                                <?php echo $row['kategori']; ?>
                            </span>
                            <span class="status-badge"><i class="fa-solid fa-circle-check me-2"></i> Terdaftar</span>
                        </div>
                        
                        <div>
                            <h4 class="fw-bold text-dark mb-2"><?php echo $row['judul_acara']; ?></h4>
                            <p class="text-muted small mb-0 fw-500">
                                <i class="fa-solid fa-calendar-day me-2"></i> <?php echo date('d F Y', strtotime($row['tanggal_waktu'])); ?> 
                                <span class="mx-2">|</span> 
                                <i class="fa-solid fa-clock me-2"></i> <?php echo date('H:i', strtotime($row['tanggal_waktu'])); ?> WIB
                            </p>
                        </div>
                        
                        <div class="mt-3 pt-3 border-top">
                            <button class="btn btn-outline-success btn-sm rounded-pill px-4 fw-bold disabled">
                                Tiket Digital Diterbitkan
                            </button>
                        </div>
                    </div>
                </div>

                <?php 
                    endwhile;
                else: 
                ?>
                
                <div class="text-center py-5 bg-white rounded-4 border shadow-sm mt-3" style="min-height: 400px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                        <i class="fa-solid fa-folder-open text-muted fs-1"></i>
                    </div>
                    <h4 class="fw-800 text-dark mb-2">Belum ada riwayat acara</h4>
                    <p class="text-muted mb-4" style="max-width: 400px;">Sepertinya kamu belum pernah mendaftar pada kegiatan atau lomba apapun di sekolah.</p>
                    <a href="dashboard_siswa.php" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> Eksplorasi Acara Sekarang
                    </a>
                </div>
                
                <?php endif; ?>
            </div>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>