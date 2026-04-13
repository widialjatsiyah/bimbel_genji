<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

/**
 * Question Group Management Module
 * Provides functionality to manage question groups
 */
class Question_group extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model([
            'QuestionModel',
            'AppModel'
        ]);
        $this->load->library('form_validation');
    }

    /**
     * Display the question group management interface
     */
    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('question_group'),
            'card_title' => 'Manajemen Grup Soal',
        ];

        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    /**
     * Display form for creating or editing a question group
     */
    public function form($id = null)
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('question_group/form', true),
            'card_title' => ($id) ? 'Ubah Grup Soal' : 'Tambah Grup Soal',
            'group_data' => null
        ];

        // If editing, get group data
        if ($id) {
            // We'll treat the ID as the group ID and fetch related questions
            $questions = $this->QuestionModel->getByGroupId($id);
            $data['group_data'] = [
                'group_id' => $id,
                'questions' => $questions,
                'question_count' => count($questions)
            ];
        }

        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('form_page', $data, TRUE);
        $this->template->render();
    }

    /**
     * Get all question groups for DataTables
     */
    public function ajax_get_all()
    {
        $this->handle_ajax_request();

        // Get all unique group IDs from questions table
        $this->db->select('DISTINCT group_id as id, COUNT(*) as question_count');
        $this->db->from('questions');
        $this->db->where('group_id IS NOT NULL');
        $this->db->group_by('group_id');
        $groups = $this->db->get()->result();

        $data = [];
        foreach ($groups as $group) {
            $main_question = $this->QuestionModel->getMainQuestionByGroupId($group->id);
            $data[] = [
                'id' => $group->id,
                'question_count' => $group->question_count,
                'main_question' => $main_question ? substr(strip_tags($main_question->question_text), 0, 100) . '...' : '-',
                'created_at' => $main_question ? $main_question->created_at : '-'
            ];
        }

        $response = [
            'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data),
            'data' => $data
        ];

        echo json_encode($response);
    }

    /**
     * Create a new question group
     */
    public function ajax_create_group()
    {
        $this->handle_ajax_request();

        $group_id = $this->input->post('group_id');
        $question_ids = $this->input->post('question_ids');
        $main_question_id = $this->input->post('main_question_id');

        if (empty($question_ids)) {
            echo json_encode(['status' => false, 'data' => 'Harus memilih setidaknya satu soal']);
            return;
        }

        // If no group_id is provided, generate a new one
        if (empty($group_id)) {
            $group_id = $this->QuestionModel->createNewGroupId();
        }

        $this->db->trans_start();

        // First, remove all questions from any existing group
        $this->db->where_in('id', $question_ids);
        $this->db->update('questions', [
            'group_id' => null,
            'group_order' => 1,
            'is_group_main' => 0
        ]);

        // Then, assign them to the new group with proper ordering
        $order = 1;
        foreach ($question_ids as $qid) {
            $is_main = ($main_question_id && $qid == $main_question_id) ? 1 : 0;
            
            $data = [
                'group_id' => $group_id,
                'group_order' => $order++,
                'is_group_main' => $is_main
            ];
            
            $this->db->where('id', $qid);
            $this->db->update('questions', $data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => false, 'data' => 'Gagal membuat grup soal']);
        } else {
            echo json_encode(['status' => true, 'data' => 'Grup soal berhasil dibuat']);
        }
    }

    /**
     * Remove questions from a group
     */
    public function ajax_remove_from_group()
    {
        $this->handle_ajax_request();

        $group_id = $this->input->post('group_id');
        $question_ids = $this->input->post('question_ids');

        // If no specific question IDs provided, remove all questions from the group
        if (empty($group_id)) {
            echo json_encode(['status' => false, 'data' => 'Group ID harus disediakan']);
            return;
        }

        $this->db->trans_start();

        if (empty($question_ids)) {
            // Remove all questions from the specified group
            $this->db->where('group_id', $group_id);
            $this->db->update('questions', [
                'group_id' => null,
                'group_order' => 1,
                'is_group_main' => 0
            ]);
        } else {
            // Remove only specified questions from the group
            $this->db->where_in('id', $question_ids);
            $this->db->where('group_id', $group_id); // Additional safety check
            $this->db->update('questions', [
                'group_id' => null,
                'group_order' => 1,
                'is_group_main' => 0
            ]);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => false, 'data' => 'Gagal menghapus dari grup']);
        } else {
            $message = empty($question_ids) ? 
                'Semua soal berhasil dihapus dari grup' : 
                'Soal berhasil dihapus dari grup';
            echo json_encode(['status' => true, 'data' => $message]);
        }
    }
    
    /**
     * Get questions that are not part of any group
     */
    public function ajax_get_questions_not_in_group()
    {
        $this->handle_ajax_request();
        
        $questions = $this->QuestionModel->getNonGroupQuestions();
        echo json_encode($questions);
    }
}