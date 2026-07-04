<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>Kelola Kategori Aduan<?= $this->endSection() ?>

<?= $this->section('page_subtitle') ?>Manajemen daftar kategori pengaduan terdaftar untuk masing-masing lokasi pelayanan.<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">
    
    <!-- Category List -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-main);">Daftar Kategori Aduan</h3>
        
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kategori Aduan</th>
                        <th>Lokasi Kerja</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($categories as $cat): ?>
                        <tr>
                            <td><strong><?= $i++ ?></strong></td>
                            <td style="font-weight: 600; color: var(--text-main);"><?= esc($cat['name']) ?></td>
                            <td>
                                <span class="badge <?= ((int)$cat['location_id'] === 1) ? 'badge-processing' : 'badge-verified' ?>">
                                    <?= esc($cat['location_name']) ?>
                                </span>
                            </td>
                            <td>
                                <form action="<?= base_url('admin/categories/delete/' . $cat['id']) ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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

    <!-- Create Category Form -->
    <div class="card" style="padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-main);">Tambah Kategori Baru</h3>
        
        <form action="<?= base_url('admin/categories/create') ?>" method="POST">
            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Lokasi Kerja Tujuan</label>
                <select name="location_id" class="form-input" required>
                    <option value="">-- Pilih Lokasi --</option>
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 16px;">
                <label class="form-label">Nama Kategori Aduan</label>
                <input type="text" name="name" class="form-input" placeholder="Contoh: Pungutan Liar, Fasilitas Rusak, Sikap Petugas..." required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 12px; padding: 12px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Kategori
            </button>
        </form>
    </div>

</div>

<?= $this->endSection() ?>
