<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UniversitasModel extends CI_Model
{
	private $_table = 'universitas';
	private $_tableView = '';
	private $_columns = array(
		'name',
		'keterangan',
		'nilai'
	); // Urutan (index) harus sama dengan template excel, dan penamaan harus sama dengan tabel (case-sensitive)

	public function rules($id)
	{
		return array(
			[
				'field' => 'name',
				'label' => 'Nama Universitas',
				'rules' => [
					'required',
					'trim',
					'min_length[3]',
					'max_length[200]',
					[
						'name_exist',
						function ($name) use ($id) {
							return $this->_name_exist($name, $id);
						}
					]
				]
			],
			[
				'field' => 'keterangan',
				'label' => 'Keterangan',
				'rules' => 'trim'
			],
			[
				'field' => 'nilai',
				'label' => 'Nilai',
				'rules' => 'trim'
			]
		);
	}


	private function _name_exist($name, $id)
	{
		$id = (!IS_NULL($id)) ? $id : 0;
		$temp = $this->db->where(array('id !=' => $id, 'name' => $name))->get($this->_table);

		if ($temp->num_rows() > 0) {
			return false;
		} else {
			return true;
		};
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
		return $this->db->where($params)->get($this->_table)->row();
	}

	public function insert()
	{
		$response = array('status' => false, 'data' => 'No operation.');

		try {
			$this->name = $this->input->post('name');
			$this->keterangan = $this->input->post('keterangan');
			$this->nilai = $this->input->post('nilai');
			$this->created_at = date('Y-m-d H:i:s');
			$this->created_by = $this->session->userdata('user')['id'];
			$this->db->insert($this->_table, $this);

			$response = array('status' => true, 'data' => 'Data has been saved.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Failed to save your data.');
		}; 

		return $response;
	}

	public function insertBatch($data)
	{
		$response = array('status' => false, 'data' => 'No operation.');

		try {
			$this->db->insert_batch($this->_table, $data);

			$response = array('status' => true, 'data' => 'Data has been saved.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Failed to save your data.');
		};

		return $response;
	}

	public function update($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');

		try {
			$this->name = $this->input->post('name');
			$this->keterangan = $this->input->post('keterangan');
			$this->nilai = $this->input->post('nilai');
			$this->updated_at = date('Y-m-d H:i:s');
			$this->updated_by = $this->session->userdata('user')['id'];
			$this->db->update($this->_table, $this, array('id' => $id));

			$response = array('status' => true, 'data' => 'Data has been saved.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Failed to save your data.');
		};

		return $response;
	}
	public function delete($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');

		try {
			$this->db->delete($this->_table, array('id' => $id));

			$response = array('status' => true, 'data' => 'Data has been deleted.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Failed to delete your data.');
		};

		return $response;
	}

	public function truncate()
	{
		$response = array('status' => false, 'data' => 'No operation.');

		try {
			$this->db->truncate($this->_table);

			$response = array('status' => true, 'data' => 'Data has been deleted.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Failed to delete your data.');
		};

		return $response;
	}


	function clean_number($number)
	{
		return preg_replace('/[^0-9]/', '', $number);
	}
}
