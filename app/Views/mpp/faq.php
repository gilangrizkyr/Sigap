<?php
ob_start(); ?>

<!-- Header -->
<div style="text-align: center; max-width: 800px; margin: 0 auto 20px;">
    <h1 style="font-size: 36px; font-weight: 800; line-height: 1.25; margin-bottom: 10px; color: #ffffff;">
        FAQ — Portal Pengaduan MPP
    </h1>
    <p style="color: rgba(255,255,255,0.9); font-size: 16px; margin-bottom: 0; line-height: 1.6;">
        Pertanyaan yang sering diajukan seputar cara menggunakan portal Sigap untuk Mal Pelayanan Publik.
    </p>
</div>

<!-- FAQ Card -->
<div class="card" style="padding: 32px; color: #111827; max-width: 800px; margin: 0 auto 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
    <?php $faqs = [
        [
            'q' => 'Apa saja jenis laporan yang bisa dikirim melalui portal ini untuk MPP?',
            'a' => 'Tersedia empat jenis laporan: <strong>Pengaduan</strong> (keluhan layanan loket tidak memuaskan), <strong>Aspirasi</strong> (saran peningkatan fasilitas/layanan MPP), <strong>Apresiasi</strong> (pujian untuk petugas/loket berprestasi), dan <strong>Saran</strong> (masukan umum untuk pengelola MPP).'
        ],
        [
            'q' => 'Bisakah laporan saya ditujukan ke loket instansi tertentu di dalam MPP?',
            'a' => 'Ya. Saat mengisi formulir, Anda bisa memilih <strong>Unit Layanan</strong> secara spesifik — misalnya loket Imigrasi, Samsat, BPJS Kesehatan, Disdukcapil, dan sebagainya — agar laporan langsung diteruskan ke unit yang tepat.'
        ],
        [
            'q' => 'Apakah saya wajib mengisi nama dan data diri saat melapor?',
            'a' => 'Tidak wajib. Aktifkan toggle <strong>"Lapor Secara Anonim"</strong> di bagian atas formulir. Kolom nama, WhatsApp, dan email akan otomatis tersembunyi dan tidak ada data identitas Anda yang tersimpan.'
        ],
        [
            'q' => 'Bagaimana cara melacak status laporan setelah dikirim?',
            'a' => 'Setiap laporan menghasilkan <strong>Nomor Tiket</strong> dan <strong>PIN Rahasia</strong>. Simpan keduanya, lalu kunjungi menu <strong>Lacak Aduan</strong> untuk memantau progres dan membaca tanggapan resmi dari pengelola MPP.'
        ],
        [
            'q' => 'Bisakah saya melampirkan bukti foto atau dokumen PDF?',
            'a' => 'Ya. Tersedia kolom <strong>Upload Lampiran Bukti</strong> di formulir yang mendukung format JPG, PNG, dan PDF dengan ukuran maksimal <strong>5 MB</strong>. Melampirkan bukti sangat dianjurkan agar laporan diproses lebih cepat dan akurat.'
        ],
        [
            'q' => 'Apakah data dan identitas saya aman di portal ini?',
            'a' => 'Ya. Seluruh data dienkripsi. Laporan hanya dapat diakses menggunakan kombinasi nomor tiket dan PIN yang hanya Anda miliki. Petugas hanya dapat melihat isi laporan untuk keperluan tindak lanjut.'
        ],
    ]; ?>

    <div style="display: flex; flex-direction: column; gap: 0;">
        <?php foreach ($faqs as $i => $item): ?>
        <div style="border-bottom: 1px solid #f3f4f6; <?= $i === count($faqs)-1 ? 'border-bottom: none;' : '' ?>">
            <button onclick="toggleFaq(this)" style="width:100%; text-align:left; background:none; border:none; padding:18px 0; display:flex; justify-content:space-between; align-items:flex-start; gap:16px; cursor:pointer;">
                <span style="font-weight:700; font-size:15px; color:#111827; line-height:1.4; flex:1;"><?= $item['q'] ?></span>
                <span class="faq-chevron" style="color:#b71c1c; flex-shrink:0; font-size:13px; padding-top:2px; transition:transform 0.3s ease;">▼</span>
            </button>
            <div class="faq-body" style="max-height:0; overflow:hidden; transition:max-height 0.4s cubic-bezier(0.4,0,0.2,1);">
                <div style="padding-bottom:16px; font-size:14.5px; color:#4b5563; line-height:1.7;">
                    <?= $item['a'] ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- CTA -->
<div style="text-align:center; margin-bottom: 20px;">
    <a href="<?= base_url('mpp') ?>" style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.15); border:1.5px solid rgba(255,255,255,0.4); color:#ffffff; padding:11px 26px; border-radius:30px; text-decoration:none; font-weight:700; font-size:14px; backdrop-filter:blur(8px); transition:all 0.3s ease;">
        <i class="fa-solid fa-paper-plane"></i> Kirim Laporan ke MPP
    </a>
</div>

<script>
function toggleFaq(btn) {
    const body = btn.nextElementSibling;
    const icon = btn.querySelector('.faq-chevron');
    const isOpen = body.style.maxHeight && body.style.maxHeight !== '0px';
    document.querySelectorAll('.faq-body').forEach(el => el.style.maxHeight = '0');
    document.querySelectorAll('.faq-chevron').forEach(el => el.style.transform = 'rotate(0deg)');
    if (!isOpen) {
        body.style.maxHeight = body.scrollHeight + 50 + 'px';
        icon.style.transform = 'rotate(180deg)';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const first = document.querySelector('[onclick="toggleFaq(this)"]');
    if (first) toggleFaq(first);
});
</script>

<?php $hero_content = ob_get_clean();

echo view('layouts/mpp', [
    'page_title'   => 'FAQ MPP — Sigap',
    'hero_content' => $hero_content,
]);
