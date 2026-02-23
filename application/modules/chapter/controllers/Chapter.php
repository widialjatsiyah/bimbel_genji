<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Chapter extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'AppModel',
            'ChapterModel'
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        // Ambil data subject untuk combobox
        $subjects = $this->ChapterModel->getSubjects();
        $list_subject = $this->init_list($subjects, 'id', 'name');

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('chapter'),
            'card_title' => 'Bab',
            'list_subject' => $list_subject,
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();
        // Karena kita menggunakan join di model getAll, kita perlu mengkonfigurasi dtAjax dengan select_column agar join terbaca.
        // Alternatif: gunakan query builder di AppModel dengan konfigurasi table_join.
        // Di sini kita akan set config untuk AppModel->getData_dtAjax dengan join.
        $dtAjax_config = array(
            'select_column' => ['chapters.id', 'chapters.subject_id', 'chapters.name', 'chapters.order_num', 'subjects.name as subject_name'],
            'table_name' => 'chapters',
            'table_join' => array(
                array(
                    'table_name' => 'subjects',
                    'expression' => 'subjects.id = chapters.subject_id',
                    'type' => 'left'
                )
            ),
            'order_column' => 4, // indeks kolom di DataTables (0: id, 1: subject_name, 2: name, 3: order_num, 4: actions)
            'order_column_dir' => 'asc',
        );
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->form_validation->set_rules($this->ChapterModel->rules());

        if ($this->form_validation->run() === true) {
            if (is_null($id)) {
                echo json_encode($this->ChapterModel->insert());
            } else {
                echo json_encode($this->ChapterModel->update($id));
            }
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->ChapterModel->delete($id));
    }
}
