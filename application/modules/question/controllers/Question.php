<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Question extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'AppModel',
            'QuestionModel',
            'SubjectModel',
            'ChapterModel',
            'TopicModel'
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        // Ambil data untuk dropdown
        $subjects = $this->SubjectModel->getAll([], 'name', 'asc');
        $list_subject = $this->init_list($subjects, 'id', 'name');

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('question'),
            'card_title' => 'Bank Soal',
            'list_subject' => $list_subject,
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function form($id = null)
    {
        $subjects = $this->SubjectModel->getAll([], 'name', 'asc');
        $list_subject = $this->init_list($subjects, 'id', 'name');
        
        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('question/views/main_form.js.php',true),
            'card_title' => ($id) ? 'Ubah Soal' : 'Tambah Soal',
            'list_subject' => $list_subject,
            'question_data' => null
        );
        
        // Jika edit, ambil data question
        if ($id) {
            $question = $this->QuestionModel->getById($id);
            if ($question) {
                $data['question_data'] = $question;
            }
        }
        
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('form_page', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();

        // Konfigurasi DataTables dengan join ke subjects, chapters, topics
        $dtAjax_config = array(
            'select_column' => [
                'questions.id',
                'questions.question_text',
                'questions.question_type',
                'questions.difficulty',
                'questions.curriculum',
                'subjects.name as subject_name',
                'chapters.name as chapter_name',
                'topics.name as topic_name',
                'questions.correct_option',
                'questions.is_active',
                'questions.created_at',
                'questions.subject_id',
                'questions.chapter_id',
                'questions.topic_id'
            ],
            'table_name' => 'questions',
            'table_join' => [
                [
                    'table_name' => 'subjects',
                    'expression' => 'subjects.id = questions.subject_id',
                    'type' => 'left'
                ],
                [
                    'table_name' => 'chapters',
                    'expression' => 'chapters.id = questions.chapter_id',
                    'type' => 'left'
                ],
                [
                    'table_name' => 'topics',
                    'expression' => 'topics.id = questions.topic_id',
                    'type' => 'left'
                ]
            ],
            'order_column' => 9, // kolom created_at
            'order_column_dir' => 'desc',
        );
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        
        // Ambil jenis soal dari input
        $question_type = $this->input->post('question_type') ?: 'multiple_choice';
        
        // Atur aturan validasi berdasarkan jenis soal
        if ($question_type === 'essay') {
            $this->form_validation->set_rules($this->QuestionModel->rulesEssayOnly());
        } else {
            // Atur aturan validasi berbeda jika menggunakan gambar
            $use_images = $this->input->post('question_image') || 
                          $this->input->post('option_a_image') || 
                          $this->input->post('option_b_image') || 
                          $this->input->post('option_c_image') || 
                          $this->input->post('option_d_image') || 
                          $this->input->post('option_e_image');
            
            if ($use_images) {
                $this->form_validation->set_rules($this->QuestionModel->rulesWithImage());
            } else {
                $this->form_validation->set_rules($this->QuestionModel->rules());
            }
        }

        if ($this->form_validation->run() === true) {
            if (is_null($id)) {
                echo json_encode($this->QuestionModel->insert());
            } else {
                echo json_encode($this->QuestionModel->update($id));
            }
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->QuestionModel->delete($id));
    }

    // Ajax untuk mendapatkan chapters berdasarkan subject (chained dropdown)
    public function ajax_get_chapters()
    {
        $this->handle_ajax_request();
        $subject_id = $this->input->get('subject_id');
        $chapters = $this->ChapterModel->getAll(['subject_id' => $subject_id], 'name', 'asc');
        echo json_encode($chapters);
    }

    // Ajax untuk mendapatkan topics berdasarkan chapter (chained dropdown)
    public function ajax_get_topics()
    {
        $this->handle_ajax_request();
        $chapter_id = $this->input->get('chapter_id');
        $topics = $this->TopicModel->getAll(['chapter_id' => $chapter_id], 'name', 'asc');
        echo json_encode($topics);
    }
    
    // Fungsi untuk upload gambar
    public function upload_image()
    {
        $this->handle_ajax_request();
        
        $config['upload_path'] = './uploads/questions/';
        $config['allowed_types'] = 'jpg|png|jpeg|gif';
        $config['max_size'] = 2048; // 2MB
        
        // Buat folder jika belum ada
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }
        
        $this->load->library('upload', $config);
        
        if (!$this->upload->do_upload('image')) {
            echo json_encode(['status' => false, 'error' => $this->upload->display_errors()]);
        } else {
            $upload_data = $this->upload->data();
            $path = 'uploads/questions/' . $upload_data['file_name'];
            echo json_encode(['status' => true, 'path' => $path]);
        }
    }
}