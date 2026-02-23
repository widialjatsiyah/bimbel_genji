<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class My_packages extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya untuk role student
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            show_error('Akses ditolak', 403);
        }
        $this->load->model(['AppModel']);
    }

    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('my_packages'),
            'card_title' => 'Paket Saya'
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();
        $user_id = $this->session->userdata('user')['id'];

        $dtAjax_config = [
            'select_column' => [
                'user_packages.id',
                'packages.name as package_name',
                'packages.description',
                'user_packages.start_date',
                'user_packages.end_date',
                'user_packages.status',
                'user_packages.payment_status',
                'packages.price'
            ],
            'table_name' => 'user_packages',
            'table_join' => [
                [
                    'table_name' => 'packages',
                    'expression' => 'packages.id = user_packages.package_id',
                    'type' => 'left'
                ]
            ],
            'static_conditional' => [
                'user_packages.user_id' => $user_id
            ],
            'order_column' => 3, // start_date
            'order_column_dir' => 'desc',
        ];
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }
}
