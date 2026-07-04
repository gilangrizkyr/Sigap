<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Detail Pengaduan — <?= esc($complaint['ticket_number']) ?><?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Verifikasi isi aduan, kelola status laporan, dan kirim tanggapan resmi.<?= $this->endSection() ?>

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

<div style="margin-bottom: 24px;">
    <a href="<?= base_url('admin/complaints') ?>" class="btn btn-secondary" style="padding: 8px 16px; font-size: 14px;">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Laporan
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px; align-items: flex-start;">
    <!-- Left Column: Details -->
    <div>
        <!-- Laporan Details Card -->
        <style>
            @keyframes highlightPulse {
                0% {
                    box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.4);
                    border-color: #f59e0b;
                    background-color: rgba(245, 158, 11, 0.05);
                }
                50% {
                    box-shadow: 0 0 0 10px rgba(245, 158, 11, 0.8);
                    border-color: #f59e0b;
                    background-color: rgba(245, 158, 11, 0.15);
                }
                100% {
                    box-shadow: none;
                    border-color: var(--border-color);
                    background-color: var(--card-bg);
                }
            }
            .highlight-pulse {
                animation: highlightPulse 3s ease-in-out forwards;
                border: 2px solid #f59e0b !important;
            }
        </style>
        <div class="card <?= isset($_GET['highlight']) ? 'highlight-pulse' : '' ?>" style="margin-bottom: 28px;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 16px; margin-bottom: 20px;">
                <h3 style="font-size: 18px; font-weight: 700;">Informasi Pengaduan</h3>
                <span class="badge badge-<?= $complaint['status'] ?>" style="font-size: 13px; padding: 8px 16px;"><?= $statusMapping[$complaint['status']] ?? esc($complaint['status']) ?></span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
                <div>
                    <p style="font-size: 13px; color: var(--text-muted);">Judul Laporan</p>
                    <p style="font-weight: 600; font-size: 16px; margin-top: 4px;"><?= esc($complaint['title']) ?></p>
                </div>
                <div>
                    <p style="font-size: 13px; color: var(--text-muted);">Tanggal Masuk</p>
                    <p style="font-weight: 600; font-size: 16px; margin-top: 4px;"><?= date('d F Y H:i', strtotime($complaint['created_at'])) ?> WIB</p>
                </div>
                <div>
                    <p style="font-size: 13px; color: var(--text-muted);">Tujuan Layanan</p>
                    <p style="font-weight: 600; font-size: 16px; margin-top: 4px; color: var(--secondary);"><?= esc($complaint['location_name']) ?></p>
                    <?php if ($complaint['service_unit_name']): ?>
                        <p style="font-size: 13px; color: var(--text-secondary); margin-top: 2px;">Unit: <?= esc($complaint['service_unit_name']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <p style="font-size: 13px; color: var(--text-muted);">Kategori & Tipe</p>
                    <p style="font-weight: 600; font-size: 16px; margin-top: 4px;">
                        <?= esc($complaint['category_name']) ?> <span style="font-weight:normal; color:var(--text-muted);">/</span> <?= esc($complaint['complaint_type']) ?>
                    </p>
                </div>
            </div>

            <div style="margin-bottom: 24px;">
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 8px;">Isi Pengaduan / Kronologi</p>
                <div style="background: rgba(15,23,42,0.4); border-radius: var(--radius-sm); padding: 20px; border: 1px solid var(--border-color); white-space: pre-line; font-size: 15px;">
                    <?= esc($complaint['description']) ?>
                </div>
            </div>

            <!-- Uploads preview -->
            <?php if (!empty($complaint['attachments'])): ?>
                <div style="border-top: 1px solid var(--border-color); padding-top: 20px; margin-bottom: 20px;">
                    <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 12px;">File Bukti Pendukung</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                        <?php foreach ($complaint['attachments'] as $att): ?>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                <?php if (str_contains($att['file_type'], 'image')): ?>
                                    <a href="<?= base_url($att['file_path']) ?>" target="_blank">
                                        <img src="<?= base_url($att['file_path']) ?>" style="max-width: 250px; border-radius: var(--radius-sm); border: 1px solid var(--border-color);" alt="Bukti aduan">
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url($att['file_path']) ?>" target="_blank" class="btn btn-secondary" style="font-size: 13px; padding: 10px 14px;">
                                        <i class="fa-solid fa-file-pdf" style="font-size: 18px; color: var(--danger);"></i> Unduh Dokumen PDF
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Timeline Log Card -->
        <div class="card">
            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                <i class="fa-solid fa-clock-rotate-left"></i> Log Perubahan Status & Audit
            </h3>
            
            <div style="position: relative; padding-left: 24px; margin-top: 10px;">
                <div style="position: absolute; top: 0; bottom: 0; left: 7px; width: 2px; background: var(--border-color);"></div>
                <?php foreach ($complaint['status_logs'] as $log): ?>
                    <div style="position: relative; margin-bottom: 20px;">
                        <div style="position: absolute; left: -22px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background: var(--primary); border: 2px solid var(--bg-primary);"></div>
                        <div style="font-size: 12px; color: var(--text-muted);"><?= date('d M Y H:i', strtotime($log['created_at'])) ?> WIB</div>
                        <div style="font-size: 14px; font-weight: 600; margin-top: 2px;">
                            Status diubah: 
                            <span class="badge badge-<?= $log['new_status'] ?>" style="font-size: 10px; padding: 2px 8px;"><?= $statusMapping[$log['new_status']] ?? esc($log['new_status']) ?></span>
                        </div>
                        <p style="font-size: 12px; color: var(--text-secondary); margin-top: 2px;">Diproses oleh: <strong><?= esc($log['changed_by']) ?></strong></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Control Panel & Replies -->
    <div>
        <!-- Update Status Card -->
        <div class="card" style="padding: 24px; margin-bottom: 28px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Kelola Status Aduan</h3>
            <?php if ($role === 'superadmin'): ?>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="badge badge-<?= $complaint['status'] ?>" style="font-size: 13px; padding: 8px 16px;">
                        <?= $statusMapping[$complaint['status']] ?? esc($complaint['status']) ?>
                    </span>
                </div>
                <p style="font-size: 12px; color: var(--text-muted); margin-top: 12px; margin-bottom: 0;">
                    <i class="fa-solid fa-circle-info"></i> Super Admin hanya memantau dan tidak dapat mengubah status.
                </p>
            <?php else: ?>
                <form action="<?= base_url('admin/complaints/' . $complaint['id'] . '/status') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" for="status">Pilih Status Baru</label>
                        <select name="status" id="status" class="form-control">
                            <option value="submitted" <?= $complaint['status'] === 'submitted' ? 'selected' : '' ?>>Laporan Baru</option>
                            <?php if (!in_array($complaint['status'], ['submitted', 'processing', 'resolved'])): ?>
                                <option value="<?= esc($complaint['status']) ?>" selected><?= $statusMapping[$complaint['status']] ?? esc($complaint['status']) ?></option>
                            <?php endif; ?>
                            <option value="processing" <?= $complaint['status'] === 'processing' ? 'selected' : '' ?>>Diproses</option>
                            <option value="resolved" <?= $complaint['status'] === 'resolved' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px;">
                        Perbarui Status <i class="fa-solid fa-rotate"></i>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <!-- Info Pelapor Card -->
        <div class="card" style="padding: 24px; margin-bottom: 28px; background: rgba(255,255,255,0.01);">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                <i class="fa-solid fa-id-card"></i> Profil Pelapor
            </h3>
            <?php if ($complaint['is_anonymous']): ?>
                <div style="text-align: center; padding: 16px 0; color: var(--text-muted);">
                    <i class="fa-solid fa-user-secret" style="font-size: 32px; margin-bottom: 10px; display: block;"></i>
                    <p style="font-weight: 600; font-size: 14px;">Pelapor Anonim</p>
                    <p style="font-size: 12px; margin-top: 4px;">Identitas disembunyikan oleh sistem.</p>
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div>
                        <span style="font-size: 12px; color: var(--text-muted);">Nama Lengkap</span>
                        <p style="font-weight: 600; font-size: 14px; margin-top: 2px;"><?= esc($complaint['complainant_name']) ?></p>
                    </div>
                    <?php if ($complaint['complainant_phone']): ?>
                        <div>
                            <span style="font-size: 12px; color: var(--text-muted);">No. HP / WhatsApp</span>
                            <p style="font-weight: 600; font-size: 14px; margin-top: 2px;">
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $complaint['complainant_phone']) ?>" target="_blank" style="color: var(--success);">
                                    <i class="fa-brands fa-whatsapp"></i> <?= esc($complaint['complainant_phone']) ?>
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                    <?php if ($complaint['complainant_email']): ?>
                        <div>
                            <span style="font-size: 12px; color: var(--text-muted);">Email</span>
                            <p style="font-weight: 600; font-size: 14px; margin-top: 2px;"><?= esc($complaint['complainant_email']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div style="border-top: 1px solid var(--border-color); padding-top: 12px; margin-top: 12px; font-size: 12px; color: var(--text-muted);">
                <span>IP Address Pelapor:</span>
                <code style="display: block; background: rgba(0,0,0,0.2); padding: 4px 8px; border-radius: 4px; margin-top: 4px; color: var(--text-secondary);"><?= esc($complaint['ip_address']) ?></code>
            </div>
        </div>

        <!-- Replies Section Card -->
        <div class="card" style="padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px;">Kirim Tanggapan Resmi</h3>
            
            <?php if ($role === 'superadmin'): ?>
                <div style="background: #f8f9fa; border: 1px dashed #dee2e6; border-radius: 8px; padding: 16px; margin-bottom: 24px; text-align: center; color: var(--text-muted); font-size: 13px;">
                    <i class="fa-solid fa-lock" style="margin-bottom: 6px; display: block; font-size: 16px;"></i>
                    Super Admin hanya memantau dan tidak dapat mengirim tanggapan resmi ke aduan ini.
                </div>
            <?php else: ?>
                <form action="<?= base_url('admin/complaints/' . $complaint['id'] . '/reply') ?>" method="POST" style="margin-bottom: 24px;">
                    <?= csrf_field() ?>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <label class="form-label" for="message">Pesan Balasan</label>
                        <textarea name="message" id="message" class="form-control" placeholder="Tuliskan respon resmi Anda di sini..." required style="min-height: 100px; font-size: 14px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-hover) 100%); border:none; box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);">
                        Kirim Balasan <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            <?php endif; ?>

            <h4 style="font-size: 14px; font-weight: 600; color: var(--text-secondary); margin-bottom: 12px; border-top: 1px solid var(--border-color); padding-top: 16px;">Riwayat Balasan</h4>
            <?php if (empty($complaint['replies'])): ?>
                <p style="font-size: 13px; color: var(--text-muted); font-style: italic;">Belum ada balasan yang dikirim.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 12px; max-height: 300px; overflow-y: auto; padding-right: 4px;">
                    <?php foreach ($complaint['replies'] as $reply): ?>
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 12px;">
                            <div style="display: flex; justify-content: space-between; font-size: 11px; color: var(--text-muted); margin-bottom: 6px;">
                                <strong><?= esc($reply['admin_name'] ?: 'Admin') ?></strong>
                                <span><?= date('d M H:i', strtotime($reply['created_at'])) ?></span>
                            </div>
                            <p style="font-size: 13px; color: var(--text-primary); white-space: pre-line;"><?= esc($reply['message']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
