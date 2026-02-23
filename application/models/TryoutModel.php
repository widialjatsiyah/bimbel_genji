<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TryoutModel extends CI_Model
{
	private $_table = 'tryouts';

	public function rules()
	{
		return array(
			[
				'field' => 'title',
				'label' => 'Judul Try Out',
				'rules' => 'required|trim|max_length[200]'
			],
			[
				'field' => 'type',
				'label' => 'Tipe',
				'rules' => 'required|in_list[UTBK,TKA,Jurusan,Sekolah,Lainnya]'
			],
			[
				'field' => 'mode',
				'label' => 'Mode',
				'rules' => 'required|in_list[resmi,latihan,evaluasi]'
			],
			[
				'field' => 'total_duration',
				'label' => 'Durasi Total (menit)',
				'rules' => 'integer'
			],
			[
				'field' => 'start_time',
				'label' => 'Waktu Mulai',
				'rules' => 'trim'
			],
			[
				'field' => 'end_time',
				'label' => 'Waktu Selesai',
				'rules' => 'trim'
			],
			[
				'field' => 'is_published',
				'label' => 'Publikasi',
				'rules' => 'in_list[0,1]'
			]
		);
	}

	public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
	{
		$this->db->where($params);
		if (!is_null($orderField)) {
			$this->db->order_by($orderField, $orderBy);
		};
		return $this->db->get($this->_table)->result();
	}

	public function getDetail($params = array())
	{
		$this->db->where($params);
		return $this->db->get($this->_table)->row();
	}

	public function insert()
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->title = $this->input->post('title');
			$this->description = $this->input->post('description');
			$this->type = $this->input->post('type');
			$this->mode = $this->input->post('mode');
			$this->total_duration = $this->input->post('total_duration') ?: null;
			$this->start_time = $this->input->post('start_time') ?: null;
			$this->end_time = $this->input->post('end_time') ?: null;
			$this->is_published = $this->input->post('is_published') ? 1 : 0;
			$this->created_by = $this->session->userdata('user')['id']; // asumsikan session user id
			$this->created_at = date('Y-m-d H:i:s');
			$this->db->insert($this->_table, $this);
			$response = array('status' => true, 'data' => 'Data try out berhasil disimpan.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menyimpan data: ' . $th->getMessage());
		}
		return $response;
	}

	public function update($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->title = $this->input->post('title');
			$this->description = $this->input->post('description');
			$this->type = $this->input->post('type');
			$this->mode = $this->input->post('mode');
			$this->total_duration = $this->input->post('total_duration') ?: null;
			$this->start_time = $this->input->post('start_time') ?: null;
			$this->end_time = $this->input->post('end_time') ?: null;
			$this->is_published = $this->input->post('is_published') ? 1 : 0;
			$this->updated_at = date('Y-m-d H:i:s');
			$this->db->update($this->_table, $this, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data try out berhasil diperbarui.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal memperbarui data: ' . $th->getMessage());
		}
		return $response;
	}

	public function delete($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->db->delete($this->_table, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data try out berhasil dihapus.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
		}
		return $response;
	}

	public function getAvailableForUser($user_id)
	{
		// Asumsi: try out yang dipublikasikan dan belum dikerjakan user
		$this->db->select('t.*')
			->from('tryouts t')
			->where('t.is_published', 1)
			->where("NOT EXISTS (SELECT 1 FROM user_tryouts ut WHERE ut.tryout_id = t.id AND ut.user_id = $user_id)", NULL, FALSE);
		// ->where_not_exists('SELECT 1 FROM user_tryouts ut WHERE ut.tryout_id = t.id AND ut.user_id = ' . $user_id, false);
		return $this->db->get()->result();
	}

	// public function getAvailableForStudent($user_id)
	// {
	// 	// Ambil ID kelas siswa
	// 	$this->load->model('StudentClassModel');
	// 	$classes = $this->StudentClassModel->getClassesByStudent($user_id);
	// 	$class_ids = array_column($classes, 'id');

	// 	if (empty($class_ids)) {
	// 		// Jika tidak punya kelas, tampilkan try out publik
	// 		return $this->db->where('is_published', 1)
	// 			->where('type !=', 'Sekolah')
	// 			->get('tryouts')
	// 			->result();
	// 	}

	// 	// Try out publik + try out yang dijadwalkan untuk kelasnya
	// 	$sql = "SELECT t.* FROM tryouts t
	//         WHERE t.is_published = 1
	//         AND (t.type != 'Sekolah' OR t.id IN (
	//             SELECT tc.tryout_id FROM tryout_class tc
	//             WHERE tc.class_id IN (" . implode(',', $class_ids) . ")
	//             AND (tc.start_time IS NULL OR tc.start_time <= NOW())
	//             AND (tc.end_time IS NULL OR tc.end_time >= NOW())
	//         ))";
	// 	return $this->db->query($sql)->result();
	// }

	public function getAvailableForStudent($user_id)
	{
		$this->load->model('UserPackageModel');
		$tryout_ids = $this->UserPackageModel->getAccessibleItems($user_id, 'tryout');
		if (empty($tryout_ids)) {
			return [];
		}
		return $this->db->where_in('id', $tryout_ids)
			->where('is_published', 1)
			->order_by('title', 'asc')
			->get('tryouts')
			->result();
	}


	public function countPublished()
	{
		return $this->db->where('is_published', 1)->count_all_results('tryouts');
	}

	public function getLatestPublished()
	{
		return $this->db->where('is_published', 1)
			->order_by('created_at', 'desc')
			->limit(1)
			->get('tryouts')
			->row();
	}
}
