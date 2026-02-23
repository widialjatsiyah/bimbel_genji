<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Tryout_question extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'AppModel',
            'TryoutQuestionModel',
            'TryoutSessionModel',
            'TryoutModel',
            'QuestionModel'
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        // Ambil data sesi untuk combobox, dengan informasi try out juga
        $sessions = $this->db->select('tryout_sessions.*, tryouts.title as tryout_title')
            ->from('tryout_sessions')
            ->join('tryouts', 'tryouts.id = tryout_sessions.tryout_id')
            ->order_by('tryouts.title', 'asc')
            ->order_by('tryout_sessions.session_order', 'asc')
            ->get()
            ->result();

        // Format untuk combobox: "Tryout - Sesi (order)"
        $list_session = '';
        foreach ($sessions as $s) {
            $list_session .= '<option value="' . $s->id . '">' . $s->tryout_title . ' - ' . $s->name . ' (Urutan ' . $s->session_order . ')</option>';
        }

        // Ambil soal untuk combobox (bisa difilter nanti, tapi untuk sederhana ambil semua)
        $questions = $this->QuestionModel->getAll([], 'id', 'asc');
        $list_question = $this->init_list($questions, 'id', 'question_text', 50); // ambil 50 karakter pertama

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('tryout_question'),
            'card_title' => 'Soal Try Out',
            'list_session' => $list_session,
            'list_question' => $list_question
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();

        $dtAjax_config = array(
            'select_column' => [
                'tryout_questions.id',
                'tryouts.title as tryout_title',
                'tryout_sessions.name as session_name',
                'tryout_sessions.session_order',
                'questions.question_text as question_text',
                'subjects.name as subject_name',
                'tryout_questions.question_order',
                'tryout_questions.tryout_session_id',
                'tryout_questions.question_id'
            ],
            'table_name' => 'tryout_questions',
            'table_join' => [
                [
                    'table_name' => 'tryout_sessions',
                    'expression' => 'tryout_sessions.id = tryout_questions.tryout_session_id',
                    'type' => 'inner'
                ],
                [
                    'table_name' => 'tryouts',
                    'expression' => 'tryouts.id = tryout_sessions.tryout_id',
                    'type' => 'inner'
                ],
                [
                    'table_name' => 'questions',
                    'expression' => 'questions.id = tryout_questions.question_id',
                    'type' => 'inner'
                ],
                [
                    'table_name' => 'subjects',
                    'expression' => 'subjects.id = questions.subject_id',
                    'type' => 'left'
                ]
            ],
            'order_column' => 1, // tryout_title
            'order_column_dir' => 'asc',
        );
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->form_validation->set_rules($this->TryoutQuestionModel->rules());

        if ($this->form_validation->run() === true) {
            if (is_null($id)) {
                echo json_encode($this->TryoutQuestionModel->insert());
            } else {
                echo json_encode($this->TryoutQuestionModel->update($id));
            }
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->TryoutQuestionModel->delete($id));
    }

    // Ajax untuk mendapatkan sesi berdasarkan tryout (jika diperlukan chained dropdown)
    public function ajax_get_sessions_by_tryout()
    {
        $this->handle_ajax_request();
        $tryout_id = $this->input->get('tryout_id');
        $sessions = $this->TryoutSessionModel->getAll(['tryout_id' => $tryout_id], 'session_order', 'asc');
        echo json_encode($sessions);
    }
}
