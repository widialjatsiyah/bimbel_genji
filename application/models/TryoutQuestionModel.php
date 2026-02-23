<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TryoutQuestionModel extends CI_Model
{
	private $_table = 'tryout_questions';

	public function rules()
	{
		return array(
			[
				'field' => 'tryout_session_id',
				'label' => 'Sesi Try Out',
				'rules' => 'required|integer'
			],
			[
				'field' => 'question_id',
				'label' => 'Soal',
				'rules' => 'required|integer'
			],
			[
				'field' => 'question_order',
				'label' => 'Urutan Soal',
				'rules' => 'required|integer'
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
			$this->tryout_session_id = $this->input->post('tryout_session_id');
			$this->question_id = $this->input->post('question_id');
			$this->question_order = $this->input->post('question_order');
			$this->db->insert($this->_table, $this);
			$response = array('status' => true, 'data' => 'Soal berhasil ditambahkan ke sesi.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menambahkan soal: ' . $th->getMessage());
		}
		return $response;
	}

	public function update($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->tryout_session_id = $this->input->post('tryout_session_id');
			$this->question_id = $this->input->post('question_id');
			$this->question_order = $this->input->post('question_order');
			$this->db->update($this->_table, $this, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data soal sesi berhasil diperbarui.');
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
			$response = array('status' => true, 'data' => 'Soal berhasil dihapus dari sesi.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menghapus soal: ' . $th->getMessage());
		}
		return $response;
	}

	public function getQuestionsBySession($session_id, $random = false)
	{
		$this->db->select('tq.*, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, q.option_e, q.correct_option, q.explanation')
			->from('tryout_questions tq')
			->join('questions q', 'q.id = tq.question_id')
			->where('tq.tryout_session_id', $session_id);
		if ($random) {
			$this->db->order_by('RAND()');
		} else {
			$this->db->order_by('tq.question_order', 'asc');
		}
		return $this->db->get()->result();
	}
}
