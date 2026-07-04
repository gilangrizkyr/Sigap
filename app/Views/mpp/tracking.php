<?php

// ─── Hero Content ─────────────────────────────────────────────
ob_start();
$formAction = base_url('mpp/tracking');
$unitLabel  = 'Mal Pelayanan Publik (MPP)';
echo view('partials/tracking_hero_form', [
    'formAction' => $formAction,
    'unitLabel'  => $unitLabel,
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
echo view('layouts/mpp', [
    'page_title'   => 'Lacak Pengaduan MPP — Sigap',
    'hero_content' => $hero_content,
    'body_content' => $body_content,
]);
