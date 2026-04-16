<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Dashboard_tutor extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('user')['role'] != 'tutor') {
            // show_error('Akses ditolak', 403);
        }
        $this->load->model([
            'ClassModel',
            'StudentClassModel',
            'UserModel',
            'UserTryoutModel',
            'StudentProgressModel',
            'RecommendationModel'
        ]);
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];

        // Ambil kelas yang diajar
        $classes = $this->ClassModel->getByTeacher($user_id);
        if (!$classes) $classes = [];

        // Kumpulkan semua ID siswa dari semua kelas
        $student_ids = [];
        foreach ($classes as $c) {
            $students = $this->StudentClassModel->getStudentsByClass($c->id);
            foreach ($students as $s) {
                $student_ids[] = $s->id;
            }
        }
        $student_ids = array_unique($student_ids);

        // Data untuk setiap kelas (jumlah siswa, rata-rata skor)
        foreach ($classes as $c) {
            $c->student_count = $this->StudentClassModel->countStudentsByClass($c->id);
            $c->avg_score = $this->UserTryoutModel->getAverageScoreByClass($c->id);
        }

        // Statistik global
        $total_students = count($student_ids);
        $total_classes = count($classes);

        // Siswa perlu perhatian
        $students_in_need = $this->StudentProgressModel->getStudentsNeedAttention($student_ids);
        if (!is_array($students_in_need)) $students_in_need = [];

        // Rekomendasi terbaru
        $recent_recommendations = $this->RecommendationModel->getRecentByStudents($student_ids, 10);
        if (!is_array($recent_recommendations)) $recent_recommendations = [];

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('dashboard_tutor'),
            'card_title' => 'Dashboard Tutor',
            'classes' => $classes,
            'total_students' => $total_students,
            'total_classes' => $total_classes,
            'students_in_need' => $students_in_need,
            'recent_recommendations' => $recent_recommendations
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function class_detail($class_id)
    {
        $class = $this->ClassModel->getDetail(['id' => $class_id]);
        if (!$class) show_404();

        $students = $this->StudentClassModel->getStudentsByClass($class_id);
        foreach ($students as $s) {
            $s->latest_tryout = $this->UserTryoutModel->getLatestByUser($s->id);
            $s->progress = $this->StudentProgressModel->getLatest($s->id);
        }

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('dashboard_tutor'),
            'card_title' => 'Detail Kelas: ' . $class->name,
            'class' => $class,
            'students' => $students
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('class_detail', $data, TRUE);
        $this->template->render();
    }
}
