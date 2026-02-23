<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MaterialModel extends CI_Model
{
	private $_table = 'materials';

	public function rules()
	{
		return array(
			[
				'field' => 'title',
				'label' => 'Judul Materi',
				'rules' => 'required|trim|max_length[200]'
			],
			[
				'field' => 'type',
				'label' => 'Tipe',
				'rules' => 'required|in_list[video,pdf,modul]'
			],
			[
				'field' => 'url',
				'label' => 'URL',
				'rules' => 'required|trim|max_length[255]'
			],
			[
				'field' => 'subject_id',
				'label' => 'Mata Pelajaran',
				'rules' => 'integer'
			],
			[
				'field' => 'chapter_id',
				'label' => 'Bab',
				'rules' => 'integer'
			],
			[
				'field' => 'duration_seconds',
				'label' => 'Durasi (detik)',
				'rules' => 'integer'
			],
			[
				'field' => 'is_active',
				'label' => 'Status Aktif',
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
			$this->type = $this->input->post('type');
			$this->url = $this->input->post('url');
			$this->subject_id = $this->input->post('subject_id') ?: null;
			$this->chapter_id = $this->input->post('chapter_id') ?: null;
			$this->description = $this->input->post('description');
			$this->duration_seconds = $this->input->post('duration_seconds') ?: null;
			$this->created_by = $this->session->userdata('user')['id'];
			$this->is_active = $this->input->post('is_active') ? 1 : 0;
			$this->created_at = date('Y-m-d H:i:s');
			$this->db->insert($this->_table, $this);
			$response = array('status' => true, 'data' => 'Data materi berhasil disimpan.');
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
			$this->type = $this->input->post('type');
			$this->url = $this->input->post('url');
			$this->subject_id = $this->input->post('subject_id') ?: null;
			$this->chapter_id = $this->input->post('chapter_id') ?: null;
			$this->description = $this->input->post('description');
			$this->duration_seconds = $this->input->post('duration_seconds') ?: null;
			$this->is_active = $this->input->post('is_active') ? 1 : 0;
			$this->updated_at = date('Y-m-d H:i:s');
			$this->db->update($this->_table, $this, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data materi berhasil diperbarui.');
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
			$response = array('status' => true, 'data' => 'Data materi berhasil dihapus.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
		}
		return $response;
	}

	public function getAccessibleForStudent($user_id)
	{
		$this->load->model('UserPackageModel');
		$material_ids = $this->UserPackageModel->getAccessibleItems($user_id, 'material');
		if (empty($material_ids)) {
			return [];
		}
		return $this->db->where_in('id', $material_ids)
			->where('is_active', 1)
			->order_by('title', 'asc')
			->get('materials')
			->result();
	}
}
