<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Myrecommendations extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya untuk role student
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            // show_error('Akses ditolak', 403);
        }
        $this->load->model(['AppModel', 'RecommendationModel']);
    }

    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('myrecommendations'),
            'card_title' => 'Rekomendasi Belajar Saya'
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
                'id',
                'recommendation_text',
                'type',
                'is_read',
                'created_at'
            ],
            'table_name' => 'recommendations',
            'static_conditional' => [
                'user_id' => $user_id
            ],
            'order_column' => 4, // created_at
            'order_column_dir' => 'desc',
        ];
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_mark_read($id)
    {
        $this->handle_ajax_request();
        $this->RecommendationModel->markAsRead($id);
        echo json_encode(['status' => true]);
    }

    public function ajax_mark_all_read()
    {
        $this->handle_ajax_request();
        $user_id = $this->session->userdata('user')['id'];
        $this->RecommendationModel->markAllAsRead($user_id);
        echo json_encode(['status' => true]);
    }
}
