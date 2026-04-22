<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TryoutSessionModel extends CI_Model
{
	private $_table = 'tryout_sessions';

	public function rules()
	{
		return array(
			[
				'field' => 'tryout_id',
				'label' => 'Try Out',
				'rules' => 'required|integer'
			],
			[
				'field' => 'name',
				'label' => 'Nama Sesi',
				'rules' => 'required|trim|max_length[100]'
			],
			[
				'field' => 'session_order',
				'label' => 'Urutan',
				'rules' => 'required|integer'
			],
			[
				'field' => 'duration_minutes',
				'label' => 'Durasi (menit)',
				'rules' => 'required|integer|greater_than[0]'
			],
			[
				'field' => 'question_count',
				'label' => 'Jumlah Soal',
				'rules' => 'required|integer|greater_than[0]'
			],
			// [
			// 	'field' => 'is_random',
			// 	'label' => 'Acak Soal',
			// 	'rules' => 'required|in_list[0,1]'
			// ],
			[
				'field' => 'scoring_method',
				'label' => 'Metode Perhitungan Skor',
				'rules' => 'required|in_list[correct_incorrect,points_per_question]'
			],
			// [
			// 	'field' => 'enable_time_per_question',
			// 	'label' => 'Aktifkan Waktu Per Soal',
			// 	'rules' => 'required|in_list[0,1]'
			// ],
			[
				'field' => 'time_per_question',
				'label' => 'Waktu Per Soal (detik)',
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
			$this->tryout_id = $this->input->post('tryout_id');
			$this->name = $this->input->post('name');
			$this->session_order = $this->input->post('session_order');
			$this->duration_minutes = $this->input->post('duration_minutes');
			$this->question_count = $this->input->post('question_count');
			$this->description = $this->input->post('description');
			$this->is_random = $this->input->post('is_random', TRUE) ?: 0;
			$this->scoring_method = $this->input->post('scoring_method', TRUE) ?: 'correct_incorrect';
			$this->enable_time_per_question = $this->input->post('enable_time_per_question', TRUE) ?: 0;
			$this->time_per_question = $this->input->post('time_per_question', TRUE) ?: 0;
			$this->db->insert($this->_table, $this);
			$response = array('status' => true, 'data' => 'Data sesi try out berhasil disimpan.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menyimpan data: ' . $th->getMessage());
		}
		return $response;
	}

	public function update($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->tryout_id = $this->input->post('tryout_id');
			$this->name = $this->input->post('name');
			$this->session_order = $this->input->post('session_order');
			$this->duration_minutes = $this->input->post('duration_minutes');
			$this->question_count = $this->input->post('question_count');
			$this->description = $this->input->post('description');
			$this->is_random = $this->input->post('is_random', TRUE) ?: 0;
			$this->scoring_method = $this->input->post('scoring_method', TRUE) ?: 'correct_incorrect';
			$this->enable_time_per_question = $this->input->post('enable_time_per_question', TRUE) ?: 0;
			$this->time_per_question = $this->input->post('time_per_question', TRUE) ?: 0;
			$this->db->update($this->_table, $this, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data sesi try out berhasil diperbarui.');
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
			$response = array('status' => true, 'data' => 'Data sesi try out berhasil dihapus.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
		}
		return $response;
	}

	public function getByTryout($tryout_id)
	{
		return $this->db->where('tryout_id', $tryout_id)
			->order_by('session_order', 'asc')
			->get('tryout_sessions')
			->result();
	}

	 public function getFirstSession($tryout_id)
    {
        return $this->db->where('tryout_id', $tryout_id)
                        ->order_by('session_order', 'asc')
                        ->limit(1)
                        ->get($this->_table)
                        ->row();
    }

    /**
     * Mendapatkan semua sesi berdasarkan tryout
     */
   

    /**
     * Mendapatkan sesi berikutnya setelah sesi tertentu
     */
    public function getNextSession($tryout_id, $current_session_id)
    {
        $current = $this->db->where('id', $current_session_id)->get($this->_table)->row();
        if (!$current) return null;
        return $this->db->where('tryout_id', $tryout_id)
                        ->where('session_order >', $current->session_order)
                        ->order_by('session_order', 'asc')
                        ->limit(1)
                        ->get($this->_table)
                        ->row();
    }
}	
