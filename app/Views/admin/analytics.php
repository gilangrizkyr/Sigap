<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Analisis & Statistik Global<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Laporan komparatif dan performa pelayanan DPMPTSP dan MPP Sigap.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Summary Grid (Global, DPMPTSP, MPP side by side) -->
<div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 24px;">
    <!-- Global Summary -->
    <div class="card" style="padding: 20px; border-top: 4px solid var(--primary);">
        <h4 style="font-size: 14px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px;">Akumulasi Global</h4>
        <div style="font-size: 32px; font-weight: 800; color: var(--text-main); margin-bottom: 8px;"><?= $summary_global['total'] ?></div>
        <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted);">
            <span>Pending: <strong><?= $summary_global['pending'] ?></strong></span>
            <span>Selesai: <strong style="color: var(--success);"><?= $summary_global['resolved'] ?></strong></span>
        </div>
    </div>
    
    <!-- DPMPTSP Summary -->
    <div class="card" style="padding: 20px; border-top: 4px solid #3b82f6;">
        <h4 style="font-size: 14px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px;">Unit DPMPTSP</h4>
        <div style="font-size: 32px; font-weight: 800; color: var(--text-main); margin-bottom: 8px;"><?= $summary_dpmptsp['total'] ?></div>
        <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted);">
            <span>Pending: <strong><?= $summary_dpmptsp['pending'] ?></strong></span>
            <span>Selesai: <strong style="color: var(--success);"><?= $summary_dpmptsp['resolved'] ?></strong></span>
        </div>
    </div>

    <!-- MPP Summary -->
    <div class="card" style="padding: 20px; border-top: 4px solid #10b981;">
        <h4 style="font-size: 14px; color: var(--text-muted); text-transform: uppercase; margin-bottom: 12px;">Unit MPP</h4>
        <div style="font-size: 32px; font-weight: 800; color: var(--text-main); margin-bottom: 8px;"><?= $summary_mpp['total'] ?></div>
        <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted);">
            <span>Pending: <strong><?= $summary_mpp['pending'] ?></strong></span>
            <span>Selesai: <strong style="color: var(--success);"><?= $summary_mpp['resolved'] ?></strong></span>
        </div>
    </div>
</div>

<!-- Charts Panel Grid -->
<div class="chart-grid">
    <!-- Chart 1: Tren Bulanan -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Tren Bulanan (Semua Unit)</h3>
        <div style="height: 280px; position: relative;">
            <canvas id="analyticsMonthlyChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Perbandingan Unit -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Distribusi Per Lokasi</h3>
        <div style="height: 280px; position: relative;">
            <canvas id="analyticsLocationChart"></canvas>
        </div>
    </div>
</div>

<div class="chart-grid" style="margin-top: 24px;">
    <!-- Chart 3: Sebaran Kategori -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Sebaran Kategori Pengaduan</h3>
        <div style="height: 280px; position: relative;">
            <canvas id="analyticsCategoryChart"></canvas>
        </div>
    </div>

    <!-- Chart 4: Sebaran Unit Layanan MPP -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Pengaduan per Loket/Sub-Unit MPP</h3>
        <div style="height: 280px; position: relative;">
            <canvas id="analyticsUnitChart"></canvas>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const chartTheme = {
        primary: '#6366f1',
        secondary: '#0ea5e9',
        success: '#10b981',
        warning: '#f59e0b',
        muted: '#64748b',
        colors: ['#6366f1', '#0ea5e9', '#10b981', '#f59e0b', '#ec4899', '#8b5cf6', '#3b82f6', '#14b8a6']
    };

    Chart.defaults.color = chartTheme.muted;
    Chart.defaults.borderColor = 'rgba(0, 0, 0, 0.08)';
    Chart.defaults.font.family = 'Outfit, sans-serif';

    // 1. Monthly Chart
    const monthlyData = <?= json_encode($monthly_chart) ?>;
    new Chart(document.getElementById('analyticsMonthlyChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [{
                label: 'Aduan Global',
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
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // 2. Location Chart
    const locationData = <?= json_encode($location_chart) ?>;
    new Chart(document.getElementById('analyticsLocationChart').getContext('2d'), {
        type: 'doughnut',
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
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } } }
        }
    });

    // 3. Category Chart
    const categoryData = <?= json_encode($category_chart) ?>;
    new Chart(document.getElementById('analyticsCategoryChart').getContext('2d'), {
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
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } } }
        }
    });

    // 4. Unit Chart
    const unitData = <?= json_encode($unit_chart) ?>;
    new Chart(document.getElementById('analyticsUnitChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: unitData.map(d => d.unit),
            datasets: [{
                label: 'Aduan MPP',
                data: unitData.map(d => d.count),
                backgroundColor: chartTheme.secondary,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>
<?= $this->endSection() ?>
