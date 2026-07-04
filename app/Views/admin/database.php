<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Pemeliharaan & Backup Database<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Ekspor cadangan basis data (mysqldump) atau impor file backup untuk pemulihan sistem Sigap.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start;">
    
    <!-- Backup Card -->
    <div class="card" style="padding: 32px; text-align: center;">
        <div style="font-size: 48px; color: var(--primary); margin-bottom: 20px;">
            <i class="fa-solid fa-cloud-arrow-down"></i>
        </div>
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--text-main);">Cadangkan Basis Data (Backup)</h3>
        <p style="color: var(--text-muted); font-size: 13px; line-height: 1.6; margin-bottom: 24px;">
            Unduh seluruh struktur tabel dan data basis data Sigap saat ini dalam format SQL. Disarankan melakukan backup sebelum melakukan pembaruan sistem.
        </p>

        <form action="<?= base_url('admin/database/backup') ?>" method="POST">
            <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                <i class="fa-solid fa-download"></i> Unduh File SQL Backup
            </button>
        </form>
    </div>

    <!-- Restore Card -->
    <div class="card" style="padding: 32px;">
        <div style="font-size: 48px; color: var(--warning); margin-bottom: 20px; text-align: center;">
            <i class="fa-solid fa-cloud-arrow-up"></i>
        </div>
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 12px; color: var(--text-main); text-align: center;">Pulihkan Basis Data (Restore)</h3>
        <p style="color: var(--text-muted); font-size: 13px; line-height: 1.6; margin-bottom: 24px; text-align: center;">
            Unggah file backup basis data (.sql) untuk me-restore seluruh data sistem. <br>
            <strong style="color: #ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Peringatan: Tindakan ini akan menimpa data yang ada saat ini!</strong>
        </p>

        <form action="<?= base_url('admin/database/restore') ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Pilih File SQL Backup</label>
                <input type="file" name="backup_file" accept=".sql" class="form-input" style="padding: 8px;" required>
            </div>

            <button type="submit" class="btn btn-secondary" style="width: 100%; padding: 12px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); color: #f59e0b;" onclick="return confirm('Apakah Anda yakin ingin memulihkan database? Seluruh data saat ini akan diganti oleh file backup!')">
                <i class="fa-solid fa-upload"></i> Mulai Proses Restore
            </button>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
