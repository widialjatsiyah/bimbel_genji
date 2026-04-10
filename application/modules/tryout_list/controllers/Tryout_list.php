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
        $this->load->model([
            'TryoutModel', 
            'TryoutSessionModel', 
            'TryoutClassModel', 
            'StudentClassModel',
            'UserTryoutModel'
        ]);
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];
        $tryouts = $this->TryoutModel->getAvailableForStudent($user_id);

        // Ambil semua sesi untuk setiap tryout
        foreach ($tryouts as $tryout) {
            $tryout->sessions = $this->TryoutSessionModel->getByTryout($tryout->id);
            
            // Periksa apakah user sudah mengerjakan sesi apa pun dari tryout ini
            $tryout->completed_sessions = 0;
            foreach ($tryout->sessions as $session) {
                // Gunakan fungsi yang kompatibel dengan struktur database saat ini
                if ($this->UserTryoutModel->columnExists('tryout_session_id', 'user_tryouts')) {
                    $user_tryout = $this->UserTryoutModel->getActiveUserTryoutBySession($user_id, $session->id);
                } else {
                    // Jika kolom tidak ada, lewati pengecekan ini
                    $user_tryout = null;
                }
                
                if ($user_tryout && $user_tryout->status === 'completed') {
                    $tryout->completed_sessions++;
                }
            }
        }

        $data = [
            'app' => $this->app(),
            'card_title' => 'Daftar Try Out',
            'tryouts' => $tryouts
        ];
        $this->template->set('title', 'Daftar Try Out | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }
    
    public function start($tryout_id)
    {
        $user_id = $this->session->userdata('user')['id'];
        
        // Ambil tryout
        $tryout = $this->TryoutModel->getDetail(['id' => $tryout_id]);
        if (!$tryout) {
            show_404();
            return;
        }
        
        // Ambil sesi pertama dari tryout ini
        $first_session = $this->TryoutSessionModel->getFirstSession($tryout_id);
        if (!$first_session) {
            $this->session->set_flashdata('error', 'Tryout tidak memiliki sesi apapun.');
            redirect('tryout_list');
            return;
        }
        
        // Mulai sesi pertama
        // Gunakan fungsi yang kompatibel dengan struktur database saat ini
        if ($this->UserTryoutModel->columnExists('tryout_session_id', 'user_tryouts')) {
            $user_tryout_id = $this->UserTryoutModel->startTryoutWithSession($user_id, $first_session->id);
        } else {
            // Jika kolom tidak ada, kembali ke metode lama
            $user_tryout_id = $this->UserTryoutModel->startTryout($user_id, $tryout_id);
        }
        
        // Jika user_tryout_id adalah 0, artinya gagal membuat entri
        if ($user_tryout_id == 0) {
            $this->session->set_flashdata('error', 'Gagal memulai tryout. Silakan coba lagi.');
            redirect('tryout_list');
            return;
        }
        
        redirect('user_tryout/play/' . $user_tryout_id);
    }
}