<?php

// ─── Hero Content (inside red banner) ───────────────────────
ob_start(); ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger" style="margin-bottom: 20px;">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div><?= session()->getFlashdata('error') ?></div>
    </div>
<?php endif; ?>

<!-- Center Header -->
<div style="text-align: center; max-width: 800px; margin: 0 auto 20px;">
    <h1 style="font-size: 34px; font-weight: 800; line-height: 1.2; margin-bottom: 12px; color: #ffffff; letter-spacing: -0.01em;">
        <span style="font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: rgba(255, 255, 255, 0.65); display: block; margin-bottom: 6px;">Portal Aspirasi Masyarakat</span>
        DPMPTSP Tanah Bumbu
    </h1>
    <p style="color: rgba(255,255,255,0.95); font-size: 15px; margin-bottom: 0; line-height: 1.6; max-width: 720px; margin-left: auto; margin-right: auto;">
        Sampaikan pengaduan, aspirasi, atau saran Anda secara langsung kepada Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu (DPMPTSP) Kabupaten Tanah Bumbu secara transparan, akurat, dan terlacak.
    </p>
</div>

<!-- Guideline Steps -->
<div style="max-width: 850px; margin: 0 auto 30px; padding-top: 0;">
    <div class="steps-wrapper">
        <div class="steps-line"></div>
        <div class="grid-steps">
            <div class="step-card">
                <div class="step-number">1</div>
                <h5 class="step-title">Tulis Laporan</h5>
                <p class="step-desc">Isi klasifikasi, judul, dan detail laporan Anda.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h5 class="step-title">Unggah Bukti</h5>
                <p class="step-desc">Lampirkan foto atau dokumen pendukung jika ada.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h5 class="step-title">Pantau Status</h5>
                <p class="step-desc">Cek tindak lanjut laporan dengan kode tiket.</p>
            </div>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="card" style="padding: 32px; color: #111827; max-width: 800px; margin: 0 auto 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
    <form action="<?= base_url('dpmptsp/submit') ?>" method="POST" enctype="multipart/form-data" id="complaintForm">
        <?= csrf_field() ?>
        <input type="hidden" name="complaint_type" id="complaint_type" value="Pengaduan">

        <!-- Klasifikasi -->
        <div class="form-group" style="margin-bottom: 20px;">
            <label class="form-label" style="font-weight: 600; margin-bottom: 8px; color: #374151;">
                Pilih Klasifikasi Laporan <span style="color: var(--danger)">*</span>
            </label>
            <div class="tab-container">
                <button type="button" class="btn-type active" onclick="setType('Pengaduan', this)">PENGADUAN</button>
                <button type="button" class="btn-type" onclick="setType('Aspirasi', this)">ASPIRASI</button>
                <button type="button" class="btn-type" onclick="setType('Saran', this)">SARAN</button>
                <button type="button" class="btn-type" onclick="setType('Apresiasi', this)">APRESIASI</button>
            </div>
        </div>

        <!-- Identitas Pelapor -->
        <div class="identity-card" style="padding: 20px; margin: 20px 0 28px 0;">
            <div class="identity-toggle" style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                <div style="display:flex; align-items:center; gap:8px; color: #d32f2f; font-weight: 600;">
                    <i class="fa-solid fa-user-secret"></i>
                    <span>Identitas Pelapor</span>
                </div>
                <label class="switch-container">
                    <span class="switch-title">Kirim Sebagai Anonim</span>
                    <span class="switch">
                        <input type="checkbox" id="anonymous_checkbox" onclick="toggleAnonymous(this.checked)">
                        <span class="slider"></span>
                    </span>
                </label>
            </div>
            <div id="identity-fields-wrapper" style="margin-top: 15px;">
                <p style="font-size: 12px; color: #6b7280; margin: 0 0 16px;">
                    Silakan isi data identitas Anda di bawah ini. Data Anda akan dijamin kerahasiaannya oleh sistem.
                </p>
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" for="complainant_name" style="color: #374151;">Nama Pelapor</label>
                    <input type="text" name="complainant_name" id="complainant_name" class="form-control"
                         placeholder="Nama Lengkap Anda" value="<?= old('complainant_name') ?>">
                </div>
                <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="complainant_phone" style="color: #374151;">No. WhatsApp</label>
                        <input type="text" name="complainant_phone" id="complainant_phone" class="form-control"
                            placeholder="0812xxxxxx" value="<?= old('complainant_phone') ?>">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label" for="complainant_email" style="color: #374151;">Email</label>
                        <input type="email" name="complainant_email" id="complainant_email" class="form-control"
                            placeholder="nama@email.com" value="<?= old('complainant_email') ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori -->
        <div class="form-group">
            <label class="form-label" for="category_id" style="color: #374151;">Kategori Layanan <span style="color: var(--danger)">*</span></label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="" disabled selected>Pilih Kategori...</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                        <?= esc($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Judul -->
        <div class="form-group">
            <label class="form-label" for="title" style="color: #374151;">Judul Laporan <span style="color: var(--danger)">*</span></label>
            <input type="text" name="title" id="title" class="form-control"
                placeholder="Tuliskan intisari laporan secara singkat" value="<?= old('title') ?>" required>
        </div>

        <!-- Kronologi -->
        <div class="form-group">
            <label class="form-label" for="description" style="color: #374151;">Isi Laporan / Kronologi <span style="color: var(--danger)">*</span></label>
            <textarea name="description" id="description" class="form-control"
                placeholder="Ketikkan detail kronologi atau keluhan Anda..." required><?= old('description') ?></textarea>
        </div>

        <!-- Lampiran -->
        <div class="form-group">
            <label class="form-label" for="attachments" style="color: #374151; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-paperclip" style="color: #d32f2f;"></i>
                Upload Lampiran Bukti
                <span style="font-size: 11px; background: #fef9c3; color: #92400e; border: 1px solid #fde68a; border-radius: 4px; padding: 1px 6px; font-weight: 600; margin-left: 4px;">Opsional</span>
            </label>

            <!-- Callout info -->
            <div style="background: #fffbeb; border: 1px solid #fbbf24; border-left: 4px solid #f59e0b; border-radius: 8px; padding: 10px 14px; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-bolt" style="color: #d97706; flex-shrink: 0;"></i>
                <span style="font-size: 12.5px; color: #92400e;"><strong>Laporan dengan bukti diproses lebih cepat</strong> — sertakan foto atau dokumen jika ada.</span>
            </div>

            <input type="file" name="attachments" id="attachments" class="form-control">
            <span style="font-size: 11px; color: var(--text-muted); display: block; margin-top: 6px;">
                <i class="fa-solid fa-circle-info" style="margin-right: 3px;"></i>
                Format didukung: JPG, JPEG, PNG, PDF &nbsp;·&nbsp; Ukuran maksimal 5 MB per file.
            </span>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
            <button type="reset" class="btn btn-secondary" style="padding: 10px 20px;">Reset</button>
            <button type="submit" class="btn btn-primary"
                style="padding: 10px 24px; background: #d32f2f; border-color: #d32f2f; box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3);">
                Kirim Laporan <i class="fa-solid fa-paper-plane" style="margin-left: 4px;"></i>
            </button>
        </div>
    </form>
</div>

<div class="form-privacy-note">
    <i class="fa-solid fa-shield-halved"></i>
    <span>Kerahasiaan data identitas Anda dijamin aman dan terlindungi oleh sistem.</span>
</div>

<?php $hero_content = ob_get_clean();

// ─── Render via layout ───────────────────────────────────────
echo view('layouts/dpmptsp', [
    'page_title'    => 'Form Pengaduan DPMPTSP — Sigap',
    'hero_content'  => $hero_content,
]);
