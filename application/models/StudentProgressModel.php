<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StudentProgressModel extends CI_Model
{
	private $_table = 'student_progress';

	public function getLatest($user_id)
	{
		return $this->db->where('user_id', $user_id)
			->order_by('snapshot_date', 'desc')
			->limit(1)
			->get($this->_table)
			->row();
	}

	public function getHistory($user_id, $limit = 30)
	{
		return $this->db->where('user_id', $user_id)
			->order_by('snapshot_date', 'desc')
			->limit($limit)
			->get($this->_table)
			->result();
	}

	public function saveProgress($user_id, $data)
	{
		$data['user_id'] = $user_id;
		$data['snapshot_date'] = date('Y-m-d');
		// Cek apakah sudah ada entry hari ini
		$existing = $this->db->where('user_id', $user_id)
			->where('snapshot_date', date('Y-m-d'))
			->get($this->_table)
			->row();
		if ($existing) {
			$this->db->where('id', $existing->id)->update($this->_table, $data);
		} else {
			$this->db->insert($this->_table, $data);
		}
	}


	public function getChartData($user_id, $days = 30)
	{
		$start = date('Y-m-d', strtotime("-$days days"));
		return $this->db->select('snapshot_date, skor_akademik, skor_konsistensi, skor_kesiapan')
			->where('user_id', $user_id)
			->where('snapshot_date >=', $start)
			->order_by('snapshot_date', 'asc')
			->get('student_progress')
			->result();
	}

	// public function getStudentsNeedAttention($student_ids = [], $limit = null)
	// {
	// 	$this->db->select('sp.*, u.nama_lengkap, u.email, u.unit as school_id')
	// 		->from('student_progress sp')
	// 		->join('user u', 'u.id = sp.user_id')
	// 		->where('sp.status_kesiapan', 'Perlu Pendampingan Intensif')
	// 		->order_by('sp.snapshot_date', 'desc');

	// 	if (!empty($student_ids)) {
	// 		$this->db->where_in('sp.user_id', $student_ids);
	// 	}

	// 	if ($limit) {
	// 		$this->db->limit($limit);
	// 	}

	// 	return $this->db->get()->result();
	// }

	public function getStudentsNeedAttention($student_ids = null)
	{
		$this->db->select('sp.*, u.nama_lengkap, c.name as class_name')
			->from('student_progress sp')
			->join('user u', 'u.id = sp.user_id')
			->join('student_class sc', 'sc.student_id = u.id', 'left')
			->join('classes c', 'c.id = sc.class_id', 'left')
			->where('sp.status_kesiapan', 'Perlu Pendampingan Intensif')
			->order_by('sp.snapshot_date', 'desc');

		if (!empty($student_ids) && is_array($student_ids)) {
			$this->db->where_in('sp.user_id', $student_ids);
		} elseif (!empty($student_ids) && !is_array($student_ids)) {
			// jika hanya satu id, ubah jadi array
			$this->db->where_in('sp.user_id', [$student_ids]);
		}

		return $this->db->get()->result();
	}

	// public function getClassProgress($class_id)
	// {
	// 	if (!is_numeric($class_id)) {
	// 		return [];
	// 	}
	// 	// Load model yang diperlukan
	// 	$this->load->model('StudentClassModel');
	// 	$students = $this->StudentClassModel->getStudentsByClass($class_id);
	// 	if (empty($students)) {
	// 		return [];
	// 	}

	// 	$student_ids = array_column($students, 'id');
	// 	return $this->db->select('sp.*, u.nama_lengkap')
	// 		->from('student_progress sp')
	// 		->join('user u', 'u.id = sp.user_id')
	// 		->where_in('sp.user_id', $student_ids)
	// 		->where('sp.snapshot_date = (SELECT MAX(snapshot_date) FROM student_progress sp2 WHERE sp2.user_id = sp.user_id)')
	// 		->get()
	// 		->result();
	// }

	public function getClassProgress($class_id)
	{
		$students = $this->StudentClassModel->getStudentsByClass($class_id);
		$result = [];
		foreach ($students as $s) {
			$progress = $this->getLatest($s->id);
			if ($progress) {
				$result[] = [
					'student_id' => $s->id,
					'nama_lengkap' => $s->nama_lengkap,
					'status' => $progress->status_kesiapan,
					'skor' => $progress->skor_kesiapan
				];
			}
		}
		return $result;
	}
}
