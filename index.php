<?php
session_start();
// Jika user sudah login, arahkan ke dashboard yang sesuai
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
    } elseif ($_SESSION['role'] === 'owner') {
        header('Location: owner/dashboard.php');
    } elseif ($_SESSION['role'] === 'tutor') {
        header('Location: tutor/dashboard.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Bimbel - Selamat Datang</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            border: none;
            max-width: 900px;
            width: 95%;
        }
        .left-panel {
            background-color: #4e73df;
            background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .right-panel {
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .role-card {
            border: 2px solid #e3e6f0;
            border-radius: 15px;
            padding: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            color: #5a5c69;
        }
        .role-card:hover {
            border-color: #4e73df;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.15);
            color: #4e73df;
        }
        .role-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: #858796;
            transition: color 0.3s ease;
        }
        .role-card:hover .role-icon {
            color: #4e73df;
        }
        .role-title {
            font-weight: 600;
            font-size: 1.1rem;
        }
        .welcome-text {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .brand-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: rgba(255,255,255,0.9);
        }
    </style>
</head>
<body>

    <div class="card hero-card">
        <div class="row g-0">
            <!-- Left Side: Branding -->
            <div class="col-md-5 left-panel">
                <i class="fas fa-graduation-cap brand-icon"></i>
                <h1 class="welcome-text">Portal Bimbel</h1>
                <p class="lead mb-4">Sistem Informasi Manajemen Bimbingan Belajar</p>
                <p class="small text-white-50">Kelola siswa, tentor, jadwal, dan keuangan dengan mudah dan efisien dalam satu platform terpadu.</p>
            </div>

            <!-- Right Side: Login Options -->
            <div class="col-md-7 right-panel">
                <h3 class="fw-bold mb-2 text-dark">Selamat Datang 👋</h3>
                <p class="text-muted mb-4">Silakan pilih akses login Anda:</p>

                <div class="row g-3">
                    <div class="col-4">
                        <a href="admin/index.php" class="d-block role-card">
                            <i class="fas fa-user-shield role-icon"></i>
                            <div class="role-title">Admin</div>
                            <small class="text-muted">Admin</small>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="tutor/index.php" class="d-block role-card">
                            <i class="fas fa-chalkboard-teacher role-icon"></i>
                            <div class="role-title">Tentor</div>
                            <small class="text-muted">Pengajar</small>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="owner/index.php" class="d-block role-card">
                            <i class="fas fa-chart-line role-icon"></i>
                            <div class="role-title">Owner</div>
                            <small class="text-muted">Pemilik</small>
                        </a>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <p class="text-muted small mb-0">&copy; <?= date('Y') ?> Bimbel App. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>