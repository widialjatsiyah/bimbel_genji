<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Tryout_class extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya untuk admin sekolah, tutor, atau admin
        $role = $this->session->userdata('user')['role'];
        // if (!in_array($role, ['admin', 'school_admin', 'tutor'])) {
        //     show_error('Akses ditolak', 403);
        // }
        $this->load->model([
            'TryoutClassModel',
            'TryoutModel',
            'ClassModel',
            'SchoolModel'
        ]);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user = $this->session->userdata('user');
        // Tentukan daftar kelas yang bisa diakses user
        if ($user['role'] == 'Administrator') {
            $classes = $this->ClassModel->getAll([], 'name', 'asc');
            $tryouts = $this->TryoutModel->getAll([], 'title', 'asc');
        } elseif ($user['role'] == 'school_admin') {
            // Ambil sekolah dari user (unit = school_id)
            $classes = $this->ClassModel->getAll(['school_id' => $user['unit']], 'name', 'asc');
            $tryouts = $this->TryoutModel->getAll([], 'title', 'asc'); // semua try out, atau filter?
        } elseif ($user['role'] == 'tutor') {
            // Ambil kelas yang diajar tutor
            $classes = $this->ClassModel->getByTeacher($user['id']);
            $tryouts = $this->TryoutModel->getAll([], 'title', 'asc');
        }

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('tryout_class'),
            'card_title' => 'Jadwal Try Out per Kelas',
            'list_class' => $this->init_list($classes, 'id', 'name'),
            'list_tryout' => $this->init_list($tryouts, 'id', 'title')
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();
        $user = $this->session->userdata('user');
        $dtAjax_config = array(
            'select_column' => [
                'tryout_class.id',
                'tryout_class.tryout_id',
                'tryout_class.class_id',
                'tryout_class.start_time',
                'tryout_class.end_time',
                'tryout_class.is_active',
                'tryout_class.created_at',
                'tryouts.title as tryout_title',
                'classes.name as class_name'
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
            'order_column' => 5,
            'order_column_dir' => 'desc',
        );
        // Filter berdasarkan role
        if ($user['role'] == 'school_admin') {
            $dtAjax_config['static_conditional'] = ['classes.school_id' => $user['unit']];
        } elseif ($user['role'] == 'tutor') {
            $classes = $this->ClassModel->getByTeacher($user['id']);
            $class_ids = array_column($classes, 'id');
            if (!empty($class_ids)) {
                $dtAjax_config['static_conditional_in_key'] = 'tryout_class.class_id';
                $dtAjax_config['static_conditional_in'] = $class_ids;
            } else {
                // tidak ada kelas, return kosong
                echo json_encode(['data' => []]);
                return;
            }
        }
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->form_validation->set_rules($this->TryoutClassModel->rules());

        if ($this->form_validation->run() === true) {
            if (is_null($id)) {
                echo json_encode($this->TryoutClassModel->insert());
            } else {
                echo json_encode($this->TryoutClassModel->update($id));
            }
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->TryoutClassModel->delete($id));
    }
}
