<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class My_classes extends AppBackend
{
	public function __construct()
	{
		parent::__construct();
		// Hanya untuk role student
		if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
			show_error('Akses ditolak', 403);
		}
		$this->load->model(['AppModel', 'StudentClassModel']);
	}

	// public function index()
	// {
	//     $data = [
	//         'app' => $this->app(),
	//         'main_js' => $this->load_main_js('my_classes'),
	//         'card_title' => 'Kelas Saya'
	//     ];
	//     $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
	//     $this->template->load_view('index', $data, TRUE);
	//     $this->template->render();
	// }

	public function index()
	{
		$user_id = $this->session->userdata('user')['id'];
		$this->load->model('ClassModel');
		$classes = $this->ClassModel->getAccessibleForStudent($user_id);

		// Jika ingin tetap menampilkan kelas dari student_class juga? Atau hanya dari paket? 
		// Tergantung kebutuhan. Kita asumsikan hanya dari paket.

		$data = [
			'app' => $this->app(),
			'card_title' => 'Kelas Saya (Aktif)',
			'classes' => $classes
		];
		$this->template->load_view('index', $data);
		$this->template->render();
	}

	public function ajax_get_all()
	{
		$this->handle_ajax_request();
		$user_id = $this->session->userdata('user')['id'];

		$dtAjax_config = [
			'select_column' => [
				'classes.id',
				'classes.name as class_name',
				'schools.name as school_name',
				'classes.academic_year',
				'classes.grade_level',
				'teacher.nama_lengkap as teacher_name'
			],
			'table_name' => 'student_class',
			'table_join' => [
				[
					'table_name' => 'classes',
					'expression' => 'classes.id = student_class.class_id',
					'type' => 'inner'
				],
				[
					'table_name' => 'schools',
					'expression' => 'schools.id = classes.school_id',
					'type' => 'left'
				],
				[
					'table_name' => 'user teacher',
					'expression' => 'teacher.id = classes.teacher_id',
					'type' => 'left'
				]
			],
			'static_conditional' => [
				'student_class.student_id' => $user_id
			],
			'order_column' => 1, // class_name
			'order_column_dir' => 'asc',
		];
		$response = $this->AppModel->getData_dtAjax($dtAjax_config);
		echo json_encode($response);
	}
}
