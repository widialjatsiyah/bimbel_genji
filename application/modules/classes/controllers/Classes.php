<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Classes extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'AppModel',
            'ClassModel',
            'SchoolModel',
            'UserModel' // perlu dibuat untuk mengambil data guru
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        // Ambil data sekolah untuk combobox
        $schools = $this->SchoolModel->getAll([], 'name', 'asc');
        $list_school = $this->init_list($schools, 'id', 'name');

        // Ambil data guru (user dengan role 'teacher')
        $this->load->model('UserModel');
        $teachers = $this->UserModel->getAll(['role' => 'teacher'], 'nama_lengkap', 'asc');
        $list_teacher = $this->init_list($teachers, 'id', 'nama_lengkap');

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('classes'),
            'card_title' => 'Kelas',
            'list_school' => $list_school,
            'list_teacher' => $list_teacher,
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();
        $dtAjax_config = array(
            'select_column' => [
                'classes.id',
                'classes.name',
                'classes.academic_year',
                'classes.grade_level',
                'schools.name as school_name',
                'user.nama_lengkap as teacher_name',
                'classes.school_id',
                'classes.teacher_id'
            ],
            'table_name' => 'classes',
            'table_join' => [
                [
                    'table_name' => 'schools',
                    'expression' => 'schools.id = classes.school_id',
                    'type' => 'inner'
                ],
                [
                    'table_name' => 'user',
                    'expression' => 'user.id = classes.teacher_id',
                    'type' => 'left'
                ]
            ],
            'order_column' => 1,
            'order_column_dir' => 'asc',
        );
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->form_validation->set_rules($this->ClassModel->rules());

        if ($this->form_validation->run() === true) {
            if (is_null($id)) {
                echo json_encode($this->ClassModel->insert());
            } else {
                echo json_encode($this->ClassModel->update($id));
            }
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->ClassModel->delete($id));
    }
}
