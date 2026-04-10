Tryout_question.php
<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Tryout_question extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['TryoutQuestionModel', 'TryoutSessionModel', 'QuestionModel']);
    }

    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('tryout_question'),
            'title' => 'Soal Try Out',
            'card_title' => 'Manajemen Soal Try Out',
        ];

        $this->template->set('title', $data['title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();

        $dtAjax_config = array(
            'select_column' => [
                'tryout_questions.id',
                'tryout_sessions.name as session_name',
                'questions.question_text',
                'tryout_questions.question_order',
                'tryout_questions.points',
                'tryout_questions.tryout_session_id',
                'tryout_questions.question_id'
            ],
            'table_name' => 'tryout_questions',
            'table_join' => [
                [
                    'table_name' => 'tryout_sessions',
                    'expression' => 'tryout_sessions.id = tryout_questions.tryout_session_id',
                    'type' => 'left'
                ],
                [
                    'table_name' => 'questions',
                    'expression' => 'questions.id = tryout_questions.question_id',
                    'type' => 'left'
                ]
            ],
            'order_column' => 3, // question_order
            'order_column_dir' => 'asc',
        );
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();

        if ($id) {
            $response = $this->TryoutQuestionModel->update($id);
        } else {
            $response = $this->TryoutQuestionModel->insert();
        }

        echo json_encode($response);
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();

        $response = $this->TryoutQuestionModel->delete($id);

        echo json_encode($response);
    }
}