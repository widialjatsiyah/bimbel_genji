<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Class_meeting extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya untuk role tutor
        if ($this->session->userdata('user')['role'] != 'tutor' && $this->session->userdata('user')['role'] != 'Administrator') {
            show_error('Akses ditolak', 403);
        }
        $this->load->model([
            'AppModel',
            'ClassMeetingModel',
            'ClassModel'
        ]);
        $this->load->library('form_validation');
    }

    public function index($class_id = null)
    {
        // Jika class_id tidak diberikan, tampilkan daftar kelas yang diajar
        if (!$class_id) {
            redirect('dashboard_tutor');
        }

        // Validasi apakah tutor mengajar kelas ini
        $teacher_id = $this->session->userdata('user')['id'];
        $class = $this->ClassModel->getDetail(['id' => $class_id, 'teacher_id' => $teacher_id]);
        if (!$class) {
            show_error('Anda tidak memiliki akses ke kelas ini', 403);
        }

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('class_meeting',false,['class_id' => $class_id]),
            'card_title' => 'Pertemuan Kelas: ' . $class->name,
            'class' => $class,
            'class_id' => $class_id
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all($class_id)
    {
        $this->handle_ajax_request();
        // Validasi akses kelas
        $teacher_id = $this->session->userdata('user')['id'];
        $class = $this->ClassModel->getDetail(['id' => $class_id, 'teacher_id' => $teacher_id]);
        if (!$class) {
            echo json_encode(['error' => 'Akses ditolak']);
            return;
        }

        $dtAjax_config = [
            'select_column' => [
                'id',
                'title',
                'description',
                'date',
                'start_time',
                'end_time',
                'meeting_link',
                'order_num'
            ],
            'table_name' => 'class_meetings',
            'static_conditional' => ['class_id' => $class_id],
            'order_column' => 7, // order_num
            'order_column_dir' => 'asc',
        ];
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($class_id, $id = null)
    {
        $this->handle_ajax_request();
        // Validasi akses kelas
        $teacher_id = $this->session->userdata('user')['id'];
        $class = $this->ClassModel->getDetail(['id' => $class_id, 'teacher_id' => $teacher_id]);
        if (!$class) {
            echo json_encode(['status' => false, 'data' => 'Akses ditolak']);
            return;
        }

        $this->form_validation->set_rules($this->ClassMeetingModel->rules());

        if ($this->form_validation->run() === true) {
            // Set class_id dari parameter
            $_POST['class_id'] = $class_id;
            if (is_null($id)) {
                $res = $this->ClassMeetingModel->insert();
            } else {
                $res = $this->ClassMeetingModel->update($id);
            }
            echo json_encode($res);
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(['status' => false, 'data' => $errors]);
        }
    }

    public function ajax_delete($class_id, $id)
    {
        $this->handle_ajax_request();
        // Validasi akses kelas
        $teacher_id = $this->session->userdata('user')['id'];
        $class = $this->ClassModel->getDetail(['id' => $class_id, 'teacher_id' => $teacher_id]);
        if (!$class) {
            echo json_encode(['status' => false, 'data' => 'Akses ditolak']);
            return;
        }

        echo json_encode($this->ClassMeetingModel->delete($id));
    }
}
