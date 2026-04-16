<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Meeting_material extends AppBackend
{
	public function __construct()
	{
		parent::__construct();
		// Hanya untuk role tutor
		if ($this->session->userdata('user')['role'] != 'tutor') {
			// show_error('Akses ditolak', 403);
		}
		$this->load->model([
			'MeetingMaterialModel',
			'ClassMeetingModel',
			'ClassModel',
			'MaterialModel'
		]);
		$this->load->library('form_validation');
	}

	/**
	 * Halaman daftar materi untuk meeting tertentu
	 */
	public function index($meeting_id)
	{
		// Cek akses tutor ke meeting ini
		$meeting = $this->ClassMeetingModel->getById($meeting_id);
		if (!$meeting) {
			// show_404();
			var_dump($meeting_id);
		}

		// Cek apakah tutor mengajar kelas dari meeting ini
		$teacher_id = $this->session->userdata('user')['id'];
		$class = $this->ClassModel->getDetail(['id' => $meeting->class_id, 'teacher_id' => $teacher_id]);
		if (!$class) {
			show_error('Anda tidak memiliki akses ke kelas ini', 403);
		}

		$data = [
			'app' => $this->app(),
			'main_js' => $this->load_main_js('meeting_material'),
			'card_title' => 'Kelola Materi: ' . $meeting->title,
			'meeting' => $meeting,
			'meeting_id' => $meeting_id
		];
		$this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
		$this->template->load_view('index', $data, TRUE);
		$this->template->render();
	}

	/**
	 * Ambil data materi via AJAX untuk DataTables
	 */
	public function ajax_get_all($meeting_id)
	{
		$this->handle_ajax_request();
		$materials = $this->MeetingMaterialModel->getByMeeting($meeting_id);
		$response = [
			'data' => $materials,
			'recordsTotal' => count($materials),
			'recordsFiltered' => count($materials),
			'draw' => $this->input->post('draw')
		];
		echo json_encode($response);
	}

	/**
	 * Ambil daftar materi yang tersedia (belum ditambahkan ke meeting ini)
	 */
	public function ajax_get_available_materials($meeting_id)
	{
		$this->handle_ajax_request();
		$meeting = $this->ClassMeetingModel->getById($meeting_id);
		if (!$meeting) {
			echo json_encode([]);
			return;
		}

		// Ambil semua materi yang aktif
		$all_materials = $this->MaterialModel->getAll(['is_active' => 1], 'title', 'asc');

		// Ambil materi yang sudah ada di meeting ini
		$existing = $this->MeetingMaterialModel->getByMeeting($meeting_id);
		$existing_ids = array_column($existing, 'material_id');

		// Filter yang belum ada
		$available = [];
		foreach ($all_materials as $m) {
			if (!in_array($m->id, $existing_ids)) {
				$available[] = [
					'id' => $m->id,
					'text' => $m->title . ' (' . $m->type . ')'
				];
			}
		}

		echo json_encode($available);
	}

	/**
	 * Tambah materi ke meeting
	 */
	public function ajax_add()
	{
		$this->handle_ajax_request();
		$meeting_id = $this->input->post('meeting_id');
		$material_id = $this->input->post('material_id');
		$order_num = $this->input->post('order_num') ?: 0;

		// Validasi akses meeting
		$meeting = $this->ClassMeetingModel->getById($meeting_id);
		if (!$meeting) {
			echo json_encode(['status' => false, 'data' => 'Meeting tidak ditemukan']);
			return;
		}

		$teacher_id = $this->session->userdata('user')['id'];
		$class = $this->ClassModel->getDetail(['id' => $meeting->class_id, 'teacher_id' => $teacher_id]);
		if (!$class) {
			echo json_encode(['status' => false, 'data' => 'Akses ditolak']);
			return;
		}

		// Cek apakah materi sudah ada
		$existing = $this->db->where('meeting_id', $meeting_id)
			->where('material_id', $material_id)
			->get('meeting_materials')
			->row();
		if ($existing) {
			echo json_encode(['status' => false, 'data' => 'Materi sudah ditambahkan']);
			return;
		}

		$this->MeetingMaterialModel->addMaterial($meeting_id, $material_id, $order_num);
		echo json_encode(['status' => true, 'data' => 'Materi berhasil ditambahkan']);
	}

	/**
	 * Hapus materi dari meeting
	 */
	public function ajax_remove($id)
	{
		$this->handle_ajax_request();
		$item = $this->MeetingMaterialModel->getById($id);
		if (!$item) {
			echo json_encode(['status' => false, 'data' => 'Data tidak ditemukan']);
			return;
		}

		// Validasi akses
		$meeting = $this->ClassMeetingModel->getById($item->meeting_id);
		if (!$meeting) {
			echo json_encode(['status' => false, 'data' => 'Meeting tidak ditemukan']);
			return;
		}

		$teacher_id = $this->session->userdata('user')['id'];
		$class = $this->ClassModel->getDetail(['id' => $meeting->class_id, 'teacher_id' => $teacher_id]);
		if (!$class) {
			echo json_encode(['status' => false, 'data' => 'Akses ditolak']);
			return;
		}

		$this->MeetingMaterialModel->removeMaterial($id);
		echo json_encode(['status' => true, 'data' => 'Materi berhasil dihapus']);
	}

	public function getAll($params = [], $order = 'title ASC')
	{
		if (!empty($params)) $this->db->where($params);
		return $this->db->order_by($order)->get('materials')->result();
	}
}
