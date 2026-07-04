<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — Sigap</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style (AdminLTE) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Base styling overrides -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Force CSS Variables to Light Theme */
        :root {
            --bg-primary: #f4f6f9;
            --bg-secondary: #ffffff;
            --bg-tertiary: #e9ecef;
            --text-primary: #212529;
            --text-secondary: #495057;
            --text-muted: #6c757d;
            --border-color: #dee2e6;
            --card-bg: #ffffff;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --primary: #007bff;
            --primary-hover: #0056b3;
            --primary-light: rgba(0, 123, 255, 0.15);
            --secondary: #17a2b8;
        }

        /* Reset global background modifications from main.css */
        body {
            font-family: 'Inter', sans-serif !important;
            background-color: #f4f6f9 !important;
            color: #212529 !important;
        }
        body::before, body::after {
            display: none !important;
        }

        /* AdminLTE Structural Customizations */
        .content-wrapper {
            background-color: #f4f6f9 !important;
            padding-bottom: 40px;
        }
        .main-header {
            border-bottom: 1px solid #dee2e6 !important;
        }

        /* Stat Cards Light theme overrides */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #ffffff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 12px !important;
            padding: 20px !important;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.08) !important;
        }
        .stat-card-title {
            color: #6c757d !important;
        }
        .stat-card-value {
            color: #212529 !important;
        }
        .stat-card-footer {
            color: #6c757d !important;
        }

        /* Table custom styling for light mode */
        .table {
            color: #212529 !important;
        }
        .table th {
            color: #495057 !important;
            border-bottom: 2px solid #dee2e6 !important;
        }
        .table td {
            color: #212529 !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
        .table tr:hover td {
            background: rgba(0,0,0,0.015) !important;
        }

        /* Cards custom styling for light mode */
        .card {
            background: #ffffff !important;
            border: 1px solid #dee2e6 !important;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.08) !important;
            border-radius: 8px !important;
            color: #212529 !important;
        }
        .card h3 {
            color: #212529 !important;
        }

        /* Form control and form-input overrides for white theme inputs */
        .form-control,
        .form-input {
            display: block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057 !important;
            background-color: #ffffff !important;
            border: 1px solid #ced4da !important;
            border-radius: .25rem !important;
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        textarea.form-control,
        textarea.form-input {
            height: auto !important;
        }
        .form-control:focus,
        .form-input:focus {
            background-color: #ffffff !important;
            border-color: #80bdff !important;
            color: #495057 !important;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
        }
        .form-label {
            color: #495057 !important;
            font-weight: 600 !important;
            display: inline-block;
            margin-bottom: .5rem;
        }

        /* Overrides for inline styles matching old dark mode backgrounds */
        div[style*="background: rgba(15,23,42,0.4)"] {
            background: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
            color: #212529 !important;
        }
        div[style*="background: rgba(255,255,255,0.01)"] {
            background: #ffffff !important;
            border: 1px solid #dee2e6 !important;
        }
        div[style*="background: rgba(255,255,255,0.02)"] {
            background: #f8f9fa !important;
            border: 1px solid #dee2e6 !important;
        }
        div[style*="background: rgba(99, 102, 241, 0.02)"] {
            background: rgba(0, 123, 255, 0.03) !important;
            border-color: rgba(0, 123, 255, 0.15) !important;
            color: #212529 !important;
        }
        code[style*="background: rgba(0,0,0,0.2)"] {
            background: #e9ecef !important;
            color: #e83e8c !important;
        }

        /* Prevent public layout .navbar and .nav-link styles from bleeding into AdminLTE */
        .main-header.navbar {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: .5rem 1rem !important;
            background-color: #ffffff !important;
            border-bottom: 1px solid #dee2e6 !important;
            position: relative !important;
            top: 0 !important;
            z-index: 1030 !important;
            backdrop-filter: none !important;
            height: auto !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
        }
        
        .main-header.navbar .nav-link {
            color: #495057 !important;
            font-size: 1rem !important;
            font-weight: 500 !important;
            padding: .5rem 1rem !important;
            background: none !important;
            border: none !important;
            display: flex !important;
            align-items: center !important;
        }

        .main-header.navbar .nav-link:hover {
            color: #007bff !important;
        }

        .main-header.navbar .navbar-nav {
            align-items: center !important;
            display: flex !important;
            flex-direction: row !important;
        }

        .main-header.navbar .nav-link[data-widget="pushmenu"] {
            width: 38px !important;
            height: 38px !important;
            border-radius: 50% !important;
            background-color: #f8f9fa !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: #495057 !important;
            transition: all 0.2s ease !important;
            padding: 0 !important;
        }
        
        .main-header.navbar .nav-link[data-widget="pushmenu"]:hover {
            background-color: #e9ecef !important;
            color: #212529 !important;
        }

        .nav-portal-btn {
            font-weight: 600 !important;
            font-size: 14px !important;
            color: #007bff !important;
            background-color: rgba(0, 123, 255, 0.05) !important;
            border: 1px solid rgba(0, 123, 255, 0.15) !important;
            border-radius: 20px !important;
            padding: 6px 16px !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            transition: all 0.2s ease !important;
            margin-left: 15px !important;
        }
        
        .nav-portal-btn:hover {
            background-color: #007bff !important;
            color: #ffffff !important;
            border-color: #007bff !important;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.15) !important;
            text-decoration: none !important;
        }

        .user-avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.2) !important;
        }
        
        .badge-role {
            font-size: 10px !important;
            padding: 4px 8px !important;
            border-radius: 20px !important;
            font-weight: 700 !important;
            letter-spacing: 0.5px !important;
            background-color: rgba(0, 123, 255, 0.1) !important;
            color: #007bff !important;
            border: 1px solid rgba(0, 123, 255, 0.2) !important;
            text-transform: uppercase !important;
        }
        
        /* Sidebar nav-link overrides to keep AdminLTE styling intact */
        .main-sidebar .nav-link:not(.bg-danger) {
            font-size: 0.95rem !important;
            font-weight: 500 !important;
            color: #495057 !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            background: transparent !important;
            border: none !important;
        }
        
        .main-sidebar .nav-link.active:not(.bg-danger) {
            color: #ffffff !important;
            background-color: #007bff !important;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.25) !important;
        }
        
        .main-sidebar .nav-link:hover:not(.active):not(.bg-danger) {
            background-color: rgba(0, 0, 0, 0.04) !important;
            color: #212529 !important;
        }

        .main-sidebar .brand-link {
            border-bottom: 1px solid #dee2e6 !important;
            display: flex !important;
            align-items: center !important;
            padding: 15px !important;
        }

        /* Floating Toast Alert styling */
        #toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }
        .custom-toast {
            min-width: 320px;
            max-width: 420px;
            background: #ffffff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border-left: 5px solid #007bff;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.3s ease;
            pointer-events: auto;
            opacity: 0;
        }
        .custom-toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        .custom-toast-success {
            border-left-color: #10b981;
        }
        .custom-toast-error {
            border-left-color: #ef4444;
        }
        .custom-toast-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .custom-toast-success .custom-toast-icon {
            color: #10b981;
        }
        .custom-toast-error .custom-toast-icon {
            color: #ef4444;
        }
        .custom-toast-content {
            flex: 1;
            min-width: 0;
        }
        .custom-toast-title {
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .custom-toast-message {
            font-size: 13px;
            color: #475569;
            line-height: 1.4;
            word-wrap: break-word;
        }
        .custom-toast-close {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
            font-size: 16px;
            line-height: 1;
            flex-shrink: 0;
            transition: color 0.15s ease;
        }
        .custom-toast-close:hover {
            color: #475569;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

    <!-- Toast Notifications Container -->
    <div id="toast-container"></div>

    <div class="wrapper">
        <!-- Top Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="<?= base_url() ?>" class="nav-link nav-portal-btn"><i class="fa-solid fa-house"></i> Lihat Portal Publik</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto align-items-center">
                <?php
                $userId = session()->get('user_id');
                $role = session()->get('role');
                $locationId = session()->get('location_id');
                $serviceUnitId = session()->get('service_unit_id');

                $complaintRepo = new \App\Repositories\ComplaintRepository();
                $unreadCount = $complaintRepo->getUnreadComplaintsCount((int)$userId, $role, $locationId, $serviceUnitId);
                $unreadComplaints = $complaintRepo->getUnreadComplaints((int)$userId, $role, $locationId, $serviceUnitId, 5);
                $notifHistory = $complaintRepo->getNotificationHistory((int)$userId, $role, $locationId, $serviceUnitId, 20);
                ?>
                
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown mr-3">
                    <a class="nav-link" id="nav-notif-bell" data-toggle="dropdown" href="#" aria-expanded="false" style="position: relative; padding: 8px; display: flex; align-items: center; justify-content: center;">
                        <i class="far fa-bell" style="font-size: 20px; color: var(--text-secondary);"></i>
                        <span id="nav-notif-badge" class="badge badge-warning navbar-badge" style="position: absolute; top: 0px; right: 0px; font-size: 9px; padding: 2px 4px; border-radius: 50%; font-weight: 800; background-color: #f59e0b; color: white; display: <?= $unreadCount > 0 ? 'inline-block' : 'none' ?>;">
                            <?= $unreadCount ?>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 320px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid var(--border-color); padding: 0; margin-top: 8px;">
                        <span class="dropdown-item dropdown-header font-weight-bold" style="padding: 12px; border-bottom: 1px solid var(--border-color); font-size: 14px; text-align: left; display: block; color: var(--text-main); background: transparent;">
                            <i class="fa-solid fa-bell mr-2" style="color: #f59e0b;"></i> <span id="nav-notif-header-count"><?= $unreadCount ?></span> Laporan Baru
                        </span>
                        <div class="dropdown-divider" style="margin: 0;"></div>
                        
                        <div id="nav-notif-list-empty" style="padding: 24px; text-align: center; color: var(--text-muted); font-size: 13px; display: <?= empty($unreadComplaints) ? 'block' : 'none' ?>;">
                            <i class="fa-regular fa-bell-slash" style="font-size: 32px; margin-bottom: 10px; display: block; color: #cbd5e1;"></i>
                            Tidak ada laporan baru.
                        </div>

                        <div id="nav-notif-list-items" style="max-height: 280px; overflow-y: auto; display: <?= !empty($unreadComplaints) ? 'block' : 'none' ?>;">
                            <?php foreach ($unreadComplaints as $uc): ?>
                                <a href="<?= base_url('admin/complaints/' . $uc['id'] . '?highlight=1') ?>" class="dropdown-item d-flex align-items-start" style="padding: 12px; border-bottom: 1px solid #f1f5f9; gap: 10px; white-space: normal; transition: background 0.2s;">
                                    <div style="background: rgba(14, 165, 233, 0.1); color: var(--secondary); border-radius: 8px; padding: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; width: 32px; height: 32px; flex-shrink: 0;">
                                        <i class="fa-solid fa-file-signature"></i>
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <p class="font-weight-bold mb-0 text-dark" style="font-size: 13px; line-height: 1.4; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            <?= esc($uc['title']) ?>
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between mt-2" style="font-size: 11px; color: var(--text-muted);">
                                            <span>#<?= esc($uc['ticket_number']) ?></span>
                                            <span><i class="fa-regular fa-clock"></i> <?= date('d M Y, H:i', strtotime($uc['created_at'])) ?></span>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <div class="dropdown-divider" style="margin: 0;"></div>
                        <a href="#" class="dropdown-item dropdown-footer text-center font-weight-bold" style="padding: 12px; color: var(--secondary); font-size: 13px; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; display: block; background: #fafafa;" data-toggle="modal" data-target="#notifHistoryModal">
                            Lihat Semua History Notifikasi <i class="fa-solid fa-clock-rotate-left" style="font-size: 11px; margin-left: 4px;"></i>
                        </a>
                    </div>
                </li>

                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" style="gap: 8px;">
                        <div class="user-avatar-circle">
                            <?= strtoupper(substr(esc(session()->get('name')) ?: 'A', 0, 1)) ?>
                        </div>
                        <span class="d-none d-md-inline font-weight-bold text-dark"><?= esc(session()->get('name')) ?></span>
                        <span class="badge badge-role text-uppercase"><?= str_replace('_', ' ', esc(session()->get('role'))) ?></span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?= base_url('admin/dashboard') ?>" class="brand-link bg-white">
                <span class="brand-image img-circle elevation-3 d-flex align-items-center justify-content-center" style="opacity: .8; background: #007bff; width: 33px; height: 33px; color: #fff;">
                    <i class="fa-solid fa-comments" style="font-size: 14px;"></i>
                </span>
                <span class="brand-text font-weight-bold ml-2 text-dark" style="letter-spacing: 1px;">SIGAP</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-3">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= ($active_menu === 'dashboard') ? 'active' : '' ?>">
                                <i class="nav-icon fa-solid fa-chart-line"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/complaints') ?>" class="nav-link <?= ($active_menu === 'complaints') ? 'active' : '' ?>">
                                <i class="nav-icon fa-solid fa-folder-open"></i>
                                <p>Semua Laporan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/complaints?status=submitted') ?>" class="nav-link <?= ($active_menu === 'new_complaints') ? 'active' : '' ?>">
                                <i class="nav-icon fa-solid fa-envelope-open-text"></i>
                                <p>Laporan Baru</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/complaints?status=processing') ?>" class="nav-link <?= ($active_menu === 'processing_complaints') ? 'active' : '' ?>">
                                <i class="nav-icon fa-solid fa-spinner"></i>
                                <p>Diproses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/complaints?status=resolved') ?>" class="nav-link <?= ($active_menu === 'resolved_complaints') ? 'active' : '' ?>">
                                <i class="nav-icon fa-solid fa-circle-check"></i>
                                <p>Selesai</p>
                            </a>
                        </li>
                        
                        <?php if (session()->get('role') === 'superadmin'): ?>
                            <li class="nav-header text-uppercase" style="color: #6c757d; font-weight: 700; font-size: 11px; padding: 15px 0 5px 15px;">Pengaturan Master</li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/users') ?>" class="nav-link <?= ($active_menu === 'users') ? 'active' : '' ?>">
                                    <i class="nav-icon fa-solid fa-user-gear"></i>
                                    <p>Manajemen Admin</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/locations') ?>" class="nav-link <?= ($active_menu === 'locations') ? 'active' : '' ?>">
                                    <i class="nav-icon fa-solid fa-map-location-dot"></i>
                                    <p>Lokasi Layanan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/service-units') ?>" class="nav-link <?= ($active_menu === 'service_units') ? 'active' : '' ?>">
                                    <i class="nav-icon fa-solid fa-building-user"></i>
                                    <p>Unit Layanan MPP</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/categories') ?>" class="nav-link <?= ($active_menu === 'categories') ? 'active' : '' ?>">
                                    <i class="nav-icon fa-solid fa-tags"></i>
                                    <p>Kategori Aduan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/analytics') ?>" class="nav-link <?= ($active_menu === 'analytics') ? 'active' : '' ?>">
                                    <i class="nav-icon fa-solid fa-chart-pie"></i>
                                    <p>Analytics Global</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/audit-logs') ?>" class="nav-link <?= ($active_menu === 'audit_logs') ? 'active' : '' ?>">
                                    <i class="nav-icon fa-solid fa-file-shield"></i>
                                    <p>Audit Log</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/database') ?>" class="nav-link <?= ($active_menu === 'database') ? 'active' : '' ?>">
                                    <i class="nav-icon fa-solid fa-database"></i>
                                    <p>Backup Database</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/settings') ?>" class="nav-link <?= ($active_menu === 'settings') ? 'active' : '' ?>">
                                    <i class="nav-icon fa-solid fa-sliders"></i>
                                    <p>Pengaturan Sistem</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <li class="nav-item mt-4">
                            <a href="<?= base_url('admin/logout') ?>" class="nav-link bg-danger text-white">
                                <i class="nav-icon fa-solid fa-right-from-bracket text-white"></i>
                                <p class="text-white font-weight-bold">Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header pt-4">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0 font-weight-extrabold text-dark" style="font-size: 28px; font-weight: 800;"><?= $this->renderSection('page_title') ?></h1>
                            <p class="text-muted mt-1" style="font-size: 14px;"><?= $this->renderSection('page_subtitle') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Flash Messages Container -->
                    <script>
                        // Global function to trigger a toast
                        function showToast(title, message, type = 'success') {
                            const container = document.getElementById('toast-container');
                            if (!container) return;

                            const toast = document.createElement('div');
                            toast.className = `custom-toast custom-toast-${type}`;
                            
                            const iconClass = type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation';

                            toast.innerHTML = `
                                <div class="custom-toast-icon">
                                    <i class="fa-solid ${iconClass}"></i>
                                </div>
                                <div class="custom-toast-content">
                                    <div class="custom-toast-title">${title}</div>
                                    <div class="custom-toast-message">${message}</div>
                                </div>
                                <button class="custom-toast-close" onclick="this.parentElement.remove()">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            `;

                            container.appendChild(toast);

                            // Trigger reflow to apply transition
                            toast.offsetHeight;
                            toast.classList.add('show');

                            // Auto remove after 4 seconds
                            setTimeout(() => {
                                toast.classList.remove('show');
                                setTimeout(() => {
                                    toast.remove();
                                }, 300);
                            }, 4000);
                        }
                    </script>

                    <?php if (session()->getFlashdata('success')): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                showToast('Berhasil!', '<?= esc(session()->getFlashdata('success'), 'js') ?>', 'success');
                            });
                        </script>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                showToast('Kesalahan!', '<?= esc(session()->getFlashdata('error'), 'js') ?>', 'error');
                            });
                        </script>
                    <?php endif; ?>

                    <!-- Render child view content -->
                    <?= $this->renderSection('content') ?>
                </div>
            </section>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer text-sm" style="background-color: #ffffff; border-top: 1px solid #dee2e6; color: #6c757d;">
            <strong>&copy; <?= date('Y') ?> <a href="<?= base_url() ?>" class="text-primary">Sigap</a>.</strong> Semua Hak Cipta Dilindungi.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Global Light Mode overrides for Chart.js if present -->
    <script>
        $(document).ready(function() {
            if (typeof Chart !== 'undefined') {
                Chart.defaults.color = '#6c757d';
                Chart.defaults.borderColor = '#dee2e6';
            }
        });

        // Helper to escape HTML tags to prevent XSS
        function escapeHtml(text) {
            if (!text) return '';
            return text
                .toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Notification polling function
        function updateNotifications() {
            $.ajax({
                url: '<?= base_url('admin/notifications/unread') ?>',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const count = response.unread_count;
                        
                        // 1. Badge count on bell icon
                        const badge = $('#nav-notif-badge');
                        if (count > 0) {
                            badge.text(count).css('display', 'inline-block');
                        } else {
                            badge.hide();
                        }

                        // 2. Header title count
                        $('#nav-notif-header-count').text(count);

                        // 3. Dropdown items list
                        const listEmpty = $('#nav-notif-list-empty');
                        const listItems = $('#nav-notif-list-items');
                        
                        if (response.unread_complaints.length === 0) {
                            listItems.hide();
                            listEmpty.show();
                        } else {
                            listEmpty.hide();
                            listItems.show();
                            
                            let html = '';
                            response.unread_complaints.forEach(function(uc) {
                                const detailUrl = '<?= base_url('admin/complaints') ?>/' + uc.id + '?highlight=1';
                                html += `
                                    <a href="${detailUrl}" class="dropdown-item d-flex align-items-start" style="padding: 12px; border-bottom: 1px solid #f1f5f9; gap: 10px; white-space: normal; transition: background 0.2s;">
                                        <div style="background: rgba(14, 165, 233, 0.1); color: var(--secondary); border-radius: 8px; padding: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; width: 32px; height: 32px; flex-shrink: 0;">
                                            <i class="fa-solid fa-file-signature"></i>
                                        </div>
                                        <div style="flex: 1; min-width: 0;">
                                            <p class="font-weight-bold mb-0 text-dark" style="font-size: 13px; line-height: 1.4; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                                ${escapeHtml(uc.title)}
                                            </p>
                                            <div class="d-flex align-items-center justify-content-between mt-2" style="font-size: 11px; color: var(--text-muted);">
                                                <span>#${escapeHtml(uc.ticket_number)}</span>
                                                <span><i class="fa-regular fa-clock"></i> ${uc.formatted_date}</span>
                                            </div>
                                        </div>
                                    </a>
                                `;
                            });
                            listItems.html(html);
                        }

                        // 4. Modal items list
                        const modalEmpty = $('#modal-notif-empty');
                        const modalItems = $('#modal-notif-list-items');
                        
                        if (response.notification_history.length === 0) {
                            modalItems.hide();
                            modalEmpty.show();
                        } else {
                            modalEmpty.hide();
                            modalItems.show();
                            
                            let modalHtml = '';
                            response.notification_history.forEach(function(nh) {
                                const detailUrl = '<?= base_url('admin/complaints') ?>/' + nh.id + '?highlight=1';
                                const isRead = nh.read_at !== null;
                                const bgStyle = isRead ? 'transparent' : 'rgba(245, 158, 11, 0.04)';
                                const iconBg = isRead ? 'rgba(100, 116, 139, 0.1)' : 'rgba(245, 158, 11, 0.1)';
                                const iconColor = isRead ? 'var(--text-secondary)' : '#f59e0b';
                                const iconClass = isRead ? 'fa-envelope-open' : 'fa-envelope';
                                const badgeDisplay = isRead ? 'none' : 'inline-block';
                                const readTimeDisplay = isRead ? 'block' : 'none';
                                const readTimeText = nh.formatted_read_at ? `Dibaca: ${nh.formatted_read_at}` : '';

                                modalHtml += `
                                    <a href="${detailUrl}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between" style="padding: 16px 24px; border-bottom: 1px solid var(--border-color); transition: all 0.2s; background: ${bgStyle}; gap: 16px;">
                                        <div class="d-flex align-items-start" style="gap: 16px; min-width: 0; flex: 1;">
                                            <div class="notif-modal-icon-wrapper" style="background: ${iconBg}; color: ${iconColor}; border-radius: 10px; padding: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; width: 40px; height: 40px; flex-shrink: 0;">
                                                <i class="fa-solid ${iconClass}"></i>
                                            </div>
                                            <div style="min-width: 0; flex: 1;">
                                                <div class="d-flex align-items-center" style="gap: 8px; flex-wrap: wrap;">
                                                    <span class="font-weight-bold text-dark modal-notif-title" style="font-size: 14px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 320px;">
                                                        ${escapeHtml(nh.title)}
                                                    </span>
                                                    <span class="badge modal-notif-new-badge" style="background-color: #f59e0b; color: white; font-size: 10px; padding: 3px 8px; border-radius: 12px; font-weight: 700; display: ${badgeDisplay};">Baru</span>
                                                </div>
                                                <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">#${escapeHtml(nh.ticket_number)}</span>
                                            </div>
                                        </div>
                                        <div class="text-right flex-shrink-0" style="font-size: 11px; color: var(--text-muted); min-width: 110px;">
                                            <div class="modal-notif-time"><i class="fa-regular fa-clock"></i> ${nh.formatted_date}</div>
                                            <div class="modal-notif-read-time" style="font-size: 10px; color: #10b981; margin-top: 4px; display: ${readTimeDisplay};">
                                                <i class="fa-solid fa-check-double"></i> ${readTimeText}
                                            </div>
                                        </div>
                                    </a>
                                `;
                            });
                            modalItems.html(modalHtml);
                        }
                    }
                },
                error: function(err) {
                    console.error('Gagal mengambil data notifikasi:', err);
                }
            });
        }

        // Start polling on DOM ready and run every 10 seconds
        $(document).ready(function() {
            updateNotifications();
            setInterval(updateNotifications, 10000);
        });
    </script>

    <!-- Notification History Modal -->
    <div class="modal fade" id="notifHistoryModal" tabindex="-1" role="dialog" aria-labelledby="notifHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.2);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 18px 24px; display: flex; align-items: center; justify-content: space-between;">
                    <h5 class="modal-title font-weight-bold" id="notifHistoryModalLabel" style="font-size: 18px; color: var(--text-main);">
                        <i class="fa-solid fa-clock-rotate-left mr-2" style="color: var(--secondary);"></i> Riwayat Notifikasi Laporan
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 24px; padding: 0; margin: 0; color: var(--text-muted); outline: none; background: transparent; border: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding: 0; max-height: 480px; overflow-y: auto;">
                    <div id="modal-notif-empty" style="padding: 40px; text-align: center; color: var(--text-muted); display: <?= empty($notifHistory) ? 'block' : 'none' ?>;">
                        <i class="fa-regular fa-bell-slash" style="font-size: 48px; margin-bottom: 16px; display: block; color: var(--border-color);"></i>
                        Belum ada riwayat aktivitas laporan.
                    </div>
                    
                    <div id="modal-notif-list-items" class="list-group list-group-flush" style="display: <?= !empty($notifHistory) ? 'block' : 'none' ?>;">
                        <?php foreach ($notifHistory as $nh): ?>
                            <?php $isRead = ($nh['read_at'] !== null); ?>
                            <a href="<?= base_url('admin/complaints/' . $nh['id'] . '?highlight=1') ?>" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between" style="padding: 16px 24px; border-bottom: 1px solid var(--border-color); transition: all 0.2s; background: <?= $isRead ? 'transparent' : 'rgba(245, 158, 11, 0.04)' ?>; gap: 16px;">
                                <div class="d-flex align-items-start" style="gap: 16px; min-width: 0; flex: 1;">
                                    <div class="notif-modal-icon-wrapper" style="background: <?= $isRead ? 'rgba(100, 116, 139, 0.1)' : 'rgba(245, 158, 11, 0.1)' ?>; color: <?= $isRead ? 'var(--text-secondary)' : '#f59e0b' ?>; border-radius: 10px; padding: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; width: 40px; height: 40px; flex-shrink: 0;">
                                        <i class="fa-solid <?= $isRead ? 'fa-envelope-open' : 'fa-envelope' ?>"></i>
                                    </div>
                                    <div style="min-width: 0; flex: 1;">
                                        <div class="d-flex align-items-center" style="gap: 8px; flex-wrap: wrap;">
                                            <span class="font-weight-bold text-dark modal-notif-title" style="font-size: 14px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 320px;">
                                                <?= esc($nh['title']) ?>
                                            </span>
                                            <span class="badge modal-notif-new-badge" style="background-color: #f59e0b; color: white; font-size: 10px; padding: 3px 8px; border-radius: 12px; font-weight: 700; display: <?= $isRead ? 'none' : 'inline-block' ?>;">Baru</span>
                                        </div>
                                        <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">#<?= esc($nh['ticket_number']) ?></span>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0" style="font-size: 11px; color: var(--text-muted); min-width: 110px;">
                                    <div class="modal-notif-time"><i class="fa-regular fa-clock"></i> <?= date('d M Y, H:i', strtotime($nh['created_at'])) ?></div>
                                    <div class="modal-notif-read-time" style="font-size: 10px; color: #10b981; margin-top: 4px; display: <?= $isRead ? 'block' : 'none' ?>;">
                                        <i class="fa-solid fa-check-double"></i> Dibaca: <?= $nh['read_at'] ? date('d M, H:i', strtotime($nh['read_at'])) : '' ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color); padding: 14px 24px; background: #fafafa; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="padding: 8px 20px; font-size: 14px; border-radius: 8px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Specific Script -->
    <?= $this->renderSection('scripts') ?>

</body>
</html>
