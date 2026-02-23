<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Daily_checklist extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('DailyChecklistModel');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];
        $today = $this->DailyChecklistModel->getToday($user_id);

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('daily_checklist'),
            'card_title' => 'Checklist Harian',
            'today_data' => $today
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_save()
    {
        $this->handle_ajax_request();
        $this->form_validation->set_rules($this->DailyChecklistModel->rules());

        if ($this->form_validation->run() === true) {
            echo json_encode($this->DailyChecklistModel->save());
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
        }
    }

    public function ajax_get_history()
    {
        $this->handle_ajax_request();
        $user_id = $this->session->userdata('user')['id'];
        $start = $this->input->get('start') ?: date('Y-m-01');
        $end = $this->input->get('end') ?: date('Y-m-t');
        $data = $this->DailyChecklistModel->getRange($user_id, $start, $end);
        echo json_encode($data);
    }
}
