<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Certificate extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            // show_error('Akses ditolak', 403);
        }
        $this->load->model('CertificateModel');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];
        $certificates = $this->CertificateModel->getByUser($user_id);

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('certificate'),
            'card_title' => 'Sertifikat Saya',
            'certificates' => $certificates
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function view($id)
    {
        $cert = $this->CertificateModel->getDetail($id);
        if (!$cert || $cert->user_id != $this->session->userdata('user')['id']) {
            show_404();
        }
        // Tampilkan file PDF atau HTML sertifikat
        // Jika file_url disimpan, bisa redirect ke sana
        if ($cert->file_url) {
            redirect($cert->file_url);
        } else {
            echo 'File sertifikat tidak ditemukan.';
        }
    }
}
