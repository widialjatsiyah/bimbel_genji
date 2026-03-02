<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserTryoutModel extends CI_Model
{
	private $_table = 'user_tryouts';

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
	public function getActiveUserTryout($user_id, $tryout_id)
	{
		return $this->db->where('user_id', $user_id)
			->where('tryout_id', $tryout_id)
			->where('status', 'in_progress')
			->get($this->_table)
			->row();
	}

	public function startTryout($user_id, $tryout_id)
	{
		$data = [
			'user_id' => $user_id,
			'tryout_id' => $tryout_id,
			'start_time' => date('Y-m-d H:i:s'),
			'status' => 'in_progress'
		];
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	public function completeTryout($id, $total_score = null)
	{
		$this->db->where('id', $id)->update($this->_table, [
			'end_time' => date('Y-m-d H:i:s'),
			'status' => 'completed',
			'total_score' => $total_score
		]);
	}

	public function getLatestByUser($user_id)
	{
		return $this->db->where('user_id', $user_id)
			->order_by('created_at', 'desc')
			->limit(1)
			->get('user_tryouts')
			->row();
	}

	public function getRecentActivities($user_id, $limit = 5)
	{
		return $this->db->select('ut.*, t.title as tryout_title')
			->from('user_tryouts ut')
			->join('tryouts t', 't.id = ut.tryout_id')
			->where('ut.user_id', $user_id)
			->order_by('ut.created_at', 'desc')
			->limit($limit)
			->get()
			->result();
	}

	public function getAverageScoreByClass($class_id)
	{
		// Ambil semua user_id dari kelas
		$this->load->model('StudentClassModel');
		$students = $this->StudentClassModel->getStudentsByClass($class_id);
		if (empty($students)) return 0;

		$user_ids = array_column($students, 'id');
		$this->db->select_avg('total_score', 'avg_score')
			->where_in('user_id', $user_ids)
			->where('status', 'completed');
		$result = $this->db->get('user_tryouts')->row();
		return $result ? round($result->avg_score, 2) : 0;
	}

	public function countByStudents($student_ids, $status = null)
	{
		if (empty($student_ids)) return 0;
		$this->db->where_in('user_id', $student_ids);
		if ($status) {
			$this->db->where('status', $status);
		}
		return $this->db->count_all_results('user_tryouts');
	}

	public function getRecentByStudents($student_ids, $limit = 10)
	{
		if (empty($student_ids)) {
			return [];
		}
		return $this->db->select('ut.*, u.nama_lengkap, t.title as tryout_title')
			->from('user_tryouts ut')
			->join('user u', 'u.id = ut.user_id')
			->join('tryouts t', 't.id = ut.tryout_id')
			->where_in('ut.user_id', $student_ids)
			->order_by('ut.created_at', 'desc')
			->limit($limit)
			->get()
			->result();
	}

	public function getHistoryByUser($user_id)
	{
		return $this->db->select('ut.*, t.title as tryout_title')
			->from('user_tryouts ut')
			->join('tryouts t', 't.id = ut.tryout_id')
			->where('ut.user_id', $user_id)
			->order_by('ut.created_at', 'desc')
			->get()
			->result();
	}

	public function getLeaderboard($tryout_id, $limit = 10)
	{
		return $this->db->select('u.nama_lengkap, ut.total_score')
			->from('user_tryouts ut')
			->join('user u', 'u.id = ut.user_id')
			->where('ut.tryout_id', $tryout_id)
			->where('ut.status', 'completed')
			->order_by('ut.total_score', 'desc')
			->limit($limit)
			->get()
			->result();
	}
}
