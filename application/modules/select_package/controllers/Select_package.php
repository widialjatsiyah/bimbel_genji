<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Select_package extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('user')) {
            redirect('login');
        }
        $this->load->model(['PackageModel', 'UserPackageModel']);
    }

    public function index()
    {
        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('select_package'),
            'card_title' => 'Pilih Paket Belajar',
            'packages' => $this->PackageModel->getAll(['is_active' => 1], 'price', 'asc')
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function choose($package_id)
    {
        $user = $this->session->userdata('user');
        $package = $this->PackageModel->getDetail(['id' => $package_id]);
        if (!$package) {
            show_404();
        }

        // Hitung tanggal mulai dan akhir
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+{$package->duration_days} days"));

        $data = [
            'user_id' => $user['id'],
            'package_id' => $package_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => 'active',
            'payment_status' => 'paid' // asumsi langsung aktif, nanti bisa diubah jika ada pembayaran
        ];
        $this->UserPackageModel->insertArray($data);

        redirect('dashboard');
    }
}
