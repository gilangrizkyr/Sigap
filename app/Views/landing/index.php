<?php

$trackingUrl = base_url('tracking');
$dpmptspUrl  = base_url('dpmptsp');
$mppUrl      = base_url('mpp');

// ── HERO ──────────────────────────────────────────────────────
ob_start(); ?>
<div class="hero-grid-split">
    <!-- Left Column: Brand Tagline, Description, CTA Buttons -->
    <div class="hero-brand-col">
        <span class="hero-subtitle">Platform Aspirasi & Pengaduan Publik</span>
        <h1 class="hero-main-title">
            SIGAP<span>Cepat. Transparan. Akuntabel.</span>
        </h1>
        <p class="hero-description">
            Portal resmi pelacakan status laporan dan pengaduan pelayanan publik. Masukkan nomor tiket dan PIN keamanan Anda pada widget di sebelah kanan untuk melihat progres penanganan secara real-time.
        </p>    </div>

    <!-- Right Column: Interactive Tracking Card -->
    <div class="hero-widget-col">
        <div class="tracking-widget-card" id="trackingWidget">
            <!-- Form State -->
            <div id="trackingFormState">
                <p class="widget-title">
                    <i class="fa-solid fa-magnifying-glass" style="color: #f59e0b;"></i> Lacak Status Laporan
                </p>
                <form id="widgetTrackingForm" onsubmit="handleWidgetTracking(event)">
                    <div style="margin-bottom: 12px;">
                        <input type="text" id="widgetTicket" placeholder="Nomor Tiket (KM-2026-xxxxxx)" class="widget-input" required autocomplete="off">
                    </div>
                    <div style="margin-bottom: 16px;">
                        <input type="password" id="widgetPin" placeholder="PIN Keamanan" maxlength="10" class="widget-input" required autocomplete="off">
                    </div>
                    <button type="submit" class="btn-widget-submit">
                        <i class="fa-solid fa-radar"></i> Cek Progres Laporan
                    </button>
                </form>
            </div>

            <!-- Loading State / Skeleton Loader (Hidden by default) -->
            <div id="trackingLoadingState" style="display: none; text-align: left;">
                <!-- Header skeleton -->
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; margin-bottom: 16px;">
                    <div>
                        <div class="skeleton-pulse" style="width: 70px; height: 18px; border-radius: 99px; margin-bottom: 6px;"></div>
                        <div class="skeleton-pulse" style="width: 130px; height: 14px; border-radius: 4px;"></div>
                    </div>
                    <div class="skeleton-pulse" style="width: 60px; height: 24px; border-radius: 6px;"></div>
                </div>
                <!-- Title skeleton -->
                <div style="margin-bottom: 16px;">
                    <div class="skeleton-pulse" style="width: 90px; height: 12px; margin-bottom: 6px;"></div>
                    <div class="skeleton-pulse" style="width: 100%; height: 16px; margin-bottom: 4px;"></div>
                    <div class="skeleton-pulse" style="width: 80%; height: 16px;"></div>
                </div>
                <!-- Unit skeleton -->
                <div style="margin-bottom: 20px;">
                    <div class="skeleton-pulse" style="width: 80px; height: 12px; margin-bottom: 6px;"></div>
                    <div class="skeleton-pulse" style="width: 150px; height: 14px;"></div>
                </div>
                <!-- Timeline skeleton -->
                <div>
                    <div class="skeleton-pulse" style="width: 100px; height: 12px; margin-bottom: 12px;"></div>
                    <div style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px;">
                        <div class="skeleton-pulse" style="width: 10px; height: 10px; border-radius: 50%; margin-top: 4px; flex-shrink: 0;"></div>
                        <div style="width: 100%;">
                            <div class="skeleton-pulse" style="width: 80px; height: 10px; margin-bottom: 4px;"></div>
                            <div class="skeleton-pulse" style="width: 110px; height: 12px;"></div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div class="skeleton-pulse" style="width: 10px; height: 10px; border-radius: 50%; margin-top: 4px; flex-shrink: 0;"></div>
                        <div style="width: 100%;">
                            <div class="skeleton-pulse" style="width: 80px; height: 10px; margin-bottom: 4px;"></div>
                            <div class="skeleton-pulse" style="width: 120px; height: 12px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Result State (Hidden by default) -->
            <div id="trackingResultState" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px; margin-bottom: 12px;">
                    <div>
                        <span id="resBadge" class="result-status-badge">TERKIRIM</span>
                        <h4 id="resTicket" style="font-weight: 800; font-size: 15px; margin: 6px 0 0; color: #ffffff;">KM-2026-000000</h4>
                    </div>
                    <button onclick="resetWidget()" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #ffffff; padding: 5px 10px; border-radius: 6px; font-size: 11px; cursor: pointer; font-weight: 700; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                </div>
                
                <div style="max-height: 230px; overflow-y: auto; padding-right: 4px;" class="custom-scroll">
                    <p style="font-size: 12.5px; color: rgba(255,255,255,0.7); margin-bottom: 4px;">Judul Laporan:</p>
                    <p id="resTitle" style="font-size: 13.5px; font-weight: 700; color: #ffffff; margin: 0 0 12px; line-height: 1.4;"></p>
                    
                    <p style="font-size: 12.5px; color: rgba(255,255,255,0.7); margin-bottom: 6px;">Unit Layanan:</p>
                    <p id="resLocation" style="font-size: 13px; font-weight: 600; color: #fcd34d; margin: 0 0 14px;"></p>

                    <!-- Latest reply -->
                    <div id="resReplyBox" style="background: rgba(255,255,255,0.06); border-left: 3px solid #ef4444; padding: 12px; border-radius: 0 8px 8px 0; margin-bottom: 15px; display: none;">
                        <p style="font-size: 11px; font-weight: 800; color: #fca5a5; margin: 0 0 4px; text-transform: uppercase;">Tanggapan Resmi:</p>
                        <p id="resReplyText" style="font-size: 12.5px; color: rgba(255,255,255,0.9); margin: 0; line-height: 1.5; white-space: pre-line;"></p>
                    </div>

                    <!-- Timeline -->
                    <p style="font-size: 12px; font-weight: 800; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 0.05em; margin: 0 0 8px;">Riwayat Status:</p>
                    <ul class="widget-timeline" id="resTimeline"></ul>
                </div>
            </div>

            <!-- Error State (Hidden by default) -->
            <div id="trackingErrorState" style="display: none; text-align: center; padding: 20px 0;">
                <div style="width: 48px; height: 48px; background: rgba(239,68,68,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #f87171; font-size: 20px;"></i>
                </div>
                <p id="errorMessage" style="color: rgba(255,255,255,0.9); font-size: 13.5px; line-height: 1.5; margin-bottom: 16px;">Pengaduan tidak ditemukan. Silakan periksa kembali Tiket & PIN Anda.</p>
                <button onclick="resetWidget()" style="padding: 8px 18px; background: #ffffff; border: none; border-radius: 8px; font-weight: 700; font-size: 13px; color: #b71c1c; cursor: pointer; transition: all 0.2s;">
                    Coba Lagi
                </button>
            </div>
        </div>
    </div>
</div>
<?php $hero_content = ob_get_clean();

// ── BODY SECTIONS ─────────────────────────────────────────────
ob_start(); ?>

<!-- ══ SECTION 1: Tentang Sigap ══ -->
<div style="background:#faf8f8;padding:72px 0 64px;" id="tentang">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;" class="about-grid">
            <div>
                <span style="font-size:11.5px;font-weight:700;color:#b71c1c;text-transform:uppercase;letter-spacing:.12em;background:rgba(183,28,28,.07);padding:5px 14px;border-radius:99px;display:inline-block;margin-bottom:16px;">Tentang Kami</span>
                <h2 style="font-size:32px;font-weight:900;color:#111827;margin-bottom:16px;letter-spacing:-.02em;line-height:1.25;">Apa itu <span style="color:#b71c1c;">Sigap</span>?</h2>
                <p style="color:#4b5563;font-size:15px;line-height:1.8;margin-bottom:16px;">
                    <strong>Sigap</strong> adalah platform pengaduan dan aspirasi publik berbasis web yang dibangun untuk menjadi jembatan resmi antara masyarakat umum dan instansi pelayanan publik — secara digital, cepat, dan terukur.
                </p>
                <p style="color:#6b7280;font-size:14.5px;line-height:1.75;margin:0;">
                    Setiap laporan yang masuk diproses secara sistematis, diteruskan ke unit yang tepat, dan dapat dipantau progresnya secara real-time oleh pelapor menggunakan Nomor Tiket dan PIN unik.
                </p>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <?php
                $stats = [
                    ['icon'=>'fa-file-lines','val'=>'2.4K+','label'=>'Laporan Masuk'],
                    ['icon'=>'fa-circle-check','val'=>'91%','label'=>'Terselesaikan'],
                    ['icon'=>'fa-clock','val'=>'< 3 Hari','label'=>'Rata-rata Respons'],
                    ['icon'=>'fa-shield-halved','val'=>'100%','label'=>'Keamanan Data'],
                ];
                foreach ($stats as $s): ?>
                <div style="background:#fff;border:1px solid #f0e8e8;border-radius:14px;padding:22px 18px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,.04);">
                    <div style="width:40px;height:40px;background:linear-gradient(135deg,#d32f2f,#b71c1c);border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <i class="fa-solid <?= $s['icon'] ?>" style="color:#fff;font-size:16px;"></i>
                    </div>
                    <div style="font-size:22px;font-weight:900;color:#111827;"><?= $s['val'] ?></div>
                    <div style="font-size:12px;color:#9ca3af;font-weight:600;margin-top:4px;"><?= $s['label'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- ══ SECTION 2: Keunggulan ══ -->
<div style="background:linear-gradient(180deg,#fdf3f3 0%,#faf8f8 100%);border-top:1px solid rgba(183,28,28,.07);padding:70px 0;" id="keunggulan">
    <div class="container">
        <div class="features-split-layout">
            <div class="features-left-col">
                <?php
                $features = [
                    ['icon'=>'fa-user-secret','title'=>'Jaminan Anonimitas','desc'=>'Laporan dapat dikirim tanpa mencantumkan identitas. Aktifkan opsi anonim dan privasi Anda terjaga sepenuhnya.'],
                    ['icon'=>'fa-chart-line','title'=>'Pelacakan Real-Time','desc'=>'Tiket + PIN unik memungkinkan Anda memantau progres laporan kapan saja dan dari mana saja secara mandiri.'],
                    ['icon'=>'fa-bullseye','title'=>'Tepat Sasaran','desc'=>'Laporan diteruskan langsung ke unit instansi terkait sehingga penanganan lebih cepat dan tepat sasaran.'],
                    ['icon'=>'fa-shield-halved','title'=>'Data Terenkripsi','desc'=>'Seluruh data laporan dienkripsi dan disimpan dengan standar keamanan tinggi. Kerahasiaan Anda adalah prioritas kami.'],
                    ['icon'=>'fa-comments','title'=>'Dua Arah','desc'=>'Petugas dapat merespons laporan secara resmi, dan Anda menerima notifikasi serta tanggapan tertulis yang terdokumentasi.'],
                    ['icon'=>'fa-mobile-screen','title'=>'Ramah Semua Perangkat','desc'=>'Akses dari HP, tablet, atau komputer. Antarmuka Sigap responsif dan nyaman digunakan di layar ukuran apapun.'],
                ];
                foreach ($features as $f): ?>
                <div class="feature-minimal-item">
                    <div class="feature-minimal-icon">
                        <i class="fa-solid <?= $f['icon'] ?>"></i>
                    </div>
                    <div class="feature-minimal-content">
                        <h4 class="feature-minimal-title"><?= $f['title'] ?></h4>
                        <p class="feature-minimal-desc"><?= $f['desc'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <!-- Right Column: Sticky Introduction Panel -->
            <div class="features-right-col">
                <div class="sticky-intro-panel">
                    <span class="intro-tag">Keunggulan Platform</span>
                    <h2 class="intro-title">Dirancang untuk Kemudahan Semua Pihak</h2>
                    <p class="intro-desc">
                        Baik Anda masyarakat pelapor maupun petugas penanganan — Sigap memudahkan seluruh alur komunikasi publik.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ══ SECTION 3: Alur Layanan ══ -->
<div style="background:#faf8f8;padding:80px 0;border-top:1px solid rgba(183,28,28,.07);" id="cara-kerja">
    <div class="container">
        <div style="text-align:center;max-width:680px;margin:0 auto 60px;">
            <span style="font-size:11.5px;font-weight:700;color:#b71c1c;text-transform:uppercase;letter-spacing:.12em;background:rgba(183,28,28,.07);padding:5px 14px;border-radius:99px;display:inline-block;margin-bottom:14px;">Cara Kerja</span>
            <h2 style="font-size:30px;font-weight:800;color:#111827;margin-bottom:12px;letter-spacing:-.015em;">3 Langkah Pelacakan Progres</h2>
            <p style="color:#6b7280;font-size:15px;line-height:1.65;margin:0;">Proses pemantauan laporan dirancang sangat transparan agar pelapor mengetahui perkembangan tindak lanjut.</p>
        </div>
        <div class="minimal-steps-container">
            <!-- Connection Line (desktop only) -->
            <div class="steps-connecting-line"></div>
            
            <div class="steps-grid-new">
                <?php
                $steps = [
                    ['n'=>1,'title'=>'Registrasi Pengaduan','desc'=>'Laporan pengaduan Anda didaftarkan secara resmi ke dalam sistem database pelayanan publik.'],
                    ['n'=>2,'title'=>'Terima Tiket & PIN','desc'=>'Pelapor mendapatkan Nomor Tiket unik beserta PIN keamanan rahasia sebagai akses otentikasi pelacakan.'],
                    ['n'=>3,'title'=>'Lacak Progres Real-Time','desc'=>'Gunakan Tiket & PIN pada widget di atas untuk memantau status penanganan dan membaca tanggapan resmi petugas.'],
                ];
                foreach ($steps as $s): ?>
                <div class="step-minimal-item">
                    <div class="step-number-circle"><?= $s['n'] ?></div>
                    <h4 class="step-minimal-title"><?= $s['title'] ?></h4>
                    <p class="step-minimal-desc"><?= $s['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


<!-- ══ SECTION 4: Nilai & Visi ══ -->
<div style="background:linear-gradient(135deg,#b71c1c 0%,#7f0000 100%);padding:80px 0;position:relative;overflow:hidden;" id="visi-nilai">
    <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,.07) 1px,transparent 1px);background-size:22px 22px;pointer-events:none;z-index:1;"></div>
    <div style="position:absolute;top:-20%;right:-5%;width:500px;height:500px;background:radial-gradient(circle,rgba(239,68,68,.2) 0%,transparent 70%);filter:blur(60px);pointer-events:none;border-radius:50%;z-index:1;"></div>
    <div class="container" style="position:relative;z-index:2;">
        <div style="text-align:center;margin-bottom:60px;">
            <span style="font-size:12px;font-weight:700;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.15em;display:block;margin-bottom:12px;">Nilai & Visi</span>
            <h2 style="font-size:32px;font-weight:900;color:#fff;margin-bottom:14px;letter-spacing:-.015em;">Apa yang Kami Perjuangkan</h2>
            <p style="color:rgba(255,255,255,.8);font-size:15.5px;max-width:560px;margin:0 auto;line-height:1.7;">Sigap berdiri di atas tiga prinsip inti yang menjadi landasan setiap fitur dan kebijakan kami.</p>
        </div>
        <div class="values-grid-new">
            <?php
            $values = [
                ['icon'=>'fa-eye','title'=>'Transparansi','desc'=>'Setiap laporan diproses secara terbuka. Pelapor dapat memantau progres penanganan secara langsung tanpa perlu menunggu kabar.'],
                ['icon'=>'fa-scale-balanced','title'=>'Akuntabilitas','desc'=>'Setiap tindak lanjut petugas tercatat dan terdokumentasi. Tidak ada laporan yang hilang atau diabaikan tanpa jejak yang dapat diaudit.'],
                ['icon'=>'fa-bolt','title'=>'Kecepatan','desc'=>'Dengan sistem routing otomatis ke unit terkait, laporan Anda sampai ke meja yang tepat dalam hitungan menit setelah dikirimkan.'],
            ];
            foreach ($values as $v): ?>
            <div class="value-minimal-item">
                <div class="value-minimal-icon">
                    <i class="fa-solid <?= $v['icon'] ?>"></i>
                </div>
                <h4 class="value-minimal-title"><?= $v['title'] ?></h4>
                <p class="value-minimal-desc"><?= $v['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php $body_content = ob_get_clean();

$extra_css = '
    /* Hero Grid Split Layout */
    .hero-grid-split {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 48px;
        align-items: center;
        text-align: left;
        padding: 30px 0 50px;
    }
    .hero-brand-col {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .hero-subtitle {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 12px;
    }
    .hero-main-title {
        font-size: 54px;
        font-weight: 900;
        line-height: 1.1;
        margin-bottom: 20px;
        color: #ffffff;
        letter-spacing: -0.02em;
    }
    .hero-main-title span {
        font-size: 26px;
        font-weight: 600;
        display: block;
        opacity: 0.85;
        margin-top: 8px;
        letter-spacing: 0px;
    }
    .hero-description {
        color: rgba(255, 255, 255, 0.85);
        font-size: 16px;
        line-height: 1.75;
        margin-bottom: 30px;
        max-width: 540px;
    }
    .hero-cta-group {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    .btn-cta-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        color: #b71c1c;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14.5px;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }
    .btn-cta-primary:hover {
        background: #fdf3f3;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }
    .btn-cta-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        padding: 14px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14.5px;
        text-decoration: none;
        border: 1.5px solid rgba(255, 255, 255, 0.25);
        transition: all 0.25s ease;
    }
    .btn-cta-secondary:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        border-color: rgba(255, 255, 255, 0.4);
    }

    /* Interactive Tracking Card */
    .tracking-widget-card {
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        color: #ffffff;
        min-height: 280px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        text-align: left;
    }
    .widget-title {
        color: rgba(255, 255, 255, 0.85);
        font-size: 12.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin: 0 0 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .widget-input {
        width: 100%;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        color: #ffffff;
        font-size: 14px;
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s;
    }
    .widget-input:focus {
        border-color: rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.12);
    }
    .btn-widget-submit {
        width: 100%;
        padding: 13px;
        background: #ffffff;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14.5px;
        color: #b71c1c;
        cursor: pointer;
        transition: all 0.25s;
        margin-top: 10px;
    }
    .btn-widget-submit:hover {
        background: #fdf3f3;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
    }

    /* Response/Result styles */
    .result-status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-state-submitted { background: rgba(148, 163, 184, 0.2); color: #cbd5e1; border: 1px solid rgba(148, 163, 184, 0.3); }
    .badge-state-verified { background: rgba(245, 158, 11, 0.2); color: #fcd34d; border: 1px solid rgba(245, 158, 11, 0.3); }
    .badge-state-processing { background: rgba(59, 130, 246, 0.2); color: #93c5fd; border: 1px solid rgba(59, 130, 246, 0.3); }
    .badge-state-waiting_response { background: rgba(139, 92, 246, 0.2); color: #c4b5fd; border: 1px solid rgba(139, 92, 246, 0.3); }
    .badge-state-resolved { background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-state-rejected { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.3); }

    /* Timeline Log inside Widget */
    .widget-timeline {
        border-left: 2px solid rgba(255, 255, 255, 0.15);
        margin: 15px 0 5px 8px;
        padding: 0;
        list-style: none;
    }
    .widget-timeline-item {
        position: relative;
        padding-left: 20px;
        margin-bottom: 14px;
        text-align: left;
    }
    .widget-timeline-item::before {
        content: "";
        position: absolute;
        left: -6px;
        top: 5px;
        width: 10px;
        height: 10px;
        background: #94a3b8;
        border-radius: 50%;
    }
    .widget-timeline-item.active::before {
        background: #3b82f6;
        box-shadow: 0 0 8px #3b82f6;
    }
    .widget-timeline-item.success::before {
        background: #10b981;
        box-shadow: 0 0 8px #10b981;
    }
    .widget-timeline-item.danger::before {
        background: #ef4444;
        box-shadow: 0 0 8px #ef4444;
    }

    /* Custom Scrollbar for Widget Content */
    .custom-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 4px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Skeleton Pulse Shimmer Loading */
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .skeleton-pulse {
        background: linear-gradient(90deg, rgba(255,255,255,0.06) 25%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.06) 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite linear;
        border-radius: 4px;
    }

    /* Split Features Layout */
    .features-split-layout {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 48px;
        align-items: center;
    }
    .features-left-col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px 28px;
    }
    .feature-minimal-item {
        display: flex;
        gap: 16px;
        align-items: flex-start;
        text-align: left;
        transition: all 0.25s ease;
    }
    .feature-minimal-icon {
        width: 42px;
        height: 42px;
        background: rgba(211, 47, 47, 0.06);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #d32f2f;
        font-size: 18px;
        transition: all 0.25s ease;
        margin-top: 2px;
    }
    .feature-minimal-item:hover .feature-minimal-icon {
        background: #d32f2f;
        color: #ffffff;
        transform: translateY(-2px);
    }
    .feature-minimal-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 6px;
    }
    .feature-minimal-desc {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.6;
        margin: 0;
    }

    .sticky-intro-panel {
        text-align: left;
    }
    .intro-tag {
        font-size: 11.5px;
        font-weight: 700;
        color: #b71c1c;
        text-transform: uppercase;
        letter-spacing: .12em;
        background: rgba(183,28,28,0.07);
        padding: 5px 14px;
        border-radius: 99px;
        display: inline-block;
        margin-bottom: 16px;
    }
    .intro-title {
        font-size: 32px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 16px;
        line-height: 1.25;
        letter-spacing: -0.015em;
    }
    .intro-desc {
        color: #6b7280;
        font-size: 15px;
        line-height: 1.7;
        margin: 0;
    }

    /* Minimal Steps Layout (Section 3) */
    .minimal-steps-container {
        position: relative;
        max-width: 960px;
        margin: 0 auto;
    }
    .steps-connecting-line {
        position: absolute;
        top: 24px;
        left: 15%;
        right: 15%;
        height: 2px;
        background: linear-gradient(90deg, rgba(211,47,47,0.02) 0%, rgba(211,47,47,0.3) 50%, rgba(211,47,47,0.02) 100%);
        z-index: 1;
    }
    .steps-grid-new {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        position: relative;
        z-index: 2;
    }
    .step-minimal-item {
        text-align: center;
    }
    .step-number-circle {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 4px 12px rgba(211,47,47,0.2);
        border: 4px solid #faf8f8;
        transition: all 0.3s ease;
    }
    .step-minimal-item:hover .step-number-circle {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(211,47,47,0.35);
    }
    .step-minimal-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 10px;
    }
    .step-minimal-desc {
        font-size: 13.5px;
        color: #6b7280;
        line-height: 1.65;
        margin: 0;
        padding: 0 10px;
    }

    /* Minimal Values Layout (Section 4) */
    .values-grid-new {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 40px;
        max-width: 960px;
        margin: 0 auto;
    }
    .value-minimal-item {
        text-align: center;
        padding: 0 10px;
        transition: all 0.3s ease;
    }
    .value-minimal-icon {
        width: 54px;
        height: 54px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: #ffffff;
        font-size: 20px;
        transition: all 0.3s ease;
    }
    .value-minimal-item:hover .value-minimal-icon {
        background: #ffffff;
        color: #b71c1c;
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(255,255,255,0.2);
    }
    .value-minimal-title {
        font-size: 18px;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 12px;
    }
    .value-minimal-desc {
        font-size: 13.5px;
        color: rgba(255, 255, 255, 0.78);
        line-height: 1.7;
        margin: 0;
    }

    @media (max-width: 992px) {
        .hero-grid-split {
            grid-template-columns: 1fr !important;
            text-align: center !important;
            gap: 40px !important;
        }
        .hero-brand-col {
            align-items: center !important;
        }
        .hero-cta-group {
            justify-content: center !important;
        }
        .hero-main-title {
            font-size: 42px !important;
        }
        .about-grid   { grid-template-columns: 1fr !important; gap: 32px !important; }
        
        /* Center split features */
        .features-split-layout {
            grid-template-columns: 1fr !important;
            gap: 40px !important;
        }
        .features-right-col {
            order: -1 !important;
            position: static !important;
        }
        .sticky-intro-panel {
            text-align: center !important;
        }
        .features-left-col {
            grid-template-columns: 1fr 1fr !important;
            gap: 16px !important;
        }

        /* Responsive new steps and values */
        .steps-connecting-line {
            display: none !important;
        }
        .steps-grid-new {
            grid-template-columns: 1fr !important;
            gap: 32px !important;
        }
        .values-grid-new {
            grid-template-columns: 1fr !important;
            gap: 36px !important;
        }
    }
    @media (max-width: 576px) {
        .features-left-col {
            grid-template-columns: 1fr !important;
        }
    }
';

$extra_scripts = '
<script>
function handleWidgetTracking(e) {
    e.preventDefault();
    
    const ticket = document.getElementById("widgetTicket").value.trim();
    const pin = document.getElementById("widgetPin").value.trim();
    
    if (!ticket || !pin) return;
    
    const formState = document.getElementById("trackingFormState");
    const loadingState = document.getElementById("trackingLoadingState");
    const resultState = document.getElementById("trackingResultState");
    const errorState = document.getElementById("trackingErrorState");
    
    // Switch to loading state
    formState.style.display = "none";
    loadingState.style.display = "block";
    resultState.style.display = "none";
    errorState.style.display = "none";
    
    fetch(`' . base_url('api/complaints/tracking') . '?ticket=${encodeURIComponent(ticket)}&pin=${encodeURIComponent(pin)}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Laporan tidak ditemukan atau PIN salah.");
            }
            return response.json();
        })
        .then(res => {
            if (res.success && res.data) {
                renderWidgetResult(res.data);
            } else {
                throw new Error("Gagal mengambil data.");
            }
        })
        .catch(err => {
            document.getElementById("errorMessage").innerText = err.message || "Pengaduan tidak ditemukan. Silakan periksa kembali Tiket & PIN Anda.";
            loadingState.style.display = "none";
            errorState.style.display = "block";
        });
}

function renderWidgetResult(data) {
    const loadingState = document.getElementById("trackingLoadingState");
    const resultState = document.getElementById("trackingResultState");
    
    // Map Status translation
    const statusMap = {
        "submitted": { text: "Terkirim", class: "badge-state-submitted" },
        "verified": { text: "Menunggu Verifikasi", class: "badge-state-verified" },
        "processing": { text: "Sedang Diproses", class: "badge-state-processing" },
        "waiting_response": { text: "Menunggu Respons", class: "badge-state-waiting_response" },
        "resolved": { text: "Selesai", class: "badge-state-resolved" },
        "rejected": { text: "Tidak Dapat Diproses", class: "badge-state-rejected" }
    };
    
    const statusInfo = statusMap[data.status] || { text: data.status, class: "badge-state-submitted" };
    
    // Set text elements
    const badgeEl = document.getElementById("resBadge");
    badgeEl.innerText = statusInfo.text;
    badgeEl.className = `result-status-badge ${statusInfo.class}`;
    
    document.getElementById("resTicket").innerText = data.ticket_number;
    document.getElementById("resTitle").innerText = data.title;
    
    let locationText = data.location_name;
    if (data.service_unit_name) {
        locationText += ` - ${data.service_unit_name}`;
    }
    document.getElementById("resLocation").innerText = locationText;
    
    // Handle admin reply
    const replyBox = document.getElementById("resReplyBox");
    if (data.replies && data.replies.length > 0) {
        const latestReply = data.replies[data.replies.length - 1];
        document.getElementById("resReplyText").innerText = latestReply.message;
        replyBox.style.display = "block";
    } else {
        replyBox.style.display = "none";
    }
    
    // Render timeline
    const timelineEl = document.getElementById("resTimeline");
    timelineEl.innerHTML = "";
    
    if (data.status_logs && data.status_logs.length > 0) {
        data.status_logs.forEach(log => {
            const li = document.createElement("li");
            li.className = "widget-timeline-item";
            
            if (log.new_status === "resolved") {
                li.classList.add("success");
            } else if (log.new_status === "rejected") {
                li.classList.add("danger");
            } else if (log.new_status === "processing" || log.new_status === "waiting_response") {
                li.classList.add("active");
            }
            
            const logStatus = statusMap[log.new_status] ? statusMap[log.new_status].text : log.new_status;
            
            // Format date to local readable format
            const logDate = new Date(log.created_at);
            const formattedDate = logDate.toLocaleDateString("id-ID", { day: "2-digit", month: "short", hour: "2-digit", minute: "2-digit" });
            
            li.innerHTML = `
                <div style="font-size: 11px; color: rgba(255,255,255,0.4);">${formattedDate} WIB</div>
                <div style="font-size: 12.5px; font-weight: 700; color: #ffffff; margin-top: 2px;">Status: ${logStatus}</div>
            `;
            timelineEl.appendChild(li);
        });
    }
    
    loadingState.style.display = "none";
    resultState.style.display = "block";
}

function resetWidget() {
    document.getElementById("widgetTicket").value = "";
    document.getElementById("widgetPin").value = "";
    
    document.getElementById("trackingFormState").style.display = "block";
    document.getElementById("trackingLoadingState").style.display = "none";
    document.getElementById("trackingResultState").style.display = "none";
    document.getElementById("trackingErrorState").style.display = "none";
}
</script>
';

echo view('layouts/public', [
    'page_title'    => 'Sigap — Portal Aspirasi & Pengaduan Publik',
    'hero_content'  => $hero_content,
    'body_content'  => $body_content,
    'extra_css'     => $extra_css,
    'extra_scripts' => $extra_scripts,
]);
