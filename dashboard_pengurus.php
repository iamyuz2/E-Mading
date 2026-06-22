<?php
session_start();
include 'koneksi.php';
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
    <title>Dashboard Admin | E-Mading Pro</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #10b981;
            --primary-soft: #ecfdf5;
            --secondary: #3b82f6;
            --warning: #f59e0b;
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
            margin: 0;
        }

        /* --- Sidebar Modern --- */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: var(--dark);
            position: fixed;
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
        .nav-link:hover, .nav-link.active {
            background: rgba(16, 185, 129, 0.1);
            color: var(--primary) !important;
        }

        /* --- Main Content --- */
        .main-wrapper {
            margin-left: 280px;
            padding: 45px 50px;
        }

        /* --- Welcome Header --- */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .user-profile {
            display: flex;
            align-items: center;
            background: white;
            padding: 8px 20px 8px 8px;
            border-radius: 50px;
            border: 1px solid var(--slate-100);
            box-shadow: var(--shadow-sm);
        }
        .avatar {
            width: 38px;
            height: 38px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 12px;
        }

        /* --- Action Cards (The 3 Fitur Utama) --- */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }
        .action-card {
            background: white;
            border-radius: var(--radius-20);
            padding: 30px;
            text-decoration: none;
            border: 1px solid #f1f5f9;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
            border-color: transparent;
        }
        .icon-box {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        
        /* Variants */
        .card-green .icon-box { background: #ecfdf5; color: #10b981; }
        .card-blue .icon-box { background: #eff6ff; color: #3b82f6; }
        .card-amber .icon-box { background: #fffbeb; color: #f59e0b; }
        
        .action-card h5 { font-weight: 700; color: var(--dark); margin-bottom: 8px; }
        .action-card p { font-size: 0.85rem; color: var(--slate-500); margin: 0; line-height: 1.5; }

        /* --- Stats Row --- */
        .stat-group {
            display: flex;
            gap: 24px;
            margin-bottom: 40px;
        }
        .stat-item {
            flex: 1;
            background: white;
            padding: 24px;
            border-radius: var(--radius-20);
            border: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-val { font-size: 1.8rem; font-weight: 800; color: var(--dark); }
        .stat-label { font-size: 0.75rem; font-weight: 700; color: var(--slate-500); text-transform: uppercase; letter-spacing: 0.5px; }

        /* --- Data Table --- */
        .table-card {
            background: white;
            border-radius: var(--radius-20);
            border: 1px solid #f1f5f9;
            padding: 30px;
        }
        .table-title { font-weight: 800; font-size: 1.1rem; margin-bottom: 25px; }
        .table thead th {
            background: #f8fafc;
            border: none;
            color: var(--slate-500);
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 15px 20px;
            font-weight: 700;
        }
        .table tbody td { padding: 18px 20px; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
        .category-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 800;
        }

        @media (max-width: 1200px) {
            .action-grid { grid-template-columns: 1fr; }
            .sidebar { width: 80px; padding: 30px 15px; }
            .sidebar-logo span, .nav-link span { display: none; }
            .main-wrapper { margin-left: 80px; }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="dashboard_pengurus.php" class="sidebar-logo">
            <i class="fa-solid fa-bolt-lightning me-3"></i>
            <span>EMS PRO</span>
        </a>
        <ul class="nav-menu">
            <li class="nav-item"><a href="dashboard_pengurus.php" class="nav-link active"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
            <li class="nav-item"><a href="buat_acara.php" class="nav-link"><i class="fa-solid fa-calendar"></i> <span>Manajemen</span></a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link mt-5 text-danger"><i class="fa-solid fa-power-off"></i> <span>Keluar</span></a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-wrapper">
        
        <!-- Header -->
        <header class="header-section">
            <div>
                <h2 class="fw-800 mb-1">Pusat Kendali Mading</h2>
                <p class="text-muted small">Kelola informasi dan acara sekolah dengan satu klik.</p>
            </div>
            <div class="user-profile">
                <div class="avatar"><?php echo substr($_SESSION['nama'], 0, 1); ?></div>
                <div class="me-3">
                    <div class="fw-bold small lh-1"><?php echo $_SESSION['nama']; ?></div>
                    <span class="text-muted" style="font-size: 10px;">Pengurus Utama</span>
                </div>
            </div>
        </header>

        <!-- Quick Action Cards (3 Fitur Utama) -->
        <section class="action-grid">
            <a href="buat_acara.php" class="action-card card-green">
                <div class="icon-box"><i class="fa-solid fa-plus"></i></div>
                <h5>Tambah Acara</h5>
                <p>Buat kompetisi, agenda kelas, atau kegiatan ekstrakurikuler baru.</p>
            </a>
            
            <a href="buat_pengumuman.php" class="action-card card-blue">
                <div class="icon-box"><i class="fa-solid fa-bullhorn"></i></div>
                <h5>Pengumuman Penting</h5>
                <p>Publikasikan informasi resmi sekolah tanpa fitur pendaftaran.</p>
            </a>
            
            <!-- Fitur Kirim Notifikasi Terkait Acara -->
            <a href="#" class="action-card card-amber" data-bs-toggle="modal" data-bs-target="#modalNotif">
                <div class="icon-box"><i class="fa-solid fa-bell"></i></div>
                <h5>Kirim Notifikasi Terkait Acara</h5>
                <p>Kirim pesan peringatan atau informasi darurat langsung ke siswa.</p>
            </a>
        </section>

        <!-- Stats Overview -->
        <section class="stat-group">
            <div class="stat-item">
                <div>
                    <div class="stat-label">Total Publikasi</div>
                    <div class="stat-val"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM events")); ?></div>
                </div>
                <i class="fa-solid fa-chart-line text-muted opacity-25 fs-1"></i>
            </div>
            <div class="stat-item">
                <div>
                    <div class="stat-label">Peserta Terdaftar</div>
                    <div class="stat-val"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM registrations")); ?></div>
                </div>
                <i class="fa-solid fa-user-check text-muted opacity-25 fs-1"></i>
            </div>
        </section>

        <!-- Table Content -->
        <section class="table-card shadow-sm">
            <h5 class="table-title">Daftar Konten Terkini</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Judul Konten</th>
                            <th>Kategori</th>
                            <th>Waktu Posting</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q = mysqli_query($conn, "SELECT * FROM events ORDER BY id DESC");
                        while($row = mysqli_fetch_assoc($q)):
                            $color = ($row['kategori'] == 'Pengumuman') ? 'bg-primary text-white' : 'bg-success text-white';
                        ?>
                        <tr>
                            <td><img src="uploads/<?php echo $row['poster']; ?>" width="45" height="45" class="rounded-3 shadow-sm object-fit-cover"></td>
                            <td><span class="fw-bold d-block"><?php echo $row['judul_acara']; ?></span></td>
                            <td><span class="category-badge <?php echo $color; ?>"><?php echo $row['kategori']; ?></span></td>
                            <td class="small text-muted"><?php echo date('d M, H:i', strtotime($row['tanggal_waktu'])); ?></td>
                            <td class="text-center">
                                <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-dark border">Kelola</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- Modal Notifikasi Terkait Acara -->
    <div class="modal fade" id="modalNotif" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                <div class="modal-header border-0 px-4 pt-4">
                    <h5 class="fw-800"><i class="fa-solid fa-bell text-warning me-2"></i> Kirim Notifikasi Terkait Acara</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="proses_notifikasi.php" method="POST">
                    <div class="modal-body px-4">
                        <p class="text-muted small mb-4">Gunakan fitur ini untuk memberi peringatan darurat atau perubahan jadwal mendadak kepada siswa.</p>
                        <div class="mb-3">
                            <label class="fw-bold small mb-2">Subjek Notifikasi</label>
                            <input type="text" name="judul" class="form-control rounded-3 py-2" placeholder="Contoh: Perubahan Lokasi Lomba" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small mb-2">Pesan Notifikasi</label>
                            <textarea name="pesan" class="form-control rounded-3" rows="4" placeholder="Ketik pesan darurat di sini..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="submit" class="btn btn-warning w-100 rounded-3 fw-bold py-3">Siarkan Notifikasi Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>