<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Dashboard Analytics<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Ringkasan statistik data pengaduan masuk Sigap.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Stat Summary Cards -->
<div class="stat-grid">
    <?php if (session()->get('role') === 'superadmin'): ?>
        <div class="stat-card" style="border-left: 4px solid var(--primary);">
            <div class="stat-card-title">Total Aduan Global</div>
            <div class="stat-card-value"><?= $summary['total'] ?></div>
            <div class="stat-card-footer">Semua laporan terdaftar</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #3b82f6;">
            <div class="stat-card-title">Aduan DPMPTSP</div>
            <div class="stat-card-value"><?= $summary['total_dpmptsp'] ?></div>
            <div class="stat-card-footer">Layanan Unit DPMPTSP</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #10b981;">
            <div class="stat-card-title">Aduan MPP</div>
            <div class="stat-card-value"><?= $summary['total_mpp'] ?></div>
            <div class="stat-card-footer">Layanan Mal Pelayanan Publik</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--warning);">
            <div class="stat-card-title">Pending Global</div>
            <div class="stat-card-value"><?= $summary['pending'] ?></div>
            <div class="stat-card-footer">Tindak lanjut tertunda</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid #8b5cf6;">
            <div class="stat-card-title">Admin Aktif</div>
            <div class="stat-card-value"><?= $summary['active_admins'] ?></div>
            <div class="stat-card-footer">Jumlah pengelola terdaftar</div>
        </div>
    <?php else: ?>
        <div class="stat-card" style="border-left: 4px solid var(--primary);">
            <div class="stat-card-title">Total Aduan</div>
            <div class="stat-card-value"><?= $summary['total'] ?></div>
            <div class="stat-card-footer">Semua laporan masuk</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--secondary);">
            <div class="stat-card-title">Aduan Hari Ini</div>
            <div class="stat-card-value"><?= $summary['today'] ?></div>
            <div class="stat-card-footer">Laporan baru masuk hari ini</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--warning);">
            <div class="stat-card-title">Pending</div>
            <div class="stat-card-value"><?= $summary['pending'] ?></div>
            <div class="stat-card-footer">Butuh tindak lanjut</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--success);">
            <div class="stat-card-title">Selesai</div>
            <div class="stat-card-value"><?= $summary['resolved'] ?></div>
            <div class="stat-card-footer">Berhasil diselesaikan</div>
        </div>
        <div class="stat-card" style="border-left: 4px solid var(--danger);">
            <div class="stat-card-title">Overdue (SLA)</div>
            <div class="stat-card-value"><?= $summary['overdue'] ?></div>
            <div class="stat-card-footer">Belum selesai > 3 hari</div>
        </div>
    <?php endif; ?>
</div>

<!-- Charts Grid -->
<div class="chart-grid">
    <!-- Chart 1: Laporan Bulanan (Bar/Line) -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Tren Laporan Bulanan</h3>
        <div style="height: 300px; position: relative;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Laporan per Kategori (Doughnut) -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Sebaran Kategori</h3>
        <div style="height: 300px; position: relative;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<div class="chart-grid" style="margin-top: 24px; <?= (session()->get('role') === 'superadmin') ? 'grid-template-columns: 1fr 1fr;' : 'grid-template-columns: 1fr;' ?>">
    <!-- Location Chart for Superadmin -->
    <?php if (session()->get('role') === 'superadmin'): ?>
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Perbandingan Unit (DPMPTSP vs MPP)</h3>
        <div style="height: 300px; position: relative;">
            <canvas id="locationChart"></canvas>
        </div>
    </div>
    <?php endif; ?>

    <!-- Chart 3: Laporan per Unit MPP (Bar) -->
    <?php if (session()->get('role') !== 'admin_dpmptsp'): ?>
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Tren Laporan per Unit Layanan MPP</h3>
        <div style="height: 300px; position: relative;">
            <canvas id="unitChart"></canvas>
        </div>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    // Custom colors matching the dashboard variables
    const chartTheme = {
        primary: '#6366f1',
        secondary: '#0ea5e9',
        success: '#10b981',
        warning: '#f59e0b',
        danger: '#ef4444',
        muted: '#64748b',
        colors: ['#6366f1', '#0ea5e9', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#3b82f6', '#14b8a6']
    };

    // Chart.js Default Configs for Light Mode
    Chart.defaults.color = chartTheme.muted;
    Chart.defaults.borderColor = 'rgba(0, 0, 0, 0.08)';
    Chart.defaults.font.family = 'Outfit, sans-serif';

    // 1. Monthly Tren Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = <?= json_encode($monthly_chart) ?>;
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [{
                label: 'Aduan Masuk',
                data: monthlyData.map(d => d.count),
                borderColor: chartTheme.primary,
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: chartTheme.primary
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // 2. Sebaran Kategori Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = <?= json_encode($category_chart) ?>;
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(d => d.category),
            datasets: [{
                data: categoryData.map(d => d.count),
                backgroundColor: chartTheme.colors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 12 }
                }
            }
        }
    });

    // 3. Location Chart (Superadmin only)
    <?php if (session()->get('role') === 'superadmin'): ?>
    const locationCtx = document.getElementById('locationChart').getContext('2d');
    const locationData = <?= json_encode($location_chart) ?>;
    
    new Chart(locationCtx, {
        type: 'pie',
        data: {
            labels: locationData.map(d => d.location),
            datasets: [{
                data: locationData.map(d => d.count),
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ec4899'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 12 }
                }
            }
        }
    });
    <?php endif; ?>

    // 4. Unit MPP Chart (Only rendered if MPP or Superadmin)
    <?php if (session()->get('role') !== 'admin_dpmptsp'): ?>
    const unitCtx = document.getElementById('unitChart').getContext('2d');
    const unitData = <?= json_encode($unit_chart) ?>;
    
    new Chart(unitCtx, {
        type: 'bar',
        data: {
            labels: unitData.map(d => d.unit),
            datasets: [{
                label: 'Total Aduan',
                data: unitData.map(d => d.count),
                backgroundColor: chartTheme.secondary,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
    <?php endif; ?>
</script>
<?= $this->endSection() ?>
