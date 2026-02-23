<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Tryout_session extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'AppModel',
            'TryoutSessionModel',
            'TryoutModel'
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        // Ambil data tryout untuk combobox
        $tryouts = $this->TryoutModel->getAll([], 'title', 'asc');
        $list_tryout = $this->init_list($tryouts, 'id', 'title');

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('tryout_session'),
            'card_title' => 'Sesi Try Out',
            'list_tryout' => $list_tryout
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
                'tryout_sessions.id',
                'tryout_sessions.name',
                'tryout_sessions.session_order',
                'tryout_sessions.duration_minutes',
                'tryout_sessions.question_count',
                'tryout_sessions.description',
                'tryouts.title as tryout_title',
                'tryout_sessions.tryout_id'
            ],
            'table_name' => 'tryout_sessions',
            'table_join' => [
                [
                    'table_name' => 'tryouts',
                    'expression' => 'tryouts.id = tryout_sessions.tryout_id',
                    'type' => 'left'
                ]
            ],
            'order_column' => 2, // session_order
            'order_column_dir' => 'asc',
        );
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->form_validation->set_rules($this->TryoutSessionModel->rules());

        if ($this->form_validation->run() === true) {
            if (is_null($id)) {
                echo json_encode($this->TryoutSessionModel->insert());
            } else {
                echo json_encode($this->TryoutSessionModel->update($id));
            }
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->TryoutSessionModel->delete($id));
    }
}
