<?php

// ─── Hero Content ─────────────────────────────────────────────
ob_start();
echo view('partials/tracking_hero_form', [
    'formAction' => base_url('dpmptsp/tracking'),
    'unitLabel'  => 'DPMPTSP',
    'ticket'     => $ticket ?? '',
    'pin'        => $pin ?? '',
    'error'      => $error ?? null,
]);
$hero_content = ob_get_clean();

// ─── Body Content ─────────────────────────────────────────────
$body_content = '';
if ($complaint) {
    ob_start();
    echo view('partials/tracking_result', [
        'complaint' => $complaint,
    ]);
    $body_content = ob_get_clean();
}

// ─── Render ───────────────────────────────────────────────────
echo view('layouts/dpmptsp', [
    'page_title'   => 'Lacak Pengaduan DPMPTSP — Sigap',
    'hero_content' => $hero_content,
    'body_content' => $body_content,
]);
