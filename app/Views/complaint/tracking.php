<?php

// ─── Hero Content (Search card inside red banner) ────────────
ob_start(); ?>

<div class="tracking-container">
    <h1 class="text-center" style="font-weight: 800; font-size: 32px; margin-bottom: 12px; color: #ffffff;">
        Lacak Laporan Anda
    </h1>
    <p class="text-center" style="color: rgba(255,255,255,0.9); margin-bottom: 30px;">
        Masukkan nomor tiket dan PIN rahasia untuk melacak status dan progres tindak lanjut aduan Anda.
    </p>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success" style="max-width: 500px; margin: 0 auto 20px; background: #ecfdf5; border-color: #a7f3d0; color: #065f46;">
            <i class="fa-solid fa-circle-check"></i>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
    <?php endif; ?>

    <!-- Search Form -->
    <div class="card search-card" style="box-shadow: 0 15px 35px rgba(0,0,0,0.15);">
        <form action="<?= base_url('tracking') ?>" method="GET">
            <div class="form-group">
                <label class="form-label" for="ticket" style="color: #374151; font-weight: 600;">Nomor Tiket</label>
                <input type="text" name="ticket" id="ticket" class="form-control" placeholder="Contoh: KM-2026-000001" value="<?= esc($ticket ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="pin" style="color: #374151; font-weight: 600;">PIN Rahasia</label>
                <input type="password" name="pin" id="pin" class="form-control" placeholder="6 Digit PIN" maxlength="10" value="<?= esc($pin ?? '') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; background: #d32f2f; border-color: #d32f2f; font-weight: 700;">
                Lacak Aduan <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>

    <!-- Error State -->
    <?php if ($error): ?>
        <div class="alert alert-danger" style="max-width: 500px; margin: 20px auto 0;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div><?= esc($error) ?></div>
        </div>
    <?php endif; ?>
</div>

<?php $hero_content = ob_get_clean();

// ─── Body Content (Complaint Results Details, below the banner) ──
$body_content = '';
if ($complaint) {
    ob_start(); ?>
    <div class="container" style="padding-top: 40px;">
        <div class="card" style="margin-top: 0; box-shadow: 0 10px 30px rgba(0,0,0,0.06); padding: 32px; color: #111827;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 1px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; flex-wrap: wrap; gap: 16px;">
                <div>
                    <span class="badge badge-<?= $complaint['status'] ?>"><?= $complaint['status'] ?></span>
                    <h2 style="font-weight: 800; font-size: 24px; margin-top: 8px; color: #111827;"><?= esc($complaint['ticket_number']) ?></h2>
                    <p style="color: var(--text-muted); font-size: 13px;">Dikirim pada: <?= date('d M Y H:i', strtotime($complaint['created_at'])) ?> WIB</p>
                </div>
                <div style="text-align: right;">
                    <p style="font-weight: 600; font-size: 14px; color: var(--text-secondary);">Unit Layanan:</p>
                    <p style="font-size: 16px; font-weight: 700; color: var(--secondary);"><?= esc($complaint['location_name']) ?></p>
                    <?php if (!empty($complaint['service_unit_name'])): ?>
                        <p style="font-size: 14px; color: var(--text-secondary);"><?= esc($complaint['service_unit_name']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin-top: 20px;">
                <!-- Main Content -->
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px; color: #111827;"><?= esc($complaint['title']) ?></h3>
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 16px;">
                        Kategori: <strong style="color: var(--text-secondary);"><?= esc($complaint['category_name']) ?></strong> | 
                        Jenis: <strong style="color: var(--text-secondary);"><?= esc($complaint['complaint_type']) ?></strong>
                    </p>
                    
                    <div style="background: #f9fafb; border-radius: var(--radius-sm); padding: 20px; border: 1px solid #e5e7eb;">
                        <p style="white-space: pre-line; color: #1f2937; font-size: 15px;"><?= esc($complaint['description']) ?></p>
                    </div>

                    <!-- Attachments -->
                    <?php if (!empty($complaint['attachments'])): ?>
                        <div style="margin-top: 24px;">
                            <h4 style="font-size: 14px; font-weight: 600; color: var(--text-secondary); margin-bottom: 10px;">Berkas Bukti Dukung:</h4>
                            <?php foreach ($complaint['attachments'] as $att): ?>
                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                                    <?php if (str_contains($att['file_type'], 'image')): ?>
                                        <div>
                                            <img src="<?= base_url($att['file_path']) ?>" class="attachment-preview" alt="Bukti aduan">
                                        </div>
                                    <?php else: ?>
                                        <a href="<?= base_url($att['file_path']) ?>" target="_blank" class="btn btn-secondary" style="padding: 6px 12px; font-size: 13px;">
                                            <i class="fa-solid fa-file-pdf"></i> Unduh File PDF
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Admin Replies -->
                    <div style="margin-top: 40px;">
                        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; color: #111827;">
                            <i class="fa-solid fa-comments"></i> Tanggapan / Jawaban Admin
                        </h3>
                        <?php if (empty($complaint['replies'])): ?>
                            <p style="color: var(--text-muted); font-size: 14px; font-style: italic;">Belum ada tanggapan resmi dari pihak terkait.</p>
                        <?php else: ?>
                            <?php foreach ($complaint['replies'] as $reply): ?>
                                <div class="reply-box">
                                    <div class="reply-meta">
                                        <span style="color: #b71c1c; font-weight: 600;"><i class="fa-solid fa-user-tie"></i> <?= esc($reply['admin_name'] ?: 'Admin') ?> (Tanggapan Resmi)</span>
                                        <span style="color: #6b7280;"><?= date('d M Y H:i', strtotime($reply['created_at'])) ?></span>
                                    </div>
                                    <p style="white-space: pre-line; color: #1f2937; font-size: 14px;"><?= esc($reply['message']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Side Status Logs Timeline -->
                <div>
                    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #111827;">Riwayat Status</h3>
                    <div class="timeline">
                        <?php foreach ($complaint['status_logs'] as $log): 
                            $itemClass = '';
                            if ($log['new_status'] === 'resolved') {
                                $itemClass = 'success';
                            } elseif ($log['new_status'] === 'rejected') {
                                $itemClass = 'danger';
                            } elseif ($log['new_status'] === 'processing' || $log['new_status'] === 'waiting_response') {
                                $itemClass = 'active';
                            }
                        ?>
                            <div class="timeline-item <?= $itemClass ?>">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <div class="timeline-date"><?= date('d M Y H:i', strtotime($log['created_at'])) ?></div>
                                    <div class="timeline-title">Status: <span class="badge badge-<?= $log['new_status'] ?>"><?= $log['new_status'] ?></span></div>
                                    <p style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">Oleh: <?= esc($log['changed_by']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $body_content = ob_get_clean();
}

// ─── Render via layout ───────────────────────────────────────
echo view('layouts/public', [
    'page_title'   => 'Lacak Pengaduan — Sigap',
    'hero_content' => $hero_content,
    'body_content' => $body_content,
]);
