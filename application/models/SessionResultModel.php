<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SessionResultModel extends CI_Model
{
	private $_table = 'session_results';

	public function saveResult($user_tryout_id, $tryout_session_id, $correct_count, $wrong_count, $skipped_count, $score)
	{
		$data = [
			'user_tryout_id' => $user_tryout_id,
			'tryout_session_id' => $tryout_session_id,
			'correct_count' => $correct_count,
			'wrong_count' => $wrong_count,
			'skipped_count' => $skipped_count,
			'score' => $score
		];
		$existing = $this->db->where('user_tryout_id', $user_tryout_id)
			->where('tryout_session_id', $tryout_session_id)
			->get($this->_table)
			->row();
		if ($existing) {
			$this->db->where('id', $existing->id)->update($this->_table, $data);
		} else {
			$this->db->insert($this->_table, $data);
		}
	}

	public function getByUserTryoutAndSession($user_tryout_id, $session_id)
	{
		return $this->db->where('user_tryout_id', $user_tryout_id)
			->where('tryout_session_id', $session_id)
			->get('session_results')
			->row();
	}
}
