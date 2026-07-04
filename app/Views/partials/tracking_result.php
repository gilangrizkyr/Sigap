<?php
/**
 * Reusable Tracking Result Partial
 * Variables: $complaint (array)
 */

// ── Status config — palet hangat sinkron dengan tema merah portal ──
$statusConfig = [
    // Terkirim — rose merah muda (entry point, nada lembut)
    'submitted'        => ['label' => 'Terkirim',              'color' => '#e11d48', 'bg' => '#fff1f2', 'border' => '#fecdd3', 'icon' => 'fa-paper-plane'],
    // Menunggu — kuning amber (hangat, sejalan dengan nuansa merah)
    'pending'          => ['label' => 'Menunggu Verifikasi',   'color' => '#b45309', 'bg' => '#fefce8', 'border' => '#fde68a', 'icon' => 'fa-hourglass-half'],
    // Diproses — merah-oranye tua (masih dalam family warm)
    'processing'       => ['label' => 'Sedang Diproses',       'color' => '#c2410c', 'bg' => '#fff7ed', 'border' => '#fed7aa', 'icon' => 'fa-gear'],
    // Menunggu respons — mauve/merah muda tua (warm purple-red)
    'waiting_response' => ['label' => 'Menunggu Respons',      'color' => '#9d174d', 'bg' => '#fdf2f8', 'border' => '#fbcfe8', 'icon' => 'fa-comment-dots'],
    // Selesai — hijau (universal sukses, tetap dipertahankan)
    'resolved'         => ['label' => 'Selesai / Tuntas',      'color' => '#047857', 'bg' => '#ecfdf5', 'border' => '#a7f3d0', 'icon' => 'fa-circle-check'],
    // Ditolak — merah gelap (konsisten dengan tema utama portal)
    'rejected'         => ['label' => 'Tidak Dapat Diproses',  'color' => '#991b1b', 'bg' => '#fef2f2', 'border' => '#fecaca', 'icon' => 'fa-circle-xmark'],
    // Ditutup — abu coklat hangat (warm neutral)
    'closed'           => ['label' => 'Ditutup',               'color' => '#78350f', 'bg' => '#fafaf9', 'border' => '#e7e5e4', 'icon' => 'fa-folder-closed'],
];

// ── Jenis laporan → Bahasa Indonesia ─────────────────────────
$typeLabels = [
    'complaint'    => 'Pengaduan',
    'aspiration'   => 'Aspirasi',
    'suggestion'   => 'Saran',
    'appreciation' => 'Apresiasi',
    'saran'        => 'Saran',
    'pengaduan'    => 'Pengaduan',
    'aspirasi'     => 'Aspirasi',
    'apresiasi'    => 'Apresiasi',
];

$st = $complaint['status'];
$sc = $statusConfig[$st] ?? ['label' => ucfirst(str_replace('_', ' ', $st)), 'color' => '#6b7280', 'bg' => '#f9fafb', 'border' => '#e5e7eb', 'icon' => 'fa-circle-question'];

$rawType  = $complaint['complaint_type'] ?? '';
$typeLabel = $typeLabels[strtolower($rawType)] ?? ucfirst($rawType);
?>

<div style="padding: 40px 0 60px;">
    <div class="container">

        <!-- ── Top Status Banner ─────────────────────────────── -->
        <div style="background: <?= $sc['bg'] ?>; border: 1px solid <?= $sc['border'] ?>; border-radius: 20px; padding: 24px 30px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 52px; height: 52px; background: <?= $sc['color'] ?>1a; border: 2px solid <?= $sc['color'] ?>40; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fa-solid <?= $sc['icon'] ?>" style="color: <?= $sc['color'] ?>; font-size: 20px;"></i>
                </div>
                <div>
                    <p style="font-size: 12px; font-weight: 700; color: <?= $sc['color'] ?>; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 4px;">Status Laporan</p>
                    <h2 style="font-size: 22px; font-weight: 900; color: #111827; margin: 0; letter-spacing: -0.01em;"><?= $sc['label'] ?></h2>
                </div>
            </div>
            <div style="text-align: right;">
                <p style="font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 600; margin: 0 0 4px;">Nomor Tiket</p>
                <p style="font-size: 17px; font-weight: 800; color: #111827; margin: 0; font-family: monospace; letter-spacing: 0.03em;"><?= esc($complaint['ticket_number']) ?></p>
                <p style="font-size: 12px; color: #9ca3af; margin: 4px 0 0;"><?= date('d M Y, H:i', strtotime($complaint['created_at'])) ?> WIB</p>
            </div>
        </div>

        <!-- ── Main 2-Col Layout ─────────────────────────────── -->
        <div class="tracking-grid">

            <!-- ── LEFT COLUMN: Complaint Details ── -->
            <div style="display: flex; flex-direction: column; gap: 20px;">

                <!-- Complaint Header Card -->
                <div style="background: #ffffff; border: 1px solid #f0e8e8; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,0.04);">
                    <div style="background: linear-gradient(135deg, #b71c1c 0%, #7f0000 100%); padding: 20px 24px; position: relative; overflow: hidden;">
                        <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px); background-size: 18px 18px;"></div>
                        <div style="position: relative; z-index: 1;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                                <span style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #ffffff; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 99px; text-transform: uppercase; letter-spacing: 0.06em;"><?= esc($typeLabel) ?></span>
                                <span style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: rgba(255,255,255,0.9); font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 99px;"><?= esc($complaint['category_name']) ?></span>
                            </div>
                            <h3 style="font-size: 19px; font-weight: 800; color: #ffffff; margin: 0; line-height: 1.3;"><?= esc($complaint['title']) ?></h3>
                        </div>
                    </div>
                    <div style="padding: 20px 24px;">
                        <p style="font-size: 11px; color: #9ca3af; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 10px;">Isi Laporan</p>
                        <p style="white-space: pre-line; color: #374151; font-size: 14.5px; line-height: 1.75; margin: 0;"><?= esc($complaint['description']) ?></p>
                    </div>
                    <?php if (!empty($complaint['service_unit_name'])): ?>
                    <div style="border-top: 1px solid #f3f4f6; padding: 14px 24px; display: flex; align-items: center; gap: 10px; background: #fafafa;">
                        <i class="fa-solid fa-building-columns" style="color: #b71c1c; font-size: 13px;"></i>
                        <span style="font-size: 13px; color: #374151; font-weight: 600;"><?= esc($complaint['location_name']) ?> — <?= esc($complaint['service_unit_name']) ?></span>
                    </div>
                    <?php else: ?>
                    <div style="border-top: 1px solid #f3f4f6; padding: 14px 24px; display: flex; align-items: center; gap: 10px; background: #fafafa;">
                        <i class="fa-solid fa-building-columns" style="color: #b71c1c; font-size: 13px;"></i>
                        <span style="font-size: 13px; color: #374151; font-weight: 600;"><?= esc($complaint['location_name']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Attachments -->
                <?php if (!empty($complaint['attachments'])): ?>
                <div style="background: #ffffff; border: 1px solid #f0e8e8; border-radius: 18px; padding: 20px 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.04);">
                    <p style="font-size: 11px; color: #9ca3af; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-paperclip" style="color: #b71c1c;"></i> Berkas Lampiran
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php foreach ($complaint['attachments'] as $att): ?>
                            <?php if (str_contains($att['file_type'], 'image')): ?>
                                <img src="<?= base_url($att['file_path']) ?>" alt="Bukti aduan"
                                     style="width: 100%; border-radius: 10px; border: 1px solid #f0e8e8; max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <a href="<?= base_url($att['file_path']) ?>" target="_blank"
                                   style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 16px; background: #fff5f5; border: 1px solid #fecaca; border-radius: 10px; color: #b71c1c; font-weight: 600; font-size: 13px; text-decoration: none;">
                                    <i class="fa-solid fa-file-pdf"></i> Unduh Berkas PDF
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Admin Replies -->
                <div style="background: #ffffff; border: 1px solid #f0e8e8; border-radius: 18px; padding: 22px 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.04);">
                    <p style="font-size: 11px; color: #9ca3af; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 16px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-comments" style="color: #b71c1c;"></i> Tanggapan Resmi
                    </p>
                    <?php if (empty($complaint['replies'])): ?>
                        <div style="display: flex; flex-direction: column; align-items: center; padding: 30px 16px; text-align: center;">
                            <div style="width: 52px; height: 52px; background: #f9fafb; border-radius: 14px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                                <i class="fa-regular fa-comment-dots" style="color: #d1d5db; font-size: 22px;"></i>
                            </div>
                            <p style="font-size: 14px; color: #9ca3af; margin: 0; font-weight: 500;">Belum ada tanggapan resmi dari pihak terkait.</p>
                            <p style="font-size: 12px; color: #d1d5db; margin: 6px 0 0;">Petugas akan merespons dalam 1×24 jam kerja.</p>
                        </div>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 14px;">
                            <?php foreach ($complaint['replies'] as $reply): ?>
                            <div style="background: linear-gradient(135deg, #fff8f8 0%, #fff5f5 100%); border: 1px solid #fecaca; border-radius: 14px; padding: 16px 18px;">
                                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div style="width: 30px; height: 30px; background: #b71c1c; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fa-solid fa-user-tie" style="color: #ffffff; font-size: 12px;"></i>
                                        </div>
                                        <span style="font-size: 13px; font-weight: 700; color: #b71c1c;"><?= esc($reply['admin_name'] ?: 'Admin') ?></span>
                                        <span style="font-size: 11px; background: #fee2e2; color: #b71c1c; padding: 2px 8px; border-radius: 99px; font-weight: 600;">Tanggapan Resmi</span>
                                    </div>
                                    <span style="font-size: 12px; color: #9ca3af;"><?= date('d M Y, H:i', strtotime($reply['created_at'])) ?></span>
                                </div>
                                <p style="white-space: pre-line; color: #374151; font-size: 13.5px; line-height: 1.65; margin: 0;"><?= esc($reply['message']) ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- ── RIGHT COLUMN: Timeline + Receipt ── -->
            <div style="display: flex; flex-direction: column; gap: 20px;">

                <!-- Status Timeline -->
                <div style="background: #ffffff; border: 1px solid #f0e8e8; border-radius: 18px; padding: 22px 24px; box-shadow: 0 2px 16px rgba(0,0,0,0.04);">
                    <p style="font-size: 11px; color: #9ca3af; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin: 0 0 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-timeline" style="color: #b71c1c;"></i> Riwayat Status
                    </p>
                    <div style="position: relative;">
                        <!-- Vertical line -->
                        <div style="position: absolute; left: 17px; top: 4px; bottom: 4px; width: 2px; background: linear-gradient(180deg, #b71c1c40 0%, #e5e7eb 100%);"></div>
                        <div style="display: flex; flex-direction: column; gap: 0;">
                        <?php
                        $logs = $complaint['status_logs'];
                        foreach ($logs as $idx => $log):
                            $isLast = ($idx === 0);
                            $logSt = $log['new_status'];
                            $logSc = $statusConfig[$logSt] ?? ['color' => '#9ca3af', 'bg' => '#f9fafb', 'border' => '#e5e7eb', 'label' => $logSt, 'icon' => 'fa-circle'];
                        ?>
                        <div style="display: flex; gap: 14px; padding-bottom: <?= $isLast ? '0' : '20px' ?>; position: relative;">
                            <div style="flex-shrink: 0; width: 36px; height: 36px; border-radius: 10px; background: <?= $isLast ? $logSc['color'] : '#f3f4f6' ?>; border: 2px solid <?= $isLast ? $logSc['color'] : '#e5e7eb' ?>; display: flex; align-items: center; justify-content: center; position: relative; z-index: 1;">
                                <i class="fa-solid <?= $logSc['icon'] ?>" style="color: <?= $isLast ? '#ffffff' : '#9ca3af' ?>; font-size: 13px;"></i>
                            </div>
                            <div style="flex: 1; padding-top: 4px;">
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 3px;">
                                    <span style="font-size: 13px; font-weight: 700; color: <?= $isLast ? $logSc['color'] : '#6b7280' ?>;"><?= $logSc['label'] ?></span>
                                    <?php if ($isLast): ?>
                                        <span style="font-size: 10px; background: <?= $logSc['color'] ?>; color: #fff; padding: 1px 8px; border-radius: 99px; font-weight: 700;">Terkini</span>
                                    <?php endif; ?>
                                </div>
                                <p style="font-size: 11.5px; color: #9ca3af; margin: 0;"><?= date('d M Y, H:i', strtotime($log['created_at'])) ?> — <?= esc($log['changed_by']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Receipt Card -->
                <div id="receipt-card" style="background: #ffffff; border: 1px solid #f0e8e8; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 16px rgba(0,0,0,0.04);">
                    <div style="background: linear-gradient(135deg, #b71c1c 0%, #7f0000 100%); padding: 16px 20px; text-align: center; position: relative; overflow: hidden;">
                        <div style="position: absolute; inset: 0; background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px); background-size: 16px 16px;"></div>
                        <div style="position: relative; z-index: 1;">
                            <p style="font-size: 10px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.12em; font-weight: 700; margin: 0 0 2px;">Sigap</p>
                            <p style="font-size: 13px; color: #ffffff; font-weight: 700; margin: 0;">Tanda Terima Laporan</p>
                        </div>
                    </div>
                    <div style="padding: 18px 20px;">
                        <!-- Tiket & PIN box -->
                        <div style="background: #fff5f5; border: 1px dashed #fca5a5; border-radius: 12px; padding: 16px; text-align: center; margin-bottom: 14px;">
                            <p style="font-size: 10px; font-weight: 700; color: #b71c1c; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 6px;">Nomor Tiket</p>
                            <p style="font-size: 18px; font-weight: 900; color: #111827; font-family: monospace; letter-spacing: 0.04em; margin: 0 0 10px;"><?= esc($complaint['ticket_number']) ?></p>
                            <div style="height: 1px; background: #fca5a5; margin-bottom: 10px; border-style: dashed; border-width: 1px 0 0 0; border-color: #fca5a5;"></div>
                            <p style="font-size: 10px; font-weight: 700; color: #b71c1c; text-transform: uppercase; letter-spacing: 0.1em; margin: 0 0 4px;">PIN Rahasia</p>
                            <p style="font-size: 22px; font-weight: 900; color: #111827; font-family: monospace; letter-spacing: 0.12em; margin: 0;"><?= esc($complaint['secret_pin']) ?></p>
                        </div>
                        <!-- Info rows -->
                        <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                                <span style="font-size: 12px; color: #9ca3af; white-space: nowrap;">Unit Layanan</span>
                                <span style="font-size: 12px; font-weight: 700; color: #374151; text-align: right;"><?= esc($complaint['location_name']) ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                                <span style="font-size: 12px; color: #9ca3af; white-space: nowrap;">Kategori</span>
                                <span style="font-size: 12px; font-weight: 700; color: #374151; text-align: right;"><?= esc($complaint['category_name']) ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px;">
                                <span style="font-size: 12px; color: #9ca3af; white-space: nowrap;">Waktu Kirim</span>
                                <span style="font-size: 12px; font-weight: 700; color: #374151; text-align: right;"><?= date('d M Y, H:i', strtotime($complaint['created_at'])) ?></span>
                            </div>
                        </div>
                        <!-- Warning -->
                        <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 10px; padding: 10px 12px;">
                            <p style="font-size: 11px; color: #92400e; margin: 0; line-height: 1.5; text-align: center;">
                                <i class="fa-solid fa-triangle-exclamation" style="margin-right: 4px;"></i>
                                <strong>Penting:</strong> Simpan nomor tiket &amp; PIN ini. Tanpa keduanya Anda tidak dapat melacak laporan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Save Image Button -->
                <button onclick="downloadReceiptImage()"
                        style="width: 100%; padding: 14px; background: #ffffff; border: 1.5px solid #e5e7eb; border-radius: 14px; font-size: 14px; font-weight: 700; color: #374151; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.03);"
                        onmouseover="this.style.background='#f9fafb'; this.style.borderColor='#d1d5db'; this.style.boxShadow='0 4px 14px rgba(0,0,0,0.06)';"
                        onmouseout="this.style.background='#ffffff'; this.style.borderColor='#e5e7eb'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.03)';">
                    <i class="fa-solid fa-image" style="color: #b71c1c;"></i> Simpan Tanda Terima
                </button>

            </div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
function downloadReceiptImage() {
    const el = document.getElementById('receipt-card');
    html2canvas(el, { scale: 2, useCORS: true, backgroundColor: '#ffffff' }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'TandaTerima-<?= esc($complaint['ticket_number']) ?>.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}
</script>
