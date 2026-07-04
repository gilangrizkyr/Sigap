<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Kelola Lokasi Pelayanan<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Manajemen lokasi/unit kerja utama di sistem Sigap.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    
    <!-- Location List Section -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-main);">Daftar Lokasi Kerja</h3>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($locations as $loc): ?>
                        <tr>
                            <td><strong><?= $i++ ?></strong></td>
                            <td style="font-weight: 600; color: var(--text-main);"><?= esc($loc['name']) ?></td>
                            <td>
                                <form action="<?= base_url('admin/locations/delete/' . $loc['id']) ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')">
                                    <button type="submit" class="btn btn-secondary" style="padding: 6px 10px; font-size: 12px; color: #ef4444;" <?= in_array((int)$loc['id'], [1, 2]) ? 'disabled title="Sistem dasar tidak dapat dihapus"' : '' ?>>
                                        <i class="fa-solid fa-trash-can"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Location Form -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-main);">Tambah Lokasi Baru</h3>
        
        <form action="<?= base_url('admin/locations/create') ?>" method="POST">
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Nama Lokasi</label>
                <input type="text" name="name" class="form-input" placeholder="Masukkan nama lokasi..." required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 12px; padding: 12px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Lokasi
            </button>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
