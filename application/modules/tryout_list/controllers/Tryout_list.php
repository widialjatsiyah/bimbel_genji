<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php'); // mungkin menggunakan AppBackend atau langsung CI_Controller

class Tryout_list extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            // show_error('Akses ditolak', 403);
        }
        $this->load->model([
            'TryoutModel', 
            'TryoutSessionModel', 
            'TryoutClassModel', 
            'StudentClassModel',
            'UserTryoutModel',
            'TryoutQuestionModel'
        ]);
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];
        $tryouts = $this->TryoutModel->getAvailableForStudent($user_id);

        // Ambil semua sesi untuk setiap tryout
        foreach ($tryouts as $tryout) {
            $tryout->sessions = $this->TryoutSessionModel->getByTryout($tryout->id);
            
            // Hitung jumlah soal total untuk tryout ini
            $total_questions = 0;
            foreach ($tryout->sessions as $session) {
                $session_questions = $this->TryoutQuestionModel->getQuestionsBySession($session->id);
                $total_questions += count($session_questions);
            }
            $tryout->total_questions = $total_questions;
            
            // Hitung durasi total
            $total_duration = 0;
            foreach ($tryout->sessions as $session) {
                $total_duration += $session->duration_minutes;
            }
            $tryout->total_duration = $total_duration;
            
            // Tambahkan jumlah total sesi
            $tryout->total_sessions = count($tryout->sessions);
            
            // Periksa apakah tryout gratis
            $tryout->is_free = isset($tryout->is_free) ? $tryout->is_free : 0;
            
            // Periksa apakah user sudah mengerjakan sesi apa pun dari tryout ini
            $tryout->completed_sessions = 0;
            foreach ($tryout->sessions as $session) {
                // Gunakan fungsi yang kompatibel dengan struktur database saat ini
                if ($this->UserTryoutModel->columnExists('tryout_session_id', 'user_tryouts')) {
                    // Cek apakah sesi ini sudah selesai dikerjakan
                    $completed_tryout = $this->UserTryoutModel->getCompletedUserTryoutBySession($user_id, $session->id);
                    
                    if ($completed_tryout) {
                        $tryout->completed_sessions++;
                    }
                    // Sesi aktif tidak boleh dihitung sebagai sesi yang telah selesai
                    // Jadi kami menghapus bagian pengecekan aktif di sini
                    
                } else {
                    // Jika kolom tidak ada, lewati pengecekan ini
                    $user_tryout = null;
                }
            }
        }

        // Hitung jumlah soal dan durasi total hanya sekali
        foreach ($tryouts as $tryout) {
            $total_questions = 0;
            $total_duration = 0;
            
            foreach ($tryout->sessions as $session) {
                // Hitung jumlah soal
                $session_questions = $this->TryoutQuestionModel->getQuestionsBySession($session->id);
                $total_questions += count($session_questions);
                
                // Hitung durasi
                $total_duration += $session->duration_minutes;
            }
            
            $tryout->total_questions = $total_questions;
            $tryout->total_duration = $total_duration;
            $tryout->total_sessions = count($tryout->sessions);
            $tryout->is_free = isset($tryout->is_free) ? $tryout->is_free : 0;
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
        
        // Cek apakah user sudah memiliki sesi aktif
        $active_tryout = null;
        if ($this->UserTryoutModel->columnExists('tryout_session_id', 'user_tryouts')) {
            // Cek apakah user sedang mengerjakan sesi ini
            $active_tryout = $this->UserTryoutModel->getActiveUserTryoutBySession($user_id, $first_session->id);
        } else {
            // Cek apakah user sedang mengerjakan tryout ini (tanpa session)
            $active_tryout = $this->UserTryoutModel->getActiveUserTryout($user_id, $tryout_id);
        }
        
        // Jika ada sesi aktif, cek apakah telah kadaluarsa
        if ($active_tryout && $active_tryout->status === 'in_progress') {
            if ($this->UserTryoutModel->isSessionExpired($active_tryout->id)) {
                // Selesaikan sesi jika telah kadaluarsa
                $this->UserTryoutModel->completeExpiredSession($active_tryout->id);
                
                // Tampilkan pesan bahwa waktu telah habis
                $this->session->set_flashdata('error', 'Waktu pengerjaan sebelumnya telah habis. Silakan mulai kembali.');
                
                // Redirect ke halaman hasil atau kembali ke daftar tryout
                redirect('tryout_list');
                return;
            }
            
            // Jika sesi aktif belum kadaluarsa, lanjutkan ke sesi tersebut
            redirect('user_tryout/resume/' . $active_tryout->id);
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
