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
    <title>Dashboard Siswa | E-Mading</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #10b981; --sidebar-w: 280px; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; }
        
        .sidebar { 
            width: var(--sidebar-w); 
            height: 100vh; 
            background: white; 
            position: fixed; 
            border-right: 1px solid #e2e8f0; 
            padding: 30px 24px; 
            z-index: 1000;
        }
        .main { margin-left: var(--sidebar-w); padding: 40px 60px; }
        
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

        .event-card { 
            background: white; 
            border-radius: 20px; 
            border: 1px solid #f1f5f9; 
            overflow: hidden; 
            transition: 0.4s; 
            height: 100%;
        }
        .event-card:hover { transform: translateY(-10px); border-color: var(--primary); }
        
        .x-small { font-size: 11px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="fw-800 text-primary mb-5"><i class="fa-solid fa-bolt me-2"></i>EMS PRO</h4>
        <nav>
            <a href="dashboard_siswa.php" class="nav-link active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="pengumuman_siswa.php" class="nav-link"><i class="fa-solid fa-bullhorn"></i> Pengumuman</a>
            <a href="riwayat_acara.php" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Acara</a>
            <a href="logout.php" class="nav-link text-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </nav>
    </div>

    <div class="main">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h3 class="fw-800 mb-0">Eksplorasi Acara Sekolah</h3>
                <p class="text-muted">Temukan kompetisi, agenda kelas, dan kegiatan ekskul terbaru.</p>
            </div>
            <div class="bg-white p-2 rounded-pill border pe-4 shadow-sm d-flex align-items-center">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama']); ?>&background=10b981&color=fff" class="rounded-circle me-3" width="40">
                <div>
                    <p class="mb-0 fw-bold small lh-1"><?php echo $_SESSION['nama']; ?></p>
                    <p class="mb-0 text-muted x-small">Siswa</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <?php
            // Filter: Hanya mengambil acara (Lomba, Ekskul, Agenda) dan mengecualikan Pengumuman
            $q = mysqli_query($conn, "SELECT * FROM events WHERE kategori != 'Pengumuman' ORDER BY id DESC");
            
            if(mysqli_num_rows($q) > 0):
                while($ev = mysqli_fetch_assoc($q)):
                    $reg = mysqli_query($conn, "SELECT id FROM registrations WHERE id_siswa=$user_id AND id_acara=".$ev['id']);
                    $exists = mysqli_num_rows($reg) > 0;
            ?>
            <div class="col-md-4">
                <div class="event-card">
                    <img src="uploads/<?php echo $ev['poster']; ?>" class="w-100" style="height:200px; object-fit:cover;">
                    <div class="p-4">
                        <span class="badge bg-light text-primary mb-2"><?php echo $ev['kategori']; ?></span>
                        <h6 class="fw-800 text-dark mb-3"><?php echo $ev['judul_acara']; ?></h6>
                        <div class="small text-muted mb-4">
                            <i class="fa-regular fa-calendar me-2"></i><?php echo date('d M Y', strtotime($ev['tanggal_waktu'])); ?>
                        </div>
                        <?php if($exists): ?>
                            <button class="btn btn-secondary w-100 rounded-pill" disabled><i class="fa-solid fa-check-circle me-2"></i>Terdaftar</button>
                        <?php else: ?>
                            <a href="daftar_acara.php?id=<?php echo $ev['id']; ?>" class="btn btn-success w-100 rounded-pill">Lihat & Daftar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            else:
            ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada acara mendatang yang tersedia.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>