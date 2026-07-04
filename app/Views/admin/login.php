<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Sigap</title>
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #b71c1c 0%, #7f0000 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }
        /* Dot Pattern Overlay */
        .dot-pattern {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1.5px, transparent 1.5px);
            background-size: 24px 24px;
            opacity: 0.7;
            pointer-events: none;
            z-index: 1;
        }
        /* Glow Blobs */
        .glow-blob-1 {
            position: absolute;
            top: -20%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.22) 0%, rgba(245, 158, 11, 0) 70%);
            filter: blur(60px);
            pointer-events: none;
            border-radius: 50%;
            z-index: 1;
        }
        .glow-blob-2 {
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.15) 0%, rgba(236, 72, 153, 0) 70%);
            filter: blur(60px);
            pointer-events: none;
            border-radius: 50%;
            z-index: 1;
        }
        
        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
        }
        
        /* Glassmorphism Card */
        .login-card {
            background: rgba(15, 23, 42, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            padding: 40px;
        }
        
        .brand-section {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo-icon-wrapper {
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }
        .logo-icon-wrapper i {
            color: #ffffff;
            font-size: 24px;
        }
        .brand-title {
            font-weight: 800;
            font-size: 24px;
            letter-spacing: 0.05em;
            color: #ffffff;
            margin-bottom: 4px;
        }
        .brand-subtitle {
            color: rgba(243, 244, 246, 0.7);
            font-size: 13.5px;
        }
        
        /* Alert Box */
        .alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 13.5px;
            font-weight: 500;
            line-height: 1.5;
            border: 1px solid transparent;
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.18);
            border-color: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.18);
            border-color: rgba(16, 185, 129, 0.3);
            color: #a7f3d0;
        }
        
        /* Form inputs styling */
        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            color: rgba(243, 244, 246, 0.95);
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 8px;
            letter-spacing: 0.02em;
        }
        .input-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .form-control {
            width: 100%;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-radius: 12px;
            padding: 12px 16px 12px 44px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }
        .form-control:focus {
            border-color: #ef4444;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.25);
        }
        .form-control:focus + .input-icon {
            color: #ef4444;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }
        
        /* Button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 14px;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.35);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5);
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: rgba(243, 244, 246, 0.6);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .back-link:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Decorative overlays -->
    <div class="dot-pattern"></div>
    <div class="glow-blob-1"></div>
    <div class="glow-blob-2"></div>

    <div class="login-container">
        <div class="login-card">
            
            <!-- Brand Section -->
            <div class="brand-section">
                <a href="<?= base_url() ?>" style="text-decoration: none;">
                    <div class="logo-icon-wrapper">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                </a>
                <h1 class="brand-title">SIGAP</h1>
                <p class="brand-subtitle">Portal Administrator & PIC Unit Layanan</p>
            </div>

            <!-- Flashdata alerts -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div><?= session()->getFlashdata('error') ?></div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <div><?= session()->getFlashdata('success') ?></div>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?= base_url('admin/login') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label class="form-label" for="email">Alamat Email</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" class="form-control" placeholder="nama@instansi.go.id" value="<?= old('email') ?>" required autocomplete="email">
                        <i class="fa-solid fa-envelope input-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                        <i class="fa-solid fa-lock input-icon"></i>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">
                    Masuk Dashboard <i class="fa-solid fa-right-to-bracket"></i>
                </button>
            </form>
        </div>

        <!-- Back Link -->
        <div style="text-align: center; margin-top: 24px;">
            <a href="<?= base_url() ?>" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>
