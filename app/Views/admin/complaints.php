<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Daftar Pengaduan Masyarakat<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Kelola laporan, tanggapan, dan perkembangan status aduan.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$statusMapping = [
    'submitted'        => 'Laporan Baru',
    'verified'         => 'Terverifikasi',
    'processing'       => 'Diproses',
    'resolved'         => 'Selesai',
    'rejected'         => 'Ditolak',
    'waiting_response' => 'Menunggu Respon'
];
?>

<!-- Filters Form -->
<div class="card" style="padding: 20px; margin-bottom: 24px;">
    <form action="<?= base_url('admin/complaints') ?>" method="GET" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr; gap: 16px; align-items: flex-end; flex-wrap: wrap;">
        <!-- Preserve status if filter clicked from sidebar menu, unless overriden -->
        <input type="hidden" name="status" value="<?= esc($filters['status'] ?? '') ?>">

        <div style="display: flex; flex-direction: column;">
            <label class="form-label" for="search">Cari Laporan</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="No Tiket, Judul, Pengadu..." value="<?= esc($filters['search'] ?? '') ?>">
        </div>

        <div style="display: flex; flex-direction: column;">
            <label class="form-label" for="complaint_type">Jenis Aduan</label>
            <select name="complaint_type" id="complaint_type" class="form-control">
                <option value="">Semua Jenis</option>
                <option value="Pengaduan" <?= ($filters['complaint_type'] === 'Pengaduan') ? 'selected' : '' ?>>Pengaduan</option>
                <option value="Aspirasi" <?= ($filters['complaint_type'] === 'Aspirasi') ? 'selected' : '' ?>>Aspirasi</option>
                <option value="Saran" <?= ($filters['complaint_type'] === 'Saran') ? 'selected' : '' ?>>Saran</option>
                <option value="Apresiasi" <?= ($filters['complaint_type'] === 'Apresiasi') ? 'selected' : '' ?>>Apresiasi</option>
            </select>
        </div>

        <div style="display: flex; flex-direction: column;">
            <label class="form-label" for="status_select">Status</label>
            <select name="status_select" id="status_select" class="form-control" onchange="this.form.status.value=this.value;">
                <option value="">Semua Status</option>
                <option value="submitted" <?= ($filters['status'] === 'submitted') ? 'selected' : '' ?>>Laporan Baru</option>
                <option value="verified" <?= ($filters['status'] === 'verified') ? 'selected' : '' ?>>Terverifikasi</option>
                <option value="processing" <?= ($filters['status'] === 'processing') ? 'selected' : '' ?>>Diproses</option>
                <option value="waiting_response" <?= ($filters['status'] === 'waiting_response') ? 'selected' : '' ?>>Menunggu Respon</option>
                <option value="resolved" <?= ($filters['status'] === 'resolved') ? 'selected' : '' ?>>Selesai</option>
                <option value="rejected" <?= ($filters['status'] === 'rejected') ? 'selected' : '' ?>>Ditolak</option>
            </select>
        </div>

        <div></div> <!-- Spacer -->

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="padding: 10px 16px; width: 100%;">
                <i class="fa-solid fa-filter"></i> Saring
            </button>
            <a href="<?= base_url('admin/complaints') ?>" class="btn btn-secondary" style="padding: 10px 16px;">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Complaints Table -->
<style>
    @keyframes rowPulse {
        0% {
            background-color: rgba(245, 158, 11, 0.15);
        }
        50% {
            background-color: rgba(245, 158, 11, 0.3);
        }
        100% {
            background-color: transparent;
        }
    }
    .highlight-row {
        animation: rowPulse 3s ease-in-out forwards;
    }
    .unread-row {
        background-color: rgba(14, 165, 233, 0.04) !important;
    }
    .unread-row td {
        font-weight: 600;
    }
</style>
<div class="card" style="padding: 24px;">
    <?php if (empty($complaints)): ?>
        <div style="text-align: center; padding: 40px 0; color: var(--text-muted);">
            <i class="fa-regular fa-folder-open" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
            <p>Tidak ada laporan pengaduan yang sesuai dengan penyaringan Anda.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tiket / Tanggal</th>
                        <th>Unit / Tipe</th>
                        <th>Kategori</th>
                        <th>Judul Laporan</th>
                        <th>Pengadu</th>
                        <th>Status</th>
                        <th style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($complaints as $c): ?>
                        <tr class="<?= (isset($_GET['highlight']) && $_GET['highlight'] == $c['id']) ? 'highlight-row' : '' ?> <?= empty($c['read_at']) ? 'unread-row' : '' ?>">
                            <td>
                                <strong style="color: var(--text-primary);"><?= esc($c['ticket_number']) ?></strong>
                                <?php if (empty($c['read_at'])): ?>
                                    <span class="badge" style="background-color: var(--secondary); color: white; font-size: 9px; padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-left: 4px; vertical-align: middle;">Baru</span>
                                <?php endif; ?>
                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;"><?= date('d M Y H:i', strtotime($c['created_at'])) ?></div>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: var(--secondary);"><?= esc($c['location_name']) ?></span>
                                <div style="font-size: 12px; color: var(--text-secondary);"><?= esc($c['complaint_type']) ?></div>
                            </td>
                            <td><?= esc($c['category_name']) ?></td>
                            <td>
                                <span style="font-weight: 500;"><?= esc(character_limiter($c['title'], 45)) ?></span>
                                <div style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">
                                    <?= esc(character_limiter(strip_tags($c['description']), 60)) ?>
                                </div>
                                <?php if (!empty($c['assigned_admin_name'])): ?>
                                    <div style="font-size: 11px; color: #10b981; margin-top: 4px; font-weight: 600;">
                                        <i class="fa-solid fa-user-shield"></i> <?= esc($c['assigned_admin_name']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($c['is_anonymous']): ?>
                                    <span style="color: var(--text-muted); font-style: italic;"><i class="fa-solid fa-user-secret"></i> Anonim</span>
                                <?php else: ?>
                                    <span><?= esc($c['complainant_name']) ?></span>
                                    <?php if ($c['complainant_phone']): ?>
                                        <div style="font-size: 11px; color: var(--text-muted);"><?= esc($c['complainant_phone']) ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $c['status'] ?>"><?= $statusMapping[$c['status']] ?? esc($c['status']) ?></span>
                            </td>
                            <td style="text-align: center;">
                                <a href="<?= base_url('admin/complaints/' . $c['id']) ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">
                                    Detail <i class="fa-solid fa-angle-right"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div style="display: flex; justify-content: center; gap: 8px; margin-top: 32px;">
                <?php if ($current_page > 1): ?>
                    <a href="<?= base_url('admin/complaints') ?>?page=<?= $current_page - 1 ?>&search=<?= esc($filters['search'] ?? '') ?>&complaint_type=<?= esc($filters['complaint_type'] ?? '') ?>&status=<?= esc($filters['status'] ?? '') ?>" class="btn btn-secondary" style="padding: 8px 14px;">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="<?= base_url('admin/complaints') ?>?page=<?= $i ?>&search=<?= esc($filters['search'] ?? '') ?>&complaint_type=<?= esc($filters['complaint_type'] ?? '') ?>&status=<?= esc($filters['status'] ?? '') ?>" class="btn <?= ($current_page == $i) ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 8px 14px;">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="<?= base_url('admin/complaints') ?>?page=<?= $current_page + 1 ?>&search=<?= esc($filters['search'] ?? '') ?>&complaint_type=<?= esc($filters['complaint_type'] ?? '') ?>&status=<?= esc($filters['status'] ?? '') ?>" class="btn btn-secondary" style="padding: 8px 14px;">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
