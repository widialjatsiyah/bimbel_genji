<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class User_tryout extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model([
            'UserTryoutModel',
            'TryoutSessionModel',
            'TryoutQuestionModel',
            'QuestionModel',
            'UserAnswerModel',
            'EssayAnswerModel',
        ]);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('user_tryout'),
            'title' => 'Daftar Try Out',
        ];

        $this->template->set('title', $data['title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function play($id)
    {
        $user_tryout = $this->UserTryoutModel->getById($id);
        $session = $this->TryoutSessionModel->getById($user_tryout->tryout_session_id);

        if (!$user_tryout || !$session) {
            show_404();
            return;
        }

        // Ambil soal-soal untuk sesi ini
        $questions = $this->TryoutQuestionModel->getQuestionsBySession($session->id, $session->is_random);
        $total_questions = count($questions);

        // Ambil jawaban yang sudah diberikan oleh pengguna
        $answer_map = [];
        $answers = $this->UserAnswerModel->getByUserTryoutId($user_tryout->id);
        foreach ($answers as $answer) {
            $answer_map[$answer->question_id] = $answer;
        }
        
        // Ambil jawaban esai yang sudah diberikan oleh pengguna
        $essay_answers = $this->EssayAnswerModel->getAll(['user_tryout_id' => $user_tryout->id]);
        foreach ($essay_answers as $answer) {
            $answer_map[$answer->question_id] = $answer;
        }

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('user_tryout/views/main.js.php'),
            'title' => $session->name,
            'user_tryout' => $user_tryout,
            'session' => $session,
            'questions' => $questions,
            'total_questions' => $total_questions,
            'answer_map' => $answer_map,
        ];

        $this->template->set('title', $data['title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('play', $data, TRUE);
        $this->template->render();
    }

    public function submit_session($user_tryout_id, $session_id)
    {
        // Proses penyelesaian sesi tryout
        $this->UserTryoutModel->finish($user_tryout_id);
        
        redirect('user_tryout/result/' . $user_tryout_id);
    }

    public function result($id)
    {
        // Tampilkan hasil tryout
        $user_tryout = $this->UserTryoutModel->getById($id);
        $session = $this->TryoutSessionModel->getById($user_tryout->tryout_session_id);

        if (!$user_tryout || !$session) {
            show_404();
            return;
        }

        $data = [
            'app' => $this->app(),
            'title' => 'Hasil Try Out',
            'user_tryout' => $user_tryout,
            'session' => $session,
        ];

        $this->template->set('title', $data['title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('result', $data, TRUE);
        $this->template->render();
    }

    public function ajax_save_answer()
    {
        $this->handle_ajax_request();

        $user_tryout_id = $this->input->post('user_tryout_id');
        $question_id = $this->input->post('question_id');
        $answer = $this->input->post('answer');
        $is_unsure = $this->input->post('is_unsure') ?? 0;

        // Validasi input
        if (!$user_tryout_id || !$question_id || !$answer) {
            echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
            return;
        }

        // Simpan jawaban
        $result = $this->UserAnswerModel->saveAnswer($user_tryout_id, $question_id, $answer, $is_unsure);
        echo json_encode($result);
    }

    public function ajax_save_essay_answer()
    {
        $this->handle_ajax_request();

        $user_tryout_id = $this->input->post('user_tryout_id');
        $question_id = $this->input->post('question_id');
        $answer_text = $this->input->post('answer_text');
        $is_unsure = $this->input->post('is_unsure') ?? 0;

        // Validasi input
        if (!$user_tryout_id || !$question_id) {
            echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
            return;
        }

        // Cek apakah sudah ada jawaban sebelumnya
        $existing_answer = $this->EssayAnswerModel->getByUserTryoutAndQuestion($user_tryout_id, $question_id);
        
        if ($existing_answer) {
            // Update jawaban yang sudah ada
            $this->db->where('id', $existing_answer->id);
            $this->db->update('essay_answers', [
                'answer_text' => $answer_text,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $result = ['status' => true, 'message' => 'Jawaban esai diperbarui'];
        } else {
            // Simpan jawaban baru
            $this->db->insert('essay_answers', [
                'user_tryout_id' => $user_tryout_id,
                'question_id' => $question_id,
                'answer_text' => $answer_text,
                'is_unsure' => $is_unsure,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            $result = ['status' => true, 'message' => 'Jawaban esai disimpan'];
        }

        echo json_encode($result);
    }

    public function ajax_mark_unsure()
    {
        $this->handle_ajax_request();

        $user_tryout_id = $this->input->post('user_tryout_id');
        $question_id = $this->input->post('question_id');
        $is_unsure = $this->input->post('is_unsure');

        // Validasi input
        if (!$user_tryout_id || !$question_id || !isset($is_unsure)) {
            echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
            return;
        }

        // Update status unsure di tabel jawaban
        $this->db->where([
            'user_tryout_id' => $user_tryout_id,
            'question_id' => $question_id
        ]);
        $this->db->update('user_answers', ['is_unsure' => $is_unsure]);

        echo json_encode(['status' => true, 'message' => 'Status ragu dirubah']);
    }
}