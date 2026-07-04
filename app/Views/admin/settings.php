<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Pengaturan Portal Sigap<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Konfigurasi status portal, parameter rate-limiting, SLA, dan integrasi API eksternal.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 28px; align-items: flex-start;">
    <!-- Main configuration settings -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        
        <!-- System Control (Maintenance & Rate Limit) -->
        <div class="card" style="padding: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; color: var(--text-main);">
                <i class="fa-solid fa-sliders"></i> Pengaturan Kontrol Sistem
            </h3>
            
            <form action="<?= base_url('admin/settings/update') ?>" method="POST">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="display: block; margin-bottom: 8px;">Mode Pemeliharaan (Maintenance Mode)</label>
                    <select name="maintenance_mode" class="form-input">
                        <option value="0" <?= !$settings['maintenance_mode'] ? 'selected' : '' ?>>Nonaktif (Portal dapat diakses publik)</option>
                        <option value="1" <?= $settings['maintenance_mode'] ? 'selected' : '' ?>>Aktif (Blokir akses publik & tampilkan layar pemeliharaan)</option>
                    </select>
                    <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">
                        Saat aktif, semua halaman publik dan API pengaduan akan mengembalikan respon pemeliharaan. Sesi admin tetap dapat diakses normal.
                    </small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="display: block; margin-bottom: 8px;">Batas Kirim Aduan Harian per Alamat IP</label>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <input type="number" name="rate_limit_per_day" class="form-input" style="width: 100px;" value="<?= esc($settings['rate_limit_per_day']) ?>" required min="1" max="100">
                        <span style="font-size: 14px; color: var(--text-main);">Aduan / Hari</span>
                    </div>
                    <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">
                        Batasan jumlah maksimal aduan yang dapat dikirimkan oleh satu alamat IP dalam 24 jam untuk mencegah spamming.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan Sistem
                </button>
            </form>
        </div>

        <!-- General Portal Configuration -->
        <div class="card" style="padding: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; color: var(--text-main);">
                <i class="fa-solid fa-gear"></i> Informasi Portal
            </h3>
            
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Nama Portal Pengaduan</label>
                <input type="text" class="form-input" value="Sigap" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Deskripsi Portal</label>
                <textarea class="form-input" rows="3" readonly style="resize: none;">Sigap — Suara Anda untuk Pelayanan yang Lebih Baik. Portal Pengaduan Terintegrasi DPMPTSP dan MPP.</textarea>
            </div>
        </div>

        <!-- SLA Config -->
        <div class="card" style="padding: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; color: var(--text-main);">
                <i class="fa-solid fa-business-time"></i> Service Level Agreement (SLA) & Monitoring
            </h3>
            
            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Batas Waktu Respon Awal (SLA)</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="number" class="form-input" value="3" readonly style="width: 80px;">
                        <span style="color: var(--text-muted); font-size: 14px;">Hari</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Batas Waktu Penyelesaian Laporan</label>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="number" class="form-input" value="7" readonly style="width: 80px;">
                        <span style="color: var(--text-muted); font-size: 14px;">Hari</span>
                    </div>
                </div>
            </div>
            <p style="font-size: 12px; color: var(--text-muted); margin-top: 12px;">
                Sistem akan secara otomatis menandai laporan sebagai <strong>Overdue</strong> di dashboard apabila belum diselesaikan melewati jangka waktu respon di atas.
            </p>
        </div>

        <!-- Integrations (WhatsApp & Email) -->
        <div class="card" style="padding: 24px;">
            <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; color: var(--text-main);">
                <i class="fa-solid fa-share-nodes"></i> Notifikasi & Integrasi Gateway (Future-Ready)
            </h3>
            
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
                    <div>
                        <h4 style="font-size: 14px; font-weight: 600; color: var(--text-main);">Notifikasi Email (Pelapor)</h4>
                        <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Kirim otomatis nomor tiket & PIN ke email pelapor setelah kirim laporan.</p>
                    </div>
                    <span class="badge badge-rejected" style="font-size: 10px;">Nonaktif</span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.02); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color);">
                    <div>
                        <h4 style="font-size: 14px; font-weight: 600; color: var(--text-main);">WhatsApp Gateway (Fonnte API)</h4>
                        <p style="font-size: 12px; color: var(--text-muted); margin-top: 2px;">Kirim update status aduan otomatis via WhatsApp kepada pelapor.</p>
                    </div>
                    <span class="badge badge-rejected" style="font-size: 10px;">Nonaktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Side card config -->
    <div>
        <div class="card" style="padding: 24px; background: rgba(99, 102, 241, 0.02); border-color: rgba(99, 102, 241, 0.2);">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px; color: var(--primary);">System Information</h3>
            <div style="display: flex; flex-direction: column; gap: 10px; font-size: 13px;">
                <div>
                    <span style="color: var(--text-muted);">Application:</span>
                    <p style="font-weight: 600; color: var(--text-main);">Sigap v1.0-beta</p>
                </div>
                <div>
                    <span style="color: var(--text-muted);">Environment:</span>
                    <p style="font-weight: 600; color: var(--warning);"><?= ENVIRONMENT ?></p>
                </div>
                <div>
                    <span style="color: var(--text-muted);">PHP Version:</span>
                    <p style="font-weight: 600; color: var(--text-main);"><?= PHP_VERSION ?></p>
                </div>
                <div>
                    <span style="color: var(--text-muted);">Database:</span>
                    <p style="font-weight: 600; color: var(--text-main);">MySQLi (MariaDB)</p>
                </div>
                <div>
                    <span style="color: var(--text-muted);">CI Version:</span>
                    <p style="font-weight: 600; color: var(--text-main);"><?= \CodeIgniter\CodeIgniter::CI_VERSION ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
