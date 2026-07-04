<?php
/**
 * Reusable Tracking Hero Search Form Partial
 * Variables: $formAction (string URL), $ticket, $pin, $error, $unitLabel (string)
 */
?>
<div style="text-align: center; max-width: 820px; margin: 0 auto 6px;">
    <h1 style="font-size: 38px; font-weight: 900; color: #ffffff; letter-spacing: -0.02em; margin: 0 0 10px; line-height: 1.2;">
        Lacak Status Laporan
    </h1>
    <p style="color: rgba(255,255,255,0.82); font-size: 15.5px; line-height: 1.7; max-width: 560px; margin: 0 auto;">
        Masukkan nomor tiket dan PIN keamanan untuk melihat status terkini dan riwayat penanganan laporan Anda<?= !empty($unitLabel) ? ' di <strong style="color: rgba(255,255,255,0.95);">' . esc($unitLabel) . '</strong>' : '' ?>.
    </p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div style="max-width: 560px; margin: 16px auto 0; background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px; color: #ffffff;">
        <i class="fa-solid fa-circle-check" style="color: #34d399; font-size: 16px; flex-shrink: 0;"></i>
        <span style="font-size: 14px;"><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<!-- Search Form -->
<div style="max-width: 600px; margin: 20px auto 0; background: rgba(0,0,0,0.28); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.13); border-radius: 20px; padding: 28px 30px; box-shadow: 0 25px 50px rgba(0,0,0,0.2);">
    <form action="<?= $formAction ?>" method="GET">
        <div class="form-row-responsive" style="margin-bottom: 16px;">
            <div style="text-align: left;">
                <label style="color: rgba(255,255,255,0.8); font-weight: 600; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 8px;">
                    <i class="fa-solid fa-ticket" style="margin-right: 4px; opacity: 0.7;"></i> Nomor Tiket
                </label>
                <input type="text" name="ticket" id="ticket" placeholder="KM-2026-xxxxxx"
                       value="<?= esc($ticket ?? '') ?>" required
                       style="width: 100%; padding: 11px 16px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.16); border-radius: 10px; color: #ffffff; font-size: 14px; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='rgba(255,255,255,0.4)'; this.style.background='rgba(255,255,255,0.12)';"
                       onblur="this.style.borderColor='rgba(255,255,255,0.16)'; this.style.background='rgba(255,255,255,0.08)';">
            </div>
            <div style="text-align: left;">
                <label style="color: rgba(255,255,255,0.8); font-weight: 600; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.06em; display: block; margin-bottom: 8px;">
                    <i class="fa-solid fa-lock" style="margin-right: 4px; opacity: 0.7;"></i> PIN Keamanan
                </label>
                <input type="password" name="pin" id="pin" placeholder="6 Digit PIN" maxlength="10"
                       value="<?= esc($pin ?? '') ?>" required
                       style="width: 100%; padding: 11px 16px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.16); border-radius: 10px; color: #ffffff; font-size: 14px; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                       onfocus="this.style.borderColor='rgba(255,255,255,0.4)'; this.style.background='rgba(255,255,255,0.12)';"
                       onblur="this.style.borderColor='rgba(255,255,255,0.16)'; this.style.background='rgba(255,255,255,0.08)';">
            </div>
        </div>
        <button type="submit"
                style="width: 100%; padding: 13px; background: rgba(255,255,255,0.95); border: none; border-radius: 10px; font-weight: 700; font-size: 15px; color: #b71c1c; cursor: pointer; transition: all 0.25s ease; display: flex; align-items: center; justify-content: center; gap: 9px;"
                onmouseover="this.style.background='#ffffff'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.15)';"
                onmouseout="this.style.background='rgba(255,255,255,0.95)'; this.style.transform='none'; this.style.boxShadow='none';">
            <i class="fa-solid fa-magnifying-glass"></i> Cek Progres Laporan
        </button>
    </form>
</div>

<!-- Error State -->
<?php if (!empty($error)): ?>
    <div style="max-width: 600px; margin: 16px auto 0; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; gap: 12px;">
        <i class="fa-solid fa-triangle-exclamation" style="color: #fca5a5; font-size: 16px; flex-shrink: 0;"></i>
        <span style="font-size: 14px; color: rgba(255,255,255,0.92);"><?= esc($error) ?></span>
    </div>
<?php endif; ?>
