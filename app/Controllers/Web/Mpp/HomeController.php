<?php

namespace App\Controllers\Web\Mpp;

use App\Controllers\BaseController;
use App\Models\Mpp\CategoryModel;
use App\Models\Mpp\ServiceUnitModel;
use App\Services\ComplaintService;
use Exception;

/**
 * Controller untuk seluruh halaman publik portal MPP.
 * Scope: /mpp/*
 */
class HomeController extends BaseController
{
    protected $complaintService;

    public function __construct()
    {
        $this->complaintService = new ComplaintService();
    }

    /**
     * GET /mpp
     * Halaman utama / form pengaduan MPP.
     */
    public function index()
    {
        $categoryModel   = new CategoryModel();
        $serviceUnitModel = new ServiceUnitModel();

        $data['categories']    = $categoryModel->findAll();
        $data['service_units'] = $serviceUnitModel->findAll();
        $data['location_id']   = 2;
        $data['location_name'] = 'Mal Pelayanan Publik (MPP)';

        return view('mpp/form', $data);
    }

    /**
     * GET /mpp/tracking
     * Lacak aduan khusus MPP.
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

        return view('mpp/tracking', $data);
    }

    /**
     * POST /mpp/submit
     * Proses pengiriman aduan MPP.
     */
    public function submit()
    {
        $data = [
            'location_id'       => 2, // MPP selalu location_id = 2
            'service_unit_id'   => $this->request->getPost('service_unit_id'),
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
                ->to(site_url('mpp/tracking?ticket=' . $result['ticket_number'] . '&pin=' . $result['secret_pin']))
                ->with('success', 'Pengaduan berhasil dikirim! Nomor Tiket: ' . $result['ticket_number'] . '. PIN: ' . $result['secret_pin']);
        } catch (Exception $e) {
            log_message('error', 'MPP SUBMIT ERROR: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * GET /mpp/faq
     */
    public function faq()
    {
        return view('mpp/faq');
    }

    /**
     * GET /mpp/about
     */
    public function about()
    {
        return view('mpp/about');
    }
}
