<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - E-Mading School</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: #10b981; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .login-container { display: flex; width: 1000px; max-width: 100%; background-color: white; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        .left-panel { flex: 1.2; background: linear-gradient(135deg, #10b981 0%, #064e3b 100%); color: white; padding: 60px; display: flex; flex-direction: column; justify-content: center; }
        .right-panel { flex: 1; padding: 60px; display: flex; flex-direction: column; align-items: center; }
        .logo { font-size: 42px; font-weight: 800; color: #10b981; text-decoration: none; margin-bottom: 10px; }
        .input-group { width: 100%; margin-bottom: 15px; }
        .input-group input { width: 100%; padding: 14px 18px; border: 1px solid #e5e7eb; border-radius: 12px; font-size: 14px; background-color: #f9fafb; outline: none; }
        .btn-login { width: 100%; padding: 14px; background-color: #10b981; color: white; border: none; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; margin-top: 10px;}
        .btn-login:hover { background-color: #064e3b; }
        @media (max-width: 992px) { .left-panel { display: none; } .login-container { width: 450px; } }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="left-panel">
            <h2 style="font-size: 28px; font-weight: 800;">Mari <span style="color:#fde047">berpartisipasi</span> mengembangkan acara sekolah bersama.</h2>
        </div>
        <div class="right-panel">
            <a href="index.php" class="logo">EMS</a>
            <h1 style="font-size: 20px; font-weight: 700; margin-bottom: 5px;">Selamat datang kembali</h1>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 25px;">Baru di E-Mading? <a href="register.php" style="color: #10b981; font-weight: 700; text-decoration: none;">Register disini</a></p>
            
            <form action="proses_login.php" method="POST" style="width: 100%;">
                <div class="input-group">
                    <input type="text" name="no_telp" placeholder="Nomor Telepon Terdaftar" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn-login">Masuk ke Akun</button>
            </form>
        </div>
    </div>
</body>
</html>