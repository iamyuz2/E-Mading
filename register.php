<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - E-Mading School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: #10b981; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .login-container { display: flex; width: 1000px; max-width: 100%; background-color: white; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        .left-panel { flex: 1.2; background: linear-gradient(135deg, #10b981 0%, #064e3b 100%); color: white; padding: 60px; display: flex; flex-direction: column; justify-content: center; }
        .right-panel { flex: 1; padding: 30px 60px; display: flex; flex-direction: column; align-items: center; }
        .logo { font-size: 32px; font-weight: 800; color: #10b981; text-decoration: none; margin-bottom: 5px; }
        .input-group { width: 100%; margin-bottom: 12px; }
        .input-group input, .input-group select { width: 100%; padding: 12px 18px; border: 1px solid #e5e7eb; border-radius: 12px; font-size: 14px; background-color: #f9fafb; outline: none; }
        .btn-login { width: 100%; padding: 14px; background-color: #10b981; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn-login:hover { background-color: #064e3b; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="left-panel">
            <h2 style="font-size: 28px; font-weight: 800; margin-bottom: 15px;">Bergabunglah di <br><span style="color:#fde047">E-Mading School</span></h2>
            <p>Daftarkan diri Anda untuk mulai berpartisipasi dalam berbagai acara sekolah.</p>
        </div>
        <div class="right-panel">
            <a href="index.php" class="logo">EMS</a>
            <h1 style="font-size: 18px; font-weight: 700; margin-bottom: 5px;">Daftar Akun Baru</h1>
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 20px;">Sudah punya akun? <a href="login.php" style="color: #10b981; font-weight: 700; text-decoration: none;">Login disini</a></p>
            
            <form action="proses_register.php" method="POST" style="width: 100%;">
                <div class="input-group"><input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required></div>
                <div class="input-group"><input type="text" name="kelas" placeholder="Kelas (Contoh: XI)" required></div>
                <div class="input-group"><input type="text" name="jurusan" placeholder="Jurusan" required></div>
                <div class="input-group"><input type="text" name="no_telp" placeholder="Nomor Telepon / WA" required></div>
                <div class="input-group">
                    <select name="role" required>
                        <option value="" disabled selected>-- Pilih Peran --</option>
                        <option value="siswa">Siswa</option>
                        <option value="pengurus">Pengurus Mading</option>
                    </select>
                </div>
                <div class="input-group"><input type="password" name="password" placeholder="Buat Password" required></div>
                <div class="input-group"><input type="password" name="confirm_password" placeholder="Konfirmasi Password" required></div>
                <button type="submit" class="btn-login">Daftar Sekarang</button>
            </form>
        </div>
    </div>
</body>
</html>