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
            'TryoutModel',
            'TryoutQuestionModel',
            'QuestionModel',
            'SubjectModel'
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        // Ambil data tryout untuk combobox
        $tryouts = $this->TryoutModel->getAll([], 'title', 'asc');
        $list_tryout = $this->init_list($tryouts, 'id', 'title');

        $subjects = $this->SubjectModel->getAll([], 'name', 'asc');

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('tryout_session'),
            'card_title' => 'Sesi Try Out',
            'list_tryout' => $list_tryout,
            'subjects' => $subjects
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
                'tryout_sessions.tryout_id',
                'tryout_sessions.is_random',
                'tryout_sessions.scoring_method',
				'tryout_sessions.enable_time_per_question',
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
    
    public function ajax_get_questions_by_session($session_id = null)
    {
        $this->handle_ajax_request();
        
        // Ambil session_id dari URI segment atau dari GET parameter
        if ($session_id === null) {
            $session_id = $this->uri->segment(3); // Ambil dari URI segment ke-3
        }
        
        // Jika tetap null, coba dari GET parameter
        if ($session_id === null) {
            $session_id = $this->input->get('session_id');
        }
        
        if (!$session_id) {
            echo json_encode([]);
            return;
        }
        
        $questions = $this->db
            ->select('tq.question_order, tq.question_id, q.question_text, tq.points, tq.time_limit')
            ->from('tryout_questions tq')
            ->join('questions q', 'q.id = tq.question_id')
            ->where('tq.tryout_session_id', $session_id)
            ->order_by('tq.question_order', 'ASC')
            ->get()
            ->result();
        
        // Format data untuk DataTables
        echo json_encode($questions);
    }

    public function ajax_create_question_in_session()
    {
        $this->handle_ajax_request();

        $tryout_session_id = $this->input->post('tryout_session_id');
        if (!$tryout_session_id) {
            echo json_encode(array('status' => false, 'data' => 'Tryout session tidak valid.'));
            return;
        }

        $session = $this->TryoutSessionModel->getDetail(array('id' => $tryout_session_id));
        if (!$session) {
            echo json_encode(array('status' => false, 'data' => 'Tryout session tidak ditemukan.'));
            return;
        }

        $question_type = $this->input->post('question_type') ?: 'multiple_choice';
        $option_type = $this->input->post('option_type') ?: 'text';

        if ($question_type === 'essay') {
            $this->form_validation->set_rules($this->QuestionModel->rulesEssayOnly());
        } else if ($option_type === 'image') {
            $this->form_validation->set_rules($this->QuestionModel->rulesWithImage());
        } else {
            $this->form_validation->set_rules($this->QuestionModel->rules());
        }

        if ($this->form_validation->run() === false) {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
            return;
        }

        $this->load->library('CpUpload');

        $imageFields = array(
            'question_image_file' => 'question_image',
            'option_a_image_file' => 'option_a_image',
            'option_b_image_file' => 'option_b_image',
            'option_c_image_file' => 'option_c_image',
            'option_d_image_file' => 'option_d_image',
            'option_e_image_file' => 'option_e_image'
        );

        foreach ($imageFields as $fieldName => $key) {
            $upload = $this->QuestionModel->handleImageUpload($fieldName, $key, 'questions');
            if (!$upload['status']) {
                if ($upload['data'] !== 'No file uploaded.') {
                    echo json_encode(array('status' => false, 'data' => $upload['data']));
                    return;
                }
                $_POST[$key] = $this->input->post($key) ?: null;
            } else {
                $_POST[$key] = $upload['data']->base_path;
            }
        }

        $this->db->trans_begin();

        $questionResult = $this->QuestionModel->insert();
        if (!$questionResult['status']) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'data' => $questionResult['data']));
            return;
        }

        $question_id = isset($questionResult['id']) ? $questionResult['id'] : $this->db->insert_id();

        if ($this->TryoutQuestionModel->questionExistsInSession($tryout_session_id, $question_id)) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'data' => 'Soal sudah terdapat pada Tryout Session.'));
            return;
        }

        $_POST['tryout_session_id'] = $tryout_session_id;
        $_POST['question_id'] = $question_id;
        $_POST['question_order'] = $this->TryoutQuestionModel->getMaxQuestionOrder($tryout_session_id) + 1;
        $_POST['points'] = 1.00;

        $relationResult = $this->TryoutQuestionModel->insert();
        if (!$relationResult['status']) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'data' => $relationResult['data']));
            return;
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'data' => 'Gagal menyimpan soal ke Tryout Session.'));
            return;
        }

        $this->db->trans_commit();
        echo json_encode(array('status' => true, 'data' => 'Soal berhasil dibuat dan ditambahkan ke Tryout Session.'));
    }
    
    public function ajax_get_questions_not_in_session()
    {
        $this->handle_ajax_request();
        
        $session_id = $this->input->get('session_id');
        $search_term = $this->input->get('q');
        
        // Validasi bahwa session_id ada
        if (!$session_id) {
            echo json_encode([]);
            return;
        }
        
        // Query untuk mendapatkan soal-soal yang belum ada di sesi
        $this->db->select('q.id, q.question_text');
        $this->db->from('questions q');
        
        // Subquery untuk mendapatkan ID soal yang sudah ada di sesi
        $subquery = "(SELECT question_id FROM tryout_questions WHERE tryout_session_id = {$session_id})";
        $this->db->where("q.id NOT IN {$subquery}", NULL, FALSE);
        
        if (!empty($search_term)) {
            $this->db->like('q.question_text', $search_term);
        }
        
        // Tambahkan limit untuk menghindari data yang terlalu banyak
        $this->db->limit(30);
        
        $questions = $this->db->get()->result();
        
        // Format data eksplisit untuk Select2
        $results = array();
        foreach ($questions as $q) {
            $results[] = array(
                'id' => $q->id,
                'text' => strlen($q->question_text) > 500 ? 
                         substr($q->question_text, 0, 500) . '...' : 
                         $q->question_text
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode($results);
    }
    
    public function ajax_add_questions_to_session()
    {
        $this->handle_ajax_request();
        
        $session_id = $this->input->post('session_id');
        $question_ids = $this->input->post('question_ids');
        $ordering_method = $this->input->post('ordering_method');
        $start_order = intval($this->input->post('start_order')) ?: 1;
        
        if (!$session_id || !$question_ids || !is_array($question_ids)) {
            echo json_encode(array('status' => false, 'data' => 'Parameter tidak lengkap.'));
            return;
        }
        
        // Get session info to check scoring method
        $session_info = $this->TryoutSessionModel->getDetail(['id' => $session_id]);
        
        // Get max order in this session if using auto method
        if ($ordering_method === 'auto') {
            $max_order = $this->TryoutQuestionModel->getMaxQuestionOrder($session_id);
            $start_order = $max_order + 1;
        }
        
        $success_count = 0;
        $error_messages = [];
        
        // Begin transaction
        $this->db->trans_begin();
        
        foreach ($question_ids as $index => $question_id) {
            // Check if the question is already assigned to this session
            $exists = $this->TryoutQuestionModel->questionExistsInSession($session_id, $question_id);
            
            if (!$exists) {
                $data = array(
                    'tryout_session_id' => $session_id,
                    'question_id' => $question_id,
                    'question_order' => $start_order + $index,
                    'points' => $session_info->scoring_method === 'points_per_question' ? 1.00 : 1.00 // Default to 1.00 for both methods
                );
                
                if ($this->db->insert('tryout_questions', $data)) {
                    $success_count++;
                } else {
                    $error_messages[] = "Gagal menambahkan soal ID: {$question_id}";
                }
            } else {
                $error_messages[] = "Soal ID {$question_id} sudah ada di sesi ini.";
            }
        }
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(array('status' => false, 'data' => 'Transaksi gagal: ' . implode(', ', $error_messages)));
        } else {
            $this->db->trans_commit();
            
            $message = "Berhasil menambahkan {$success_count} soal ke sesi.";
            if (!empty($error_messages)) {
                $message .= " Gagal menambahkan: " . implode(', ', $error_messages);
            }
            
            echo json_encode(array('status' => true, 'data' => $message));
        }
    }
    
    public function ajax_remove_question_from_session()
    {
        $this->handle_ajax_request();
        
        $session_id = $this->input->post('session_id');
        $question_id = $this->input->post('question_id');
        
        if (!$session_id || !$question_id) {
            echo json_encode(array('status' => false, 'data' => 'Parameter tidak lengkap.'));
            return;
        }
        
        $this->db->where('tryout_session_id', $session_id);
        $this->db->where('question_id', $question_id);
        $result = $this->db->delete('tryout_questions');
        
        if ($result) {
            echo json_encode(array('status' => true, 'data' => 'Soal berhasil dihapus dari sesi.'));
        } else {
            echo json_encode(array('status' => false, 'data' => 'Gagal menghapus soal dari sesi.'));
        }
    }
    
    public function ajax_update_question_points()
    {
        $this->handle_ajax_request();
        
        $session_id = $this->input->post('session_id');
        $question_id = $this->input->post('question_id');
        $points = $this->input->post('points');
        
        if (!$session_id || !$question_id || !isset($points)) {
            echo json_encode(array('status' => false, 'data' => 'Parameter tidak lengkap.'));
            return;
        }
        
        $result = $this->TryoutQuestionModel->updatePoints($session_id, $question_id, $points);
        
        if ($result) {
            echo json_encode(array('status' => true, 'data' => 'Poin soal berhasil diperbarui.'));
        } else {
            echo json_encode(array('status' => false, 'data' => 'Gagal memperbarui poin soal.'));
        }
    }
    
    public function ajax_update_question_time_limit()
    {
        $this->handle_ajax_request();
        
        $session_id = $this->input->post('session_id');
        $question_id = $this->input->post('question_id');
        $time_limit = $this->input->post('time_limit');
        
        if (!$session_id || !$question_id || !isset($time_limit)) {
            echo json_encode(array('status' => false, 'data' => 'Parameter tidak lengkap.'));
            return;
        }
        
        $result = $this->TryoutQuestionModel->updateTimeLimit($session_id, $question_id, $time_limit);
        
        if ($result) {
            echo json_encode(array('status' => true, 'data' => 'Batas waktu soal berhasil diperbarui.'));
        } else {
            echo json_encode(array('status' => false, 'data' => 'Gagal memperbarui batas waktu soal.'));
        }
    }
}
