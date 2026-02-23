<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php'); // mungkin menggunakan AppBackend atau langsung CI_Controller

class Tryout_list extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            show_error('Akses ditolak', 403);
        }
        $this->load->model(['TryoutModel', 'TryoutClassModel', 'StudentClassModel']);
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];
        $tryouts = $this->TryoutModel->getAvailableForStudent($user_id);

        $data = [
            'app' => $this->app(),
            'card_title' => 'Daftar Try Out',
            'tryouts' => $tryouts
        ];
        $this->template->set('title', 'Daftar Try Out | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }
}
