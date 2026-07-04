<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Audit Log Sistem<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Catatan riwayat perubahan status, respon admin, dan aktivitas pengelolaan data pengaduan.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card" style="padding: 24px;">
    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-main);">Log Aktivitas Pengaduan</h3>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu Kejadian</th>
                    <th>Nomor Tiket</th>
                    <th>Judul Aduan</th>
                    <th>Perubahan / Aktivitas</th>
                    <th>Pelaku Aktivitas (Admin)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada log aktivitas tercatat.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td style="font-size: 13px;"><?= date('d M Y H:i:s', strtotime($log['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('admin/complaints/' . $log['complaint_id']) ?>" style="font-weight: 700; color: var(--primary); text-decoration: none;">
                                    <?= esc($log['ticket_number']) ?>
                                </a>
                            </td>
                            <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= esc($log['complaint_title']) ?>">
                                <?= esc($log['complaint_title']) ?>
                            </td>
                            <td>
                                <?php if ($log['old_status'] === null): ?>
                                    <span style="color: var(--text-main); font-weight: 500; font-size: 13px;">
                                        <?= esc($log['new_status']) ?>
                                    </span>
                                <?php else: ?>
                                    <div style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                                        <span class="badge badge-submitted" style="font-size: 11px; text-transform: uppercase;"><?= esc($log['old_status']) ?></span>
                                        <i class="fa-solid fa-arrow-right-long" style="color: var(--text-muted); font-size: 11px;"></i>
                                        <span class="badge badge-resolved" style="font-size: 11px; text-transform: uppercase;"><?= esc($log['new_status']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div class="profile-avatar" style="width: 24px; height: 24px; font-size: 10px;">
                                        <?= substr($log['changed_by'], 0, 1) ?>
                                    </div>
                                    <span style="font-weight: 600; font-size: 13px;"><?= esc($log['changed_by']) ?></span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div style="display: flex; justify-content: center; gap: 8px; margin-top: 24px;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="<?= base_url('admin/audit-logs?page=' . $i) ?>" class="btn <?= ($current_page == $i) ? 'btn-primary' : 'btn-secondary' ?>" style="padding: 6px 12px; font-size: 13px;">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
