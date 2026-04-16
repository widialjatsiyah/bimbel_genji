<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Recommendation extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya untuk tutor, admin sekolah, atau admin
        $role = $this->session->userdata('user')['role'];
        if (!in_array($role, ['tutor', 'school_admin', 'admin'])) {
            // show_error('Akses ditolak', 403);
        }
        $this->load->model([
            'RecommendationModel',
            'UserModel',
            'ClassModel',
            'StudentClassModel'
        ]);
    }

    public function index()
    {
        $user = $this->session->userdata('user');
        if ($user['role'] == 'tutor') {
            // Tampilkan rekomendasi untuk siswa dalam kelas yang diajar
            $classes = $this->ClassModel->getByTeacher($user['id']);
            $student_ids = [];
            foreach ($classes as $c) {
                $students = $this->StudentClassModel->getStudentsByClass($c->id);
                foreach ($students as $s) {
                    $student_ids[] = $s->student_id;
                }
            }
            $student_ids = array_unique($student_ids);
            $students = $this->UserModel->getWhereIn('id', $student_ids);
        } elseif ($user['role'] == 'school_admin') {
            // Tampilkan rekomendasi untuk siswa di sekolahnya
            $students = $this->UserModel->getAll(['unit' => $user['unit'], 'role' => 'student']);
        } else {
            // Admin: semua siswa
            $students = $this->UserModel->getAll(['role' => 'student']);
        }

        // Ambil rekomendasi terbaru untuk setiap siswa
        foreach ($students as $s) {
            $s->recommendations = $this->RecommendationModel->getAllByUser($s->id, 3);
        }

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('recommendation'),
            'card_title' => 'Rekomendasi Siswa',
            'students' => $students
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_mark_read($id)
    {
        $this->handle_ajax_request();
        $this->RecommendationModel->markAsRead($id);
        echo json_encode(['status' => true]);
    }

    public function ajax_generate_for_user($user_id)
    {
        $this->handle_ajax_request();
        $count = $this->RecommendationModel->generateForUser($user_id, 'manual');
        echo json_encode(['status' => true, 'count' => $count]);
    }
}
