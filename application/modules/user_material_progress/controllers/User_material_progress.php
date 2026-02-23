<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class User_material_progress extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'AppModel',
            'UserMaterialProgressModel',
            'UserModel',
            'MaterialModel'
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('user_material_progress'),
            'card_title' => 'Progres Belajar Siswa'
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();

        // Filtering (opsional: filter berdasarkan user)
        $user_id = $this->input->get('user_id');
        $filters = [];
        if (!empty($user_id)) {
            $filters['user_id'] = $user_id;
        }

        $dtAjax_config = array(
            'select_column' => [
                'user_material_progress.id',
                'user.nama_lengkap as user_name',
                'materials.title as material_title',
                'materials.type as material_type',
                'user_material_progress.status',
                'user_material_progress.progress_percent',
                'user_material_progress.last_accessed',
                'user_material_progress.completed_at'
            ],
            'table_name' => 'user_material_progress',
            'table_join' => [
                [
                    'table_name' => 'user',
                    'expression' => 'user.id = user_material_progress.user_id',
                    'type' => 'left'
                ],
                [
                    'table_name' => 'materials',
                    'expression' => 'materials.id = user_material_progress.material_id',
                    'type' => 'left'
                ]
            ],
            'order_column' => 6, // last_accessed
            'order_column_dir' => 'desc',
            'filters' => $filters
        );
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->UserMaterialProgressModel->delete($id));
    }
}
