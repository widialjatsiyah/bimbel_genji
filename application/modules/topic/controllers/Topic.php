<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Topic extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'AppModel',
            'TopicModel',
            'ChapterModel',
            'SubjectModel'
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        // Ambil data chapter untuk combobox (dengan nama mata pelajaran juga)
        $chapters = $this->ChapterModel->getAll([], 'name', 'asc');
        $list_chapter = $this->init_list($chapters, 'id', 'name'); // sementara hanya nama bab, nanti di form kita akan pakai select2 dengan format yang lebih baik (bab + subject)

        // Untuk combobox yang lebih informatif, kita bisa buat array custom di view nanti.
        // Tapi untuk sementara kita gunakan list chapter biasa.

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('topic'),
            'card_title' => 'Topik / Sub-bab',
            'list_chapter' => $this->init_list($chapters, 'id', 'name'),
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();

        // Konfigurasi DataTables dengan join ke chapters dan subjects
        $dtAjax_config = array(
            'select_column' => [
                'topics.id',
                'topics.name',
                'topics.order_num',
                'chapters.name as chapter_name',
                'subjects.name as subject_name',
                'topics.chapter_id'
            ],
            'table_name' => 'topics',
            'table_join' => [
                [
                    'table_name' => 'chapters',
                    'expression' => 'chapters.id = topics.chapter_id',
                    'type' => 'inner'
                ],
                [
                    'table_name' => 'subjects',
                    'expression' => 'subjects.id = chapters.subject_id',
                    'type' => 'inner'
                ]
            ],
            'order_column' => 4, // kolom index ke-4 (subject_name) sebagai default order
            'order_column_dir' => 'asc',
        );
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->form_validation->set_rules($this->TopicModel->rules());

        if ($this->form_validation->run() === true) {
            if (is_null($id)) {
                echo json_encode($this->TopicModel->insert());
            } else {
                echo json_encode($this->TopicModel->update($id));
            }
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->TopicModel->delete($id));
    }

    // Optional: Ajax untuk mendapatkan chapter berdasarkan subject (untuk chained dropdown)
    public function ajax_get_chapters_by_subject()
    {
        $this->handle_ajax_request();
        $subject_id = $this->input->get('subject_id');
        $chapters = $this->ChapterModel->getAll(['subject_id' => $subject_id], 'name', 'asc');
        echo json_encode($chapters);
    }
}
