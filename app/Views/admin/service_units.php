<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Kelola Sub-Unit Layanan (MPP / DPMPTSP)<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Manajemen daftar bidang/sub-unit/loket pelayanan terdaftar.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    
    <!-- Service Unit List -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-main);">Daftar Sub-Unit Layanan</h3>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Sub-Unit</th>
                        <th>Lokasi Induk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($service_units as $unit): ?>
                        <tr>
                            <td><strong><?= $i++ ?></strong></td>
                            <td style="font-weight: 600; color: var(--text-main);"><?= esc($unit['name']) ?></td>
                            <td>
                                <span class="badge <?= ((int)$unit['location_id'] === 1) ? 'badge-processing' : 'badge-verified' ?>">
                                    <?= esc($unit['location_name']) ?>
                                </span>
                            </td>
                            <td>
                                <form action="<?= base_url('admin/service-units/delete/' . $unit['id']) ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit layanan ini?')">
                                    <button type="submit" class="btn btn-secondary" style="padding: 6px 10px; font-size: 12px; color: #ef4444;">
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

    <!-- Create Service Unit Form -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-main);">Tambah Sub-Unit Baru</h3>
        
        <form action="<?= base_url('admin/service-units/create') ?>" method="POST">
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Lokasi Kerja Induk</label>
                <select name="location_id" class="form-input" required>
                    <option value="">-- Pilih Lokasi Induk --</option>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Nama Sub-Unit Layanan</label>
                <input type="text" name="name" class="form-input" placeholder="Contoh: Loket BPJS Kesehatan, Bidang Perizinan..." required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 12px; padding: 12px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Sub-Unit
            </button>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
