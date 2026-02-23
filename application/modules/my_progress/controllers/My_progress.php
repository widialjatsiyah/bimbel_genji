<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class My_progress extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya untuk role student
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            show_error('Akses ditolak', 403);
        }
        $this->load->model(['AppModel', 'UserMaterialProgressModel']);
    }

    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('my_progress'),
            'card_title' => 'Progres Belajar Saya'
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();
        $user_id = $this->session->userdata('user')['id'];

        // Konfigurasi DataTables dengan join ke materials
        $dtAjax_config = [
            'select_column' => [
                'user_material_progress.id',
                'materials.title',
                'materials.type',
                'user_material_progress.status',
                'user_material_progress.progress_percent',
                'user_material_progress.last_accessed',
                'user_material_progress.completed_at',
                'materials.id as material_id',
                'materials.url'
            ],
            'table_name' => 'user_material_progress',
            'table_join' => [
                [
                    'table_name' => 'materials',
                    'expression' => 'materials.id = user_material_progress.material_id',
                    'type' => 'inner'
                ]
            ],
            'static_conditional' => [
                'user_material_progress.user_id' => $user_id
            ],
            'order_column' => 5, // last_accessed
            'order_column_dir' => 'desc',
        ];
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }
}
