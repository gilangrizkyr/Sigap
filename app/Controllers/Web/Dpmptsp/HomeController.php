<?php

namespace App\Controllers\Web\Dpmptsp;

use App\Controllers\BaseController;
use App\Models\Dpmptsp\CategoryModel;
use App\Services\ComplaintService;
use Exception;

/**
 * Controller untuk seluruh halaman publik portal DPMPTSP.
 * Scope: /dpmptsp/*
 */
class HomeController extends BaseController
{
    protected $complaintService;

    public function __construct()
    {
        $this->complaintService = new ComplaintService();
    }

    /**
     * GET /dpmptsp
     * Halaman utama / form pengaduan DPMPTSP.
     */
    public function index()
    {
        $categoryModel = new CategoryModel();
        $data['categories'] = $categoryModel->findAll();
        $data['location_id'] = 1;
        $data['location_name'] = 'DPMPTSP';

        return view('dpmptsp/form', $data);
    }

    /**
     * GET /dpmptsp/tracking
     * Lacak aduan khusus DPMPTSP.
     */
    public function tracking()
    {
        $ticket = $this->request->getGet('ticket');
        $pin    = $this->request->getGet('pin');

        $data = [
            'ticket'    => $ticket,
            'pin'       => $pin,
            'complaint' => null,
            'error'     => null,
        ];

        if ($ticket !== null && $pin !== null) {
            try {
                $data['complaint'] = $this->complaintService->trackComplaint(trim($ticket), trim($pin));
            } catch (Exception $e) {
                $data['error'] = $e->getMessage();
            }
        }

        return view('dpmptsp/tracking', $data);
    }

    /**
     * POST /dpmptsp/submit
     * Proses pengiriman aduan DPMPTSP.
     */
    public function submit()
    {
        $data = [
            'location_id'       => 1, // DPMPTSP selalu location_id = 1
            'service_unit_id'   => null,
            'category_id'       => $this->request->getPost('category_id'),
            'complaint_type'    => $this->request->getPost('complaint_type'),
            'title'             => $this->request->getPost('title'),
            'description'       => $this->request->getPost('description'),
            'complainant_name'  => $this->request->getPost('complainant_name'),
            'complainant_phone' => $this->request->getPost('complainant_phone'),
            'complainant_email' => $this->request->getPost('complainant_email'),
        ];

        $files = [];
        if ($this->request->getFiles()) {
            $uploadedFiles = $this->request->getFiles();
            if (isset($uploadedFiles['attachments'])) {
                $attachments = $uploadedFiles['attachments'];
                $files = is_array($attachments) ? $attachments : [$attachments];
            }
        }

        $ip = $this->request->getIPAddress();

        try {
            $result = $this->complaintService->createComplaint($data, $ip, $files);
            return redirect()
                ->to(site_url('dpmptsp/tracking?ticket=' . $result['ticket_number'] . '&pin=' . $result['secret_pin']))
                ->with('success', 'Pengaduan berhasil dikirim! Nomor Tiket: ' . $result['ticket_number'] . '. PIN: ' . $result['secret_pin']);
        } catch (Exception $e) {
            log_message('error', 'DPMPTSP SUBMIT ERROR: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * GET /dpmptsp/faq
     */
    public function faq()
    {
        return view('dpmptsp/faq');
    }

    /**
     * GET /dpmptsp/about
     */
    public function about()
    {
        return view('dpmptsp/about');
    }
}
