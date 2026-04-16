<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Tryout_schedule extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya untuk tutor dan admin (admin juga bisa akses, tapi kita filter di method)
        $role = $this->session->userdata('user')['role'];
        if (!in_array($role, ['tutor', 'Administrator'])) {
            // show_error('Akses ditolak', 403);
        }
        $this->load->model(['AppModel', 'TryoutClassModel', 'TryoutModel', 'ClassModel']);
    }

    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('tryout_schedule'),
            'card_title' => 'Jadwal Try Out Kelas'
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();
        $user = $this->session->userdata('user');
        $role = $user['role'];

        // Filter berdasarkan role
        $static_conditional = [];
        if ($role == 'tutor') {
            // Ambil kelas yang diajar tutor ini
            $this->load->model('ClassModel');
            $classes = $this->ClassModel->getByTeacher($user['id']);
            $class_ids = array_column($classes, 'id');
            if (empty($class_ids)) {
                $class_ids = [0]; // supaya tidak muncul data
            }
            $static_conditional_in_key = 'class_id';
            $static_conditional_in = $class_ids;
        } else {
            $static_conditional_in_key = null;
            $static_conditional_in = [];
        }

        $dtAjax_config = [
            'select_column' => [
                'tryout_class.id',
                'tryouts.title as tryout_title',
                'classes.name as class_name',
                'tryout_class.start_time',
                'tryout_class.end_time',
                'tryout_class.is_active'
            ],
            'table_name' => 'tryout_class',
            'table_join' => [
                [
                    'table_name' => 'tryouts',
                    'expression' => 'tryouts.id = tryout_class.tryout_id',
                    'type' => 'left'
                ],
                [
                    'table_name' => 'classes',
                    'expression' => 'classes.id = tryout_class.class_id',
                    'type' => 'left'
                ]
            ],
            'static_conditional' => $static_conditional,
            'static_conditional_in_key' => $static_conditional_in_key,
            'static_conditional_in' => $static_conditional_in,
            'order_column' => 3, // start_time
            'order_column_dir' => 'desc',
        ];
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->load->library('form_validation');
        $this->form_validation->set_rules([
            [
                'field' => 'tryout_id',
                'label' => 'Try Out',
                'rules' => 'required|integer'
            ],
            [
                'field' => 'class_id',
                'label' => 'Kelas',
                'rules' => 'required|integer'
            ],
            [
                'field' => 'start_time',
                'label' => 'Waktu Mulai',
                'rules' => 'required'
            ],
            [
                'field' => 'end_time',
                'label' => 'Waktu Selesai',
                'rules' => 'required'
            ],
            [
                'field' => 'is_active',
                'label' => 'Status Aktif',
                'rules' => 'in_list[0,1]'
            ]
        ]);

        if ($this->form_validation->run() == true) {
            $data = [
                'tryout_id' => $this->input->post('tryout_id'),
                'class_id' => $this->input->post('class_id'),
                'start_time' => $this->input->post('start_time'),
                'end_time' => $this->input->post('end_time'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];
            if (is_null($id)) {
                $res = $this->TryoutClassModel->insert($data);
            } else {
                $res = $this->TryoutClassModel->update($id, $data);
            }
            echo json_encode($res);
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(['status' => false, 'data' => $errors]);
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        $res = $this->TryoutClassModel->delete($id);
        echo json_encode($res);
    }

    // Untuk mendapatkan daftar try out (untuk dropdown)
    public function ajax_get_tryouts()
    {
        $this->handle_ajax_request();
        $tryouts = $this->TryoutModel->getAll(['is_published' => 1], 'title', 'asc');
        echo json_encode($tryouts);
    }

    // Untuk mendapatkan daftar kelas yang diajar tutor (untuk dropdown)
    public function ajax_get_classes()
    {
        $this->handle_ajax_request();
        $user = $this->session->userdata('user');
        if ($user['role'] == 'tutor') {
            $classes = $this->ClassModel->getByTeacher($user['id']);
        } else {
            // admin bisa melihat semua kelas
            $classes = $this->ClassModel->getAll([], 'name', 'asc');
        }
        echo json_encode($classes);
    }
}
