<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Meeting_quiz extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya untuk role tutor
        if ($this->session->userdata('user')['role'] != 'tutor') {
            show_error('Akses ditolak', 403);
        }
        $this->load->model([
            'MeetingQuizModel',
            'ClassMeetingModel',
            'ClassModel',
            'TryoutModel'
        ]);
        $this->load->library('form_validation');
    }

    public function index($meeting_id)
    {
        // Cek akses tutor ke meeting ini
        $meeting = $this->ClassMeetingModel->getById($meeting_id);
        if (!$meeting) {
            show_404();
        }

        // Cek apakah tutor mengajar kelas dari meeting ini
        $teacher_id = $this->session->userdata('user')['id'];
        $class = $this->ClassModel->getDetail(['id' => $meeting->class_id, 'teacher_id' => $teacher_id]);
        if (!$class) {
            show_error('Anda tidak memiliki akses ke kelas ini', 403);
        }

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('meeting_quiz'),
            'card_title' => 'Kelola Quiz: ' . $meeting->title,
            'meeting' => $meeting,
            'meeting_id' => $meeting_id
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all($meeting_id)
    {
        $this->handle_ajax_request();
        $quizzes = $this->MeetingQuizModel->getByMeeting($meeting_id);
        $response = [
            'data' => $quizzes,
            'recordsTotal' => count($quizzes),
            'recordsFiltered' => count($quizzes),
            'draw' => $this->input->post('draw')
        ];
        echo json_encode($response);
    }

    public function ajax_get_available_quizzes($meeting_id)
    {
        $this->handle_ajax_request();
        $meeting = $this->ClassMeetingModel->getById($meeting_id);
        if (!$meeting) {
            echo json_encode([]);
            return;
        }

        // Ambil semua tryout dengan type 'Quiz' yang aktif
        $all_quizzes = $this->TryoutModel->getAll(['type' => 'Quiz', 'is_active' => 1], 'title', 'asc');
        
        // Ambil quiz yang sudah ada di meeting ini
        $existing = $this->MeetingQuizModel->getByMeeting($meeting_id);
        $existing_ids = array_column($existing, 'quiz_id');

        // Filter yang belum ada
        $available = [];
        foreach ($all_quizzes as $q) {
            if (!in_array($q->id, $existing_ids)) {
                $available[] = [
                    'id' => $q->id,
                    'text' => $q->title . ' (' . $q->total_duration . ' menit)'
                ];
            }
        }

        echo json_encode($available);
    }

    public function ajax_add()
    {
        $this->handle_ajax_request();
        $meeting_id = $this->input->post('meeting_id');
        $quiz_id = $this->input->post('quiz_id');
        $order_num = $this->input->post('order_num') ?: 0;

        // Validasi akses meeting
        $meeting = $this->ClassMeetingModel->getById($meeting_id);
        if (!$meeting) {
            echo json_encode(['status' => false, 'data' => 'Meeting tidak ditemukan']);
            return;
        }

        $teacher_id = $this->session->userdata('user')['id'];
        $class = $this->ClassModel->getDetail(['id' => $meeting->class_id, 'teacher_id' => $teacher_id]);
        if (!$class) {
            echo json_encode(['status' => false, 'data' => 'Akses ditolak']);
            return;
        }

        // Cek apakah quiz sudah ada
        $existing = $this->db->where('meeting_id', $meeting_id)
                              ->where('quiz_id', $quiz_id)
                              ->get('meeting_quizzes')
                              ->row();
        if ($existing) {
            echo json_encode(['status' => false, 'data' => 'Quiz sudah ditambahkan']);
            return;
        }

        $this->MeetingQuizModel->addQuiz($meeting_id, $quiz_id, $order_num);
        echo json_encode(['status' => true, 'data' => 'Quiz berhasil ditambahkan']);
    }

    public function ajax_remove($id)
    {
        $this->handle_ajax_request();
        $item = $this->MeetingQuizModel->getById($id);
        if (!$item) {
            echo json_encode(['status' => false, 'data' => 'Data tidak ditemukan']);
            return;
        }

        // Validasi akses
        $meeting = $this->ClassMeetingModel->getById($item->meeting_id);
        if (!$meeting) {
            echo json_encode(['status' => false, 'data' => 'Meeting tidak ditemukan']);
            return;
        }

        $teacher_id = $this->session->userdata('user')['id'];
        $class = $this->ClassModel->getDetail(['id' => $meeting->class_id, 'teacher_id' => $teacher_id]);
        if (!$class) {
            echo json_encode(['status' => false, 'data' => 'Akses ditolak']);
            return;
        }

        $this->MeetingQuizModel->removeQuiz($id);
        echo json_encode(['status' => true, 'data' => 'Quiz berhasil dihapus']);
    }
}
