<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Student_material extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            show_error('Akses ditolak', 403);
        }
        $this->load->model([
            'MaterialModel',
            'UserMaterialProgressModel',
            'SubjectModel'
        ]);
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];

        // Ambil semua materi aktif
        $materials = $this->MaterialModel->getAll(['is_active' => 1], 'title', 'asc');

        // Ambil progres siswa untuk setiap materi
        foreach ($materials as $m) {
            $m->progress = $this->UserMaterialProgressModel->getProgress($user_id, $m->id);
        }

        // Kelompokkan berdasarkan subject (opsional)
        $subjects = $this->SubjectModel->getAll([], 'name', 'asc');

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('student_material'),
            'card_title' => 'Materi Belajar',
            'materials' => $materials,
            'subjects' => $subjects
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function view($id)
    {
        $user_id = $this->session->userdata('user')['id'];
        $material = $this->MaterialModel->getDetail(['id' => $id, 'is_active' => 1]);
        if (!$material) {
            show_404();
        }

        // Update progres (tandai sebagai diakses)
        $this->load->model('UserMaterialProgressModel');
        $progress = $this->UserMaterialProgressModel->getProgress($user_id, $id);
        $data = [
            'last_accessed' => date('Y-m-d H:i:s'),
            'status' => 'in_progress'
        ];
        if (!$progress) {
            $data['progress_percent'] = 0;
        }
        $this->UserMaterialProgressModel->updateProgress($user_id, $id, $data);

        // Redirect ke URL materi
        redirect($material->url);
    }
}
