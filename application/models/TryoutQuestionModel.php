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
			],
			[
				'field' => 'points',
				'label' => 'Poin Soal',
				'rules' => 'required|numeric|greater_than[0]'
			],
			[
				'field' => 'time_limit',
				'label' => 'Batas Waktu Soal (detik)',
				'rules' => 'integer|greater_than_equal_to[0]'
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
			$this->points = $this->input->post('points') ?: 1.00;
			$this->time_limit = $this->input->post('time_limit') ?: 0;
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
			$this->points = $this->input->post('points') ?: 1.00;
			$this->time_limit = $this->input->post('time_limit') ?: 0;
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
		$this->db->select('tq.*, q.question_text, q.question_type, q.question_image, q.expected_keywords, q.min_keyword_matches, q.option_type, q.option_a, q.option_b, q.option_c, q.option_d, q.option_e, q.correct_option, q.explanation, q.group_id, q.group_order, q.is_group_main')
			->from('tryout_questions tq')
			->join('questions q', 'q.id = tq.question_id')
			->where('tq.tryout_session_id', $session_id);
			
		if ($random) {
			// Untuk soal acak, kita perlu mengelompokkan soal berdasarkan group_id
			$questions = $this->db->get()->result();
			
			// Kelompokkan soal berdasarkan group_id
			$grouped_questions = [];
			$ungrouped_questions = [];
			
			foreach ($questions as $question) {
				if ($question->group_id !== null) {
					if (!isset($grouped_questions[$question->group_id])) {
						$grouped_questions[$question->group_id] = [];
					}
					$grouped_questions[$question->group_id][] = $question;
				} else {
					$ungrouped_questions[] = $question;
				}
			}
			
			// Acak urutan grup dan soal non-grup
			shuffle($ungrouped_questions);
			$group_keys = array_keys($grouped_questions);
			shuffle($group_keys);
			
			// Gabungkan hasilnya
			$result = $ungrouped_questions;
			
			// Tambahkan soal-soal berkelompok, dengan tetap menjaga urutan dalam grup
			foreach ($group_keys as $groupId) {
				// Urutkan soal dalam grup sesuai group_order
				usort($grouped_questions[$groupId], function($a, $b) {
					return $a->group_order - $b->group_order;
				});
				
				// Tambahkan ke hasil
				foreach ($grouped_questions[$groupId] as $q) {
					$result[] = $q;
				}
			}
			
			return $result;
		} else {
			// Urutkan berdasarkan question_order, namun tetap menjaga urutan dalam grup
			// Non-grouped questions first (group_id IS NULL), then grouped questions
			$this->db->order_by('(q.group_id IS NULL), q.group_id, q.group_order, tq.question_order', 'ASC');
			return $this->db->get()->result();
		}
	}
	
	/**
	 * Check if a question already exists in a session
	 */
	public function questionExistsInSession($session_id, $question_id)
	{
		$this->db->where('tryout_session_id', $session_id);
		$this->db->where('question_id', $question_id);
		$query = $this->db->get($this->_table);
		
		return $query->num_rows() > 0;
	}
	
	/**
	 * Get the highest question order in a session
	 */
	public function getMaxQuestionOrder($session_id)
	{
		$this->db->select_max('question_order');
		$this->db->where('tryout_session_id', $session_id);
		$result = $this->db->get($this->_table)->row();
		
		return $result->question_order ? $result->question_order : 0;
	}
	
	/**
	 * Update points for a specific question in a session
	 */
	public function updatePoints($session_id, $question_id, $points)
	{
		$data = array(
			'points' => $points
		);
		
		$this->db->where('tryout_session_id', $session_id);
		$this->db->where('question_id', $question_id);
		return $this->db->update($this->_table, $data);
	}
	
	/**
	 * Update time limit for a specific question in a session
	 */
	public function updateTimeLimit($session_id, $question_id, $time_limit)
	{
		$data = array(
			'time_limit' => $time_limit
		);
		
		$this->db->where('tryout_session_id', $session_id);
		$this->db->where('question_id', $question_id);
		return $this->db->update($this->_table, $data);
	}
}