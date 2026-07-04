<?php
ob_start(); ?>

<!-- Header -->
<div style="text-align: center; max-width: 800px; margin: 0 auto 20px;">
    <h1 style="font-size: 36px; font-weight: 800; line-height: 1.25; margin-bottom: 10px; color: #ffffff;">
        Tentang Portal — MPP
    </h1>
    <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin-bottom: 0; line-height: 1.6;">
        Kenali Sigap sebagai saluran pengaduan resmi untuk layanan Mal Pelayanan Publik.
    </p>
</div>

<!-- About Card -->
<div class="card" style="padding: 32px; color: #111827; max-width: 800px; margin: 0 auto 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">

    <!-- Intro -->
    <div style="margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid #f3f4f6;">
        <h2 style="font-size: 17px; font-weight: 800; color: #b71c1c; margin-bottom: 10px;">
            <i class="fa-solid fa-comments" style="margin-right: 8px;"></i>Apa itu Sigap?
        </h2>
        <p style="font-size: 14.5px; color: #4b5563; line-height: 1.7; margin: 0;">
            <strong style="color:#111827;">Sigap</strong> adalah portal pengaduan publik berbasis web yang menjadi saluran resmi bagi masyarakat untuk menyampaikan laporan terkait layanan di <strong style="color:#111827;">Mal Pelayanan Publik (MPP)</strong>. Dengan fitur pemilihan unit layanan per loket instansi, laporan Anda diarahkan langsung ke unit yang tepat — tanpa perlu antre fisik hanya untuk menyampaikan keluhan atau saran.
        </p>
    </div>

    <!-- Features -->
    <div style="margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid #f3f4f6;">
        <h2 style="font-size: 17px; font-weight: 800; color: #b71c1c; margin-bottom: 16px;">
            <i class="fa-solid fa-star" style="margin-right: 8px;"></i>Fitur Unggulan
        </h2>
        <div class="about-features-grid">
            <?php $features = [
                ['icon'=>'fa-building-columns','title'=>'Lapor Per Unit Loket','desc'=>'Pilih instansi spesifik (Imigrasi, Samsat, BPJS, dll.) agar laporan tepat sasaran ke unit yang dimaksud.'],
                ['icon'=>'fa-user-secret','title'=>'Laporan Anonim','desc'=>'Toggle anonim menyembunyikan semua kolom identitas. Tidak ada data yang tersimpan.'],
                ['icon'=>'fa-ticket-simple','title'=>'Tiket & PIN Unik','desc'=>'Setiap laporan menghasilkan tiket dan PIN untuk melacak status secara mandiri kapan saja.'],
                ['icon'=>'fa-paperclip','title'=>'Upload Bukti Pendukung','desc'=>'Lampirkan foto atau PDF (maks. 5 MB) agar laporan diproses lebih cepat dan akurat.'],
            ]; ?>
            <?php foreach ($features as $f): ?>
            <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px;">
                <i class="fa-solid <?= $f['icon'] ?>" style="color: #d32f2f; font-size: 16px; margin-bottom: 8px; display: block;"></i>
                <div style="font-weight: 700; color: #111827; font-size: 14px; margin-bottom: 4px;"><?= $f['title'] ?></div>
                <div style="font-size: 13px; color: #6b7280; line-height: 1.5;"><?= $f['desc'] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Steps -->
    <div>
        <h2 style="font-size: 17px; font-weight: 800; color: #b71c1c; margin-bottom: 16px;">
            <i class="fa-solid fa-list-check" style="margin-right: 8px;"></i>Alur Pengiriman Laporan
        </h2>
        <div style="display: flex; flex-direction: column; gap: 14px;">
            <?php $steps = [
                ['n'=>'1','t'=>'Pilih Unit & Isi Formulir','d'=>'Pilih unit layanan MPP yang dituju, pilih jenis laporan, isi judul dan kronologi dengan jelas.'],
                ['n'=>'2','t'=>'Lampirkan Bukti (Opsional)','d'=>'Upload foto atau PDF sebagai bukti pendukung. Maksimal 5 MB per file.'],
                ['n'=>'3','t'=>'Kirim — Tiket & PIN Terbit','d'=>'Sistem menerbitkan Nomor Tiket dan PIN Rahasia secara otomatis. Simpan keduanya baik-baik.'],
                ['n'=>'4','t'=>'Petugas Unit Memproses','d'=>'Petugas unit MPP yang bersangkutan menerima dan menindaklanjuti laporan Anda.'],
                ['n'=>'5','t'=>'Pantau via Lacak Aduan','d'=>'Gunakan tiket & PIN di halaman Lacak Aduan untuk memantau status dan membaca tanggapan.'],
            ]; ?>
            <?php foreach ($steps as $s): ?>
            <div style="display: flex; gap: 14px; align-items: flex-start;">
                <div style="flex-shrink:0; width:30px; height:30px; background:#d32f2f; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:13px;"><?= $s['n'] ?></div>
                <div>
                    <div style="font-weight: 700; font-size: 14.5px; color: #111827; margin-bottom: 2px;"><?= $s['t'] ?></div>
                    <div style="font-size: 13.5px; color: #6b7280; line-height: 1.5;"><?= $s['d'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- CTA -->
<div style="text-align:center; margin-bottom: 20px;">
    <a href="<?= base_url('mpp') ?>" style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.15); border:1.5px solid rgba(255,255,255,0.4); color:#ffffff; padding:11px 26px; border-radius:30px; text-decoration:none; font-weight:700; font-size:14px; backdrop-filter:blur(8px); transition:all 0.3s ease;">
        <i class="fa-solid fa-paper-plane"></i> Mulai Kirim Laporan ke MPP
    </a>
</div>

<?php $hero_content = ob_get_clean();

echo view('layouts/mpp', [
    'page_title'   => 'Tentang Portal MPP — Sigap',
    'hero_content' => $hero_content,
]);
