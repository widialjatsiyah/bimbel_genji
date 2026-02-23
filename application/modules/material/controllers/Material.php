<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Material extends AppBackend
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'AppModel',
			'MaterialModel',
			'SubjectModel',
			'ChapterModel'
		));
		$this->load->library('form_validation');
	}

	public function index()
	{
		$subjects = $this->SubjectModel->getAll([], 'name', 'asc');
		$chapters = $this->ChapterModel->getAll([], 'name', 'asc');

		$data = array(
			'app' => $this->app(),
			'main_js' => $this->load_main_js('material'),
			'card_title' => 'Materi Belajar',
			'list_subject' => $this->init_list($subjects, 'id', 'name'),
			'list_chapter' => $this->init_list($chapters, 'id', 'name')
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
				'materials.id',
				'materials.title',
				'materials.type',
				'materials.url',
				'subjects.name as subject_name',
				'chapters.name as chapter_name',
				'materials.duration_seconds',
				'materials.is_active',
				'materials.description'
			],
			'table_name' => 'materials',
			'table_join' => [
				[
					'table_name' => 'subjects',
					'expression' => 'subjects.id = materials.subject_id',
					'type' => 'left'
				],
				[
					'table_name' => 'chapters',
					'expression' => 'chapters.id = materials.chapter_id',
					'type' => 'left'
				]
			],
			'order_column' => 1,
			'order_column_dir' => 'asc',
		);
		$response = $this->AppModel->getData_dtAjax($dtAjax_config);
		echo json_encode($response);
	}

	public function ajax_save($id = null)
	{
		$this->handle_ajax_request();
		$this->form_validation->set_rules($this->MaterialModel->rules());

		if ($this->form_validation->run() === true) {
			if (is_null($id)) {
				echo json_encode($this->MaterialModel->insert());
			} else {
				echo json_encode($this->MaterialModel->update($id));
			}
		} else {
			$errors = validation_errors('<div>- ', '</div>');
			echo json_encode(array('status' => false, 'data' => $errors));
		}
	}

	public function ajax_delete($id)
	{
		$this->handle_ajax_request();
		echo json_encode($this->MaterialModel->delete($id));
	}

	// Untuk mendapatkan chapter berdasarkan subject (opsional)
	public function ajax_get_chapters()
	{
		$subject_id = $this->input->get('subject_id');
		$chapters = $this->ChapterModel->getAll(['subject_id' => $subject_id], 'name', 'asc');
		echo json_encode($chapters);
	}

	public function update_progress()
	{
		$this->handle_ajax_request();
		$user_id = $this->session->userdata('user')['id'];
		$material_id = $this->input->post('material_id');
		$percent = $this->input->post('percent'); // 0-100

		$this->load->model('UserMaterialProgressModel');
		$this->UserMaterialProgressModel->updateProgress($user_id, $material_id, $percent);
		echo json_encode(['status' => true]);
	}

	public function ajax_update_progress()
{
    $this->handle_ajax_request();
    $user_id = $this->session->userdata('user')['id'];
    $material_id = $this->input->post('material_id');
    $percent = $this->input->post('progress_percent');
    $status = $percent >= 100 ? 'completed' : 'in_progress';

    $this->load->model('UserMaterialProgressModel');
    $data = [
        'progress_percent' => $percent,
        'status' => $status,
        'last_accessed' => date('Y-m-d H:i:s')
    ];
    if ($status == 'completed') {
        $data['completed_at'] = date('Y-m-d H:i:s');
    }
    $this->UserMaterialProgressModel->updateProgress($user_id, $material_id, $data);

    echo json_encode(['status' => true]);
}

	public function view($id)
	{
		$user_id = $this->session->userdata('user')['id'];
		$material = $this->MaterialModel->getDetail(['id' => $id, 'is_active' => 1]);
		if (!$material) {
			show_404();
		}

		// Update progress
		$this->load->model('UserMaterialProgressModel');
		$progress = $this->UserMaterialProgressModel->getProgress($user_id, $id);
		$data = [
			'last_accessed' => date('Y-m-d H:i:s')
		];
		if (!$progress) {
			$data['status'] = 'in_progress';
			$data['progress_percent'] = 0;
		}
		$this->UserMaterialProgressModel->updateProgress($user_id, $id, $data);

		// Redirect ke URL materi
		redirect($material->url);
	}
}
