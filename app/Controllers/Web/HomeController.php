<?php

namespace App\Controllers\Web;

use App\Controllers\BaseController;
use App\Models\LocationModel;
use App\Models\ServiceUnitModel;
use App\Models\ComplaintCategoryModel;
use App\Services\ComplaintService;
use App\Repositories\ComplaintRepository;
use Exception;

class HomeController extends BaseController
{
    protected $complaintService;
    protected $repository;

    public function __construct()
    {
        $this->complaintService = new ComplaintService();
        $this->repository = new ComplaintRepository();
    }

    /**
     * Route: / or /sigap
     * Landing Page to select service unit.
     */
    public function index()
    {
        return view('landing/index');
    }

    /**
     * Route: /pengaduan/dpmptsp
     * Form page for DPMPTSP complaint.
     */
    public function dpmptsp()
    {
        $categoryModel = new ComplaintCategoryModel();
        // Location 1 is DPMPTSP
        $data['categories'] = $categoryModel->where('location_id', 1)->findAll();
        $data['location_id'] = 1;
        $data['location_name'] = 'DPMPTSP';

        return view('dpmptsp/form', $data);
    }

    /**
     * Route: /pengaduan/mpp
     * Form page for MPP complaint.
     */
    public function mpp()
    {
        $categoryModel = new ComplaintCategoryModel();
        $unitModel = new ServiceUnitModel();
        
        // Location 2 is MPP
        $data['categories'] = $categoryModel->where('location_id', 2)->findAll();
        $data['service_units'] = $unitModel->where('location_id', 2)->findAll();
        $data['location_id'] = 2;
        $data['location_name'] = 'Mal Pelayanan Publik (MPP)';

        return view('mpp/form', $data);
    }

    /**
     * Route: /tracking
     * Page to track complaints.
     */
    public function tracking()
    {
        $ticket = $this->request->getGet('ticket');
        $pin = $this->request->getGet('pin');
        
        $data = [
            'ticket'    => $ticket,
            'pin'       => $pin,
            'complaint' => null,
            'error'     => null
        ];

        if ($ticket !== null && $pin !== null) {
            try {
                $data['complaint'] = $this->complaintService->trackComplaint(trim($ticket), trim($pin));
            } catch (Exception $e) {
                $data['error'] = $e->getMessage();
            }
        }

        return view('landing/tracking', $data);
    }

    /**
     * Route: /dpmptsp/tracking
     */
    public function dpmptspTracking()
    {
        $ticket = $this->request->getGet('ticket');
        $pin = $this->request->getGet('pin');
        
        $data = [
            'ticket'    => $ticket,
            'pin'       => $pin,
            'complaint' => null,
            'error'     => null
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
     * Route: /mpp/tracking
     */
    public function mppTracking()
    {
        $ticket = $this->request->getGet('ticket');
        $pin = $this->request->getGet('pin');
        
        $data = [
            'ticket'    => $ticket,
            'pin'       => $pin,
            'complaint' => null,
            'error'     => null
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
     * POST /pengaduan/submit
     * Handle public form submissions.
     */
    public function submit()
    {
        $location_id = $this->request->getPost('location_id');
        $data = [
            'location_id'       => $location_id,
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
                if (is_array($attachments)) {
                    $files = $attachments;
                } else {
                    $files = [$attachments];
                }
            }
        }

        $ip = $this->request->getIPAddress();

        try {
            $result = $this->complaintService->createComplaint($data, $ip, $files);
            $redirectPath = '/tracking';
            if ((int)$location_id === 1) {
                $redirectPath = '/dpmptsp/tracking';
            } elseif ((int)$location_id === 2) {
                $redirectPath = '/mpp/tracking';
            }
            return redirect()->to($redirectPath . '?ticket=' . $result['ticket_number'] . '&pin=' . $result['secret_pin'])
                ->with('success', 'Pengaduan Anda berhasil dikirim dengan Nomor Tiket ' . $result['ticket_number'] . '. Simpan PIN Anda: ' . $result['secret_pin']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Route: /dpmptsp/faq
     */
    public function dpmptspFaq()
    {
        return view('dpmptsp/faq');
    }

    /**
     * Route: /dpmptsp/about
     */
    public function dpmptspAbout()
    {
        return view('dpmptsp/about');
    }

    /**
     * Route: /mpp/faq
     */
    public function mppFaq()
    {
        return view('mpp/faq');
    }

    /**
     * Route: /mpp/about
     */
    public function mppAbout()
    {
        return view('mpp/about');
    }
}
