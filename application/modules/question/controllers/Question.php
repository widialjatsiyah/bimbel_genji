<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

/**
 * Controller for managing questions in the application
 * Handles CRUD operations, AJAX requests, and file uploads for questions
 */
class Question extends AppBackend
{
    /**
     * Constructor to initialize required models and libraries
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model([
            'AppModel',
            'QuestionModel',
            'SubjectModel',
            'ChapterModel',
            'TopicModel'
        ]);
        $this->load->library('form_validation');
    }

    /**
     * Default method to display the questions management interface
     */
    public function index()
    {
        // Get data for dropdowns
        $subjects = $this->SubjectModel->getAll([], 'name', 'asc');
        $list_subject = $this->init_list($subjects, 'id', 'name');

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('question'),
            'card_title' => 'Bank Soal',
            'list_subject' => $list_subject,
        ];
        
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    /**
     * Display the form for creating or editing a question
     * @param null $id The ID of the question to edit
     */
    public function form($id = null)
    {
        $subjects = $this->SubjectModel->getAll([], 'name', 'asc');
        $list_subject = $this->init_list($subjects, 'id', 'name');
        
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('question/views/main_form.js.php', true),
            'card_title' => ($id) ? 'Ubah Soal' : 'Tambah Soal',
            'list_subject' => $list_subject,
            'question_data' => null
        ];
        
        // If editing, get question data
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

    /**
     * Handle AJAX request to get all questions with DataTables integration
     */
    public function ajax_get_all()
    {
        $this->handle_ajax_request();

        // DataTables configuration with joins to related tables
        $dtAjax_config = [
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
            'order_column' => 9, // created_at column
            'order_column_dir' => 'desc',
        ];
        
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    /**
     * Handle AJAX request to save a question (create or update)
     * @param null $id The ID of the question to update
     */
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
                          $this->input->post('option_a') || 
                          $this->input->post('option_b') || 
                          $this->input->post('option_c') || 
                          $this->input->post('option_d') || 
                          $this->input->post('option_e');
            
            if ($use_images) {
                $this->form_validation->set_rules($this->QuestionModel->rulesWithImage());
            } else {
                $this->form_validation->set_rules($this->QuestionModel->rules());
            }
        }

        if ($this->form_validation->run() === true) {
            // Handle image uploads
            $this->load->library('CpUpload');
            
            // Handle question image upload
            if (!empty($_FILES['question_image_file']['name'])) {
                $upload = $this->QuestionModel->handleImageUpload('question_image_file', 'question_image', 'questions');
                if (!$upload['status']) {
                    echo json_encode(['status' => false, 'data' => $upload['data']]);
                    return;
                }
            }
            
            // Handle option image uploads if option type is image
            if ($this->input->post('option_type') === 'image') {
                $option_fields = ['option_a_file', 'option_b_file', 'option_c_file', 'option_d_file', 'option_e_file'];
                foreach ($option_fields as $field) {
                    if (!empty($_FILES[$field]['name'])) {
                        $upload = $this->QuestionModel->handleImageUpload($field, str_replace('_file', '', $field), 'questions');
                        if ($upload['status']) {
                            // Update the post data to use the uploaded image path
                            $_POST[str_replace('_file', '', $field)] = $upload['data']->base_path;
                        } else {
                            echo json_encode(['status' => false, 'data' => $upload['data']]);
                            return;
                        }
                    }
                }
            }

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

    /**
     * Handle AJAX request to delete a question
     * @param $id The ID of the question to delete
     */
    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->QuestionModel->delete($id));
    }

    /**
     * AJAX method to get chapters based on selected subject (for chained dropdown)
     */
    public function ajax_get_chapters()
    {
        $this->handle_ajax_request();
        $subject_id = $this->input->get('subject_id');
        $chapters = $this->ChapterModel->getAll(['subject_id' => $subject_id], 'name', 'asc');
        echo json_encode($chapters);
    }

    /**
     * AJAX method to get topics based on selected chapter (for chained dropdown)
     */
    public function ajax_get_topics()
    {
        $this->handle_ajax_request();
        $chapter_id = $this->input->get('chapter_id');
        $topics = $this->TopicModel->getAll(['chapter_id' => $chapter_id], 'name', 'asc');
        echo json_encode($topics);
    }
    
    /**
     * Method for image upload functionality
     */
    public function upload_image()
    {
        $this->handle_ajax_request();
        
        $this->load->library('CpUpload');
        
        $upload = $this->QuestionModel->handleImageUpload('image', 'temp', 'questions');
        
        if ($upload['status']) {
            echo json_encode(['status' => true, 'path' => $upload['data']->base_path]);
        } else {
            echo json_encode(['status' => false, 'error' => $upload['data']]);
        }
    }
}
