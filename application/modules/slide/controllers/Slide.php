<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Slide extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya admin yang boleh akses
       
        $this->load->model(['AppModel', 'SlideModel']);
    }

    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('slide'),
            'card_title' => 'Kelola Slide Banner'
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();
        $dtAjax_config = [
            'table_name' => 'slides',
            'order_column' => 1,
            'order_column_dir' => 'asc'
        ];
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->form_validation->set_rules($this->SlideModel->rules());

        if ($this->form_validation->run() == true) {
            if (is_null($id)) {
                $res = $this->SlideModel->insert();
            } else {
                $res = $this->SlideModel->update($id);
            }
            echo json_encode($res);
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(['status' => false, 'data' => $errors]);
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->SlideModel->delete($id));
    }
}
