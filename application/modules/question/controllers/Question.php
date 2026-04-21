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

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('question'),
            'card_title' => 'Bank Soal',
			'list_subject' => $this->init_list($this->SubjectModel->getAll([], 'name', 'asc'), 'id', 'name'),
            // 'list_subject' => $this->list_subject,
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
            $question = $this->QuestionModel->getQuestionWithDetails($id);
            if ($question) {
                $data['question_data'] = $question;
            }
        }
        
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('form_page', $data, TRUE);
        $this->template->render();
    }

    /**
     * Display the form for editing a question
     * @param null $id The ID of the question to edit
     */
    public function form_edit($id = null)
    {
        if (!$id) {
            redirect('question');
        }

        $subjects = $this->SubjectModel->getAll([], 'name', 'asc');
        $question = $this->QuestionModel->getQuestionWithDetails($id);

        if (!$question) {
            redirect('question');
        }

        $selected_subject_id = $question->subject_id;
        $selected_chapter_id = $question->chapter_id;
        $selected_topic_id = $question->topic_id;

        $list_subject = $this->init_list($subjects, 'id', 'name', $selected_subject_id);

        $chapters = [];
        $topics = [];
        if ($selected_subject_id) {
            $chapters = $this->ChapterModel->getAll(['subject_id' => $selected_subject_id], 'name', 'asc');
        }
        if ($selected_chapter_id) {
            $topics = $this->TopicModel->getAll(['chapter_id' => $selected_chapter_id], 'name', 'asc');
        }

        $list_chapter = $this->init_list($chapters, 'id', 'name', $selected_chapter_id);
        $list_topic = $this->init_list($topics, 'id', 'name', $selected_topic_id);

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('question/views/main_form_edit.js.php', true,
			array('question_data' => $question) // passing question data to JS for pre-filling form
			),
            'card_title' => 'Ubah Soal',
            'list_subject' => $list_subject,
            'list_chapter' => $list_chapter,
            'list_topic' => $list_topic,
            'question_data' => $question
        ];

        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('form_page_edit', $data, TRUE);
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
                'questions.topic_id',
				'questions.expected_keywords',
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
            $use_images = $this->input->post('question_image');
            
            if ($use_images) {
                $this->form_validation->set_rules($this->QuestionModel->rulesWithImage());
            } else {
                $this->form_validation->set_rules($this->QuestionModel->rules());
            }
        }

        if ($this->form_validation->run() === true) {
            // Handle image uploads
            $this->load->library('CpUpload');
            
            // Handle question image upload - hanya upload jika ada file baru
            if (!empty($_FILES['question_image_file']['name'])) {
                $upload = $this->QuestionModel->handleImageUpload('question_image_file', 'question_image', 'questions');
                if (!$upload['status']) {
                    echo json_encode(['status' => false, 'data' => $upload['data']]);
                    return;
                }
                // Update post data so DB stores the latest uploaded path
                $_POST['question_image'] = $upload['data']->base_path;
            }
            
            // Handle option image uploads for all options - hanya upload jika ada file baru
            $option_fields = [
                'option_a_image_file' => 'option_a_image',
                'option_b_image_file' => 'option_b_image', 
                'option_c_image_file' => 'option_c_image',
                'option_d_image_file' => 'option_d_image',
                'option_e_image_file' => 'option_e_image'
            ];
            
            foreach ($option_fields as $field => $key) {
                // Gunakan method handleImageUpload yang konsisten untuk semua upload
                $upload = $this->QuestionModel->handleImageUpload($field, $key, 'questions');
                
                if (!$upload['status']) {
                    // Hanya kembalikan error jika upload gagal karena masalah teknis, bukan karena tidak ada file
                    if ($upload['data'] !== 'No file uploaded.') {
                        echo json_encode(['status' => false, 'data' => $upload['data']]);
                        return;
                    }
                    // Jika tidak ada file baru diupload, gunakan path yang ada di hidden input atau kosongkan
                    $existingPath = $this->input->post($key);
                    $_POST[$key] = $existingPath ? $existingPath : null;
                } else {
                    // Jika upload berhasil, gunakan path hasil upload
                    $_POST[$key] = $upload['data']->base_path;
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
    
    /**
     * API endpoint to get question data by ID
     * Used in the form for editing
     */
    public function api_get_question($id)
    {
        $this->handle_ajax_request();
        $question = $this->QuestionModel->getQuestionWithDetails($id);
        
        if ($question) {
            echo json_encode($question);
        } else {
            show_404();
        }
    }
    
    /**
     * API endpoint to get all subjects
     */
    public function ajax_get_subjects()
    {
        $this->handle_ajax_request();
        $this->load->model('SubjectModel');
        
        $subjects = $this->SubjectModel->getAll([], 'name', 'asc');
        echo json_encode($subjects);
    }
    
    /**
     * Import questions from Excel file
     */
    public function import_from_excel()
    {
        $this->handle_ajax_request();
        
        // Load required models
        $this->load->model(['SubjectModel', 'ChapterModel', 'TopicModel']);
        $this->load->library('upload');
        
        // Get subject ID
        $subject_id = $this->input->post('subject_id');
        
        if (!$subject_id) {
            echo json_encode(['status' => false, 'data' => 'Subject ID harus disediakan']);
            return;
        }
        
        // Check if subject exists
        $subject = $this->SubjectModel->getDetail(['id' => $subject_id]);
        if (!$subject) {
            echo json_encode(['status' => false, 'data' => 'Mata pelajaran tidak ditemukan']);
            return;
        }
        
        // Setup upload configuration
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size'] = '2048'; // 2MB
        
        // Create directory if not exists
        if (!is_dir('./uploads/temp/')) {
            mkdir('./uploads/temp/', 0755, true);
        }
        
        $this->upload->initialize($config);
        
        if (!$this->upload->do_upload('import_file')) {
            echo json_encode(['status' => false, 'data' => $this->upload->display_errors()]);
            return;
        }
        
        $upload_data = $this->upload->data();
        
        try {
            // Process Excel file
            $this->load->library('CpUpload');
            
            // Load spreadsheet reader
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load('./uploads/temp/' . $upload_data['file_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $imported_count = 0;
            $failed_count = 0;
            $errors = [];
            
            // Assuming row 1 is header, start from row 2
            $highestRow = $worksheet->getHighestRow();
            
            // Loop through rows starting from row 2
            for ($row = 2; $row <= $highestRow; $row++) {
                // Prepare question data
                $question_data = [
                    'subject_id' => $subject_id,
                    'question_text' => $worksheet->getCell('A' . $row)->getValue(),
                    'option_a' => $worksheet->getCell('B' . $row)->getValue(),
                    'option_b' => $worksheet->getCell('C' . $row)->getValue(),
                    'option_c' => $worksheet->getCell('D' . $row)->getValue(),
                    'option_d' => $worksheet->getCell('E' . $row)->getValue(),
                    'option_e' => $worksheet->getCell('F' . $row)->getValue(),
                    'correct_option' => $worksheet->getCell('G' . $row)->getValue(),
                    'explanation' => $worksheet->getCell('H' . $row)->getValue(),
                    'difficulty' => $worksheet->getCell('I' . $row)->getValue() ?: 'medium',
                    'question_type' => strtolower($worksheet->getCell('J' . $row)->getValue()) ?: 'multiple_choice',
                    'option_type' => $worksheet->getCell('K' . $row)->getValue() ?: 'text',
                    'curriculum' => $worksheet->getCell('L' . $row)->getValue() ?: 'K13' // Column L for curriculum
                ];
                
                // Validate required fields
                if (empty($question_data['question_text'])) {
                    $failed_count++;
                    $errors[] = "Baris " . $row . ": Soal tidak boleh kosong";
                    continue;
                }
                
                // Set default difficulty if invalid
                if (!in_array($question_data['difficulty'], ['easy', 'medium', 'hard'])) {
                    $question_data['difficulty'] = 'medium';
                }
                
                // Set default question type if invalid
                if (!in_array($question_data['question_type'], ['multiple_choice', 'essay'])) {
                    $question_data['question_type'] = 'multiple_choice';
                }
                
                // Set default option type if invalid
                if (!in_array($question_data['option_type'], ['text', 'image'])) {
                    $question_data['option_type'] = 'text';
                }
                
                // For essay questions, we don't need options A-E or correct_option
                if ($question_data['question_type'] === 'essay') {
                    $question_data['option_a'] = '';
                    $question_data['option_b'] = '';
                    $question_data['option_c'] = '';
                    $question_data['option_d'] = '';
                    $question_data['option_e'] = '';
                    $question_data['correct_option'] = null;
                } else { // Multiple choice
                    // Ensure required options are present for multiple choice
                    $options = [$question_data['option_a'], $question_data['option_b'], 
                                $question_data['option_c'], $question_data['option_d'], $question_data['option_e']];
                    
                    // At least two options must be filled
                    $filledOptions = array_filter($options, function($value) {
                        return !empty(trim($value));
                    });
                    
                    if (count($filledOptions) < 2) {
                        $failed_count++;
                        $errors[] = "Baris " . $row . ": Soal pilihan ganda harus memiliki setidaknya 2 opsi jawaban";
                        continue;
                    }
                    
                    // Validate correct option
                    $validCorrectOptions = ['A', 'B', 'C', 'D', 'E', 'a', 'b', 'c', 'd', 'e'];
                    if (!in_array($question_data['correct_option'], $validCorrectOptions)) {
                        $failed_count++;
                        $errors[] = "Baris " . $row . ": Jawaban benar harus berupa A, B, C, D, atau E";
                        continue;
                    }
                    
                    // Normalize correct option to uppercase
                    $question_data['correct_option'] = strtoupper($question_data['correct_option']);
                }
                
                // Save to database using QuestionModel
                $this->load->model('QuestionModel');
                
                // Set POST data temporarily to match what the model expects
                $_POST = $question_data;
                
                $result = $this->QuestionModel->insert();
                
                if ($result['status']) {
                    $imported_count++;
                } else {
                    $failed_count++;
                    $errors[] = "Baris " . $row . ": " . $result['data'];
                }
            }
            
            // Delete uploaded file after processing
            unlink('./uploads/temp/' . $upload_data['file_name']);
            
            $message = "Berhasil mengimpor {$imported_count} soal";
            if ($failed_count > 0) {
                $message .= ", gagal mengimpor {$failed_count} soal.";
                if (!empty($errors)) {
                    $message .= " Error: " . implode(', ', array_slice($errors, 0, 5)); // Limit error display
                    if (count($errors) > 5) {
                        $message .= " dan " . (count($errors) - 5) . " error lainnya";
                    }
                }
            }
            
            echo json_encode([
                'status' => true, 
                'data' => $message
            ]);
            
        } catch (Exception $e) {
            // Delete uploaded file if error occurs
            if (file_exists('./uploads/temp/' . $upload_data['file_name'])) {
                unlink('./uploads/temp/' . $upload_data['file_name']);
            }
            
            echo json_encode(['status' => false, 'data' => 'Error saat memproses file: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Generate Excel template for question import
     */
    public function download_template()
    {
        // $this->handle_ajax_request();
        
        // Create new spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $sheet->setTitle('Template Import Soal');
        
        // Header
        $headers = [
            'A1' => 'Soal',
            'B1' => 'Opsi A',
            'C1' => 'Opsi B',
            'D1' => 'Opsi C',
            'E1' => 'Opsi D',
            'F1' => 'Opsi E',
            'G1' => 'Jawaban Benar',
            'H1' => 'Penjelasan',
            'I1' => 'Tingkat Kesulitan',
            'J1' => 'Jenis Soal',
            'K1' => 'Tipe Opsi',
            'L1' => 'Kurikulum'
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Styling header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E6E6FA']
            ]
        ];
        
        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);
        
        // Add sample data
        $sampleData = [
            'A2' => 'Contoh soal pilihan ganda?',
            'B2' => 'Opsi jawaban A',
            'C2' => 'Opsi jawaban B',
            'D2' => 'Opsi jawaban C',
            'E2' => 'Opsi jawaban D',
            'F2' => 'Opsi jawaban E',
            'G2' => 'A',
            'H2' => 'Penjelasan jawaban',
            'I2' => 'medium',
            'J2' => 'multiple_choice',
            'K2' => 'text',
            'L2' => 'K13'
        ];
        
        foreach ($sampleData as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Add info notes
        // $sheet->setCellValue('A4', 'Catatan:');
        // $sheet->setCellValue('A5', '1. Kolom A (Soal): Wajib diisi');
        // $sheet->setCellValue('A6', '2. Kolom G (Jawaban Benar): Gunakan huruf A, B, C, D, atau E (kosongkan untuk soal essay)');
        // $sheet->setCellValue('A7', '3. Kolom I (Tingkat Kesulitan): Gunakan easy, medium, atau hard');
        // $sheet->setCellValue('A8', '4. Kolom J (Jenis Soal): Gunakan multiple_choice atau essay');
        // $sheet->setCellValue('A9', '5. Kolom K (Tipe Opsi): Gunakan text atau image');
        // $sheet->setCellValue('A10', '6. Kolom L (Kurikulum): Contoh: K13, KTSP, dll');
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        
        // Create writer and output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_import_soal.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
