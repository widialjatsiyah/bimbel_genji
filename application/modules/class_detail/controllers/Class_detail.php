<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Class_detail extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // if ($this->session->userdata('user')['role'] != 'student') {
        //     show_error('Akses ditolak', 403);
        // }
        $this->load->model(['ClassModel', 'ClassMeetingModel', 'MeetingMaterialModel', 'MeetingQuizModel', 'UserPackageModel']);
    }

    public function index($class_id)
    {
        $user_id = $this->session->userdata('user')['id'];
        // Cek akses
        if (!$this->UserPackageModel->hasAccessTo($user_id, 'class', $class_id)) {
            show_error('Anda tidak memiliki akses ke kelas ini', 403);
        }

        $class = $this->ClassModel->getDetail(['id' => $class_id]);
        if (!$class) show_404();

        $meetings = $this->ClassMeetingModel->getByClass($class_id);
        foreach ($meetings as $m) {
            $m->materials = $this->MeetingMaterialModel->getByMeeting($m->id);
            $m->quizzes = $this->MeetingQuizModel->getByMeeting($m->id);
        }

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('class_detail'),
            'card_title' => 'Detail Kelas: ' . $class->name,
            'class' => $class,
            'meetings' => $meetings
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }
}
