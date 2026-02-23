<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ClassModel extends CI_Model
{
	private $_table = 'classes';

	public function rules()
	{
		return array(
			[
				'field' => 'name',
				'label' => 'Nama Kelas',
				'rules' => 'required|trim|max_length[100]'
			],
			[
				'field' => 'school_id',
				'label' => 'Sekolah',
				'rules' => 'required|integer'
			],
			[
				'field' => 'teacher_id',
				'label' => 'Wali Kelas',
				'rules' => 'integer'
			],
			[
				'field' => 'academic_year',
				'label' => 'Tahun Ajaran',
				'rules' => 'trim|max_length[20]'
			],
			[
				'field' => 'grade_level',
				'label' => 'Tingkat Kelas',
				'rules' => 'trim|max_length[20]'
			],
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
			$this->name = $this->input->post('name');
			$this->school_id = $this->input->post('school_id');
			$this->teacher_id = $this->input->post('teacher_id') ?: null;
			$this->academic_year = $this->input->post('academic_year');
			$this->grade_level = $this->input->post('grade_level');
			$this->db->insert($this->_table, $this);
			$response = array('status' => true, 'data' => 'Data kelas berhasil disimpan.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menyimpan data: ' . $th->getMessage());
		}
		return $response;
	}

	public function update($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->name = $this->input->post('name');
			$this->school_id = $this->input->post('school_id');
			$this->teacher_id = $this->input->post('teacher_id') ?: null;
			$this->academic_year = $this->input->post('academic_year');
			$this->grade_level = $this->input->post('grade_level');
			$this->db->update($this->_table, $this, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data kelas berhasil diperbarui.');
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
			$response = array('status' => true, 'data' => 'Data kelas berhasil dihapus.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
		}
		return $response;
	}

	function clean_number($number)
	{
		return preg_replace('/[^0-9]/', '', $number);
	}

	public function getByTeacher($teacher_id)
	{
		return $this->db->where('teacher_id', $teacher_id)
			->get('classes')
			->result();
	}

	public function getAccessibleForStudent($user_id)
	{
		$this->load->model('UserPackageModel');
		$class_ids = $this->UserPackageModel->getAccessibleItems($user_id, 'class');
		if (empty($class_ids)) {
			return [];
		}
		return $this->db->where_in('id', $class_ids)
			->order_by('name', 'asc')
			->get('classes')
			->result();
	}
}
