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
	
	// Fungsi baru untuk mendukung tryout session
	public function getActiveUserTryoutBySession($user_id, $session_id)
	{
		// Cek apakah kolom tryout_session_id ada di tabel
		if ($this->columnExists('tryout_session_id', $this->_table)) {
			return $this->db->where('user_id', $user_id)
				->where('tryout_session_id', $session_id)  // Menggunakan tryout_session_id jika kolom tersedia
				->where('status', 'in_progress')
				->get($this->_table)
				->row();
		} else {
			// Jika kolom tidak ada, kembalikan null karena tidak bisa mencocokkan dengan sesi
			return null;
		}
	}

	public function startTryoutWithSession($user_id, $session_id)
	{
		// Cek apakah kolom tryout_session_id ada di tabel
		if ($this->columnExists('tryout_session_id', $this->_table)) {
			// Ambil tryout_id dari session
			$this->load->model('TryoutSessionModel');
			$session = $this->TryoutSessionModel->getDetail(['id' => $session_id]);
			
			if ($session) {
				$data = [
					'user_id' => $user_id,
					'tryout_id' => $session->tryout_id,  // Isi tryout_id dari session
					'tryout_session_id' => $session_id,  // Menggunakan tryout_session_id
					'start_time' => date('Y-m-d H:i:s'),
					'status' => 'in_progress'
				];
				$this->db->insert($this->_table, $data);
				return $this->db->insert_id();
			} else {
				// Jika session tidak ditemukan, kembalikan 0
				return 0;
			}
		} else {
			// Jika kolom tidak ada, kembalikan 0 karena tidak bisa membuat entri
			return 0;
		}
	}
	
	public function getRecentActivitiesWithSession($user_id, $limit = 5)
	{
		// Cek apakah kolom tryout_session_id ada di tabel
		if ($this->columnExists('tryout_session_id', $this->_table)) {
			return $this->db->select('ut.*, t.title as tryout_title, ts.name as session_name')
				->from('user_tryouts ut')
				->join('tryout_sessions ts', 'ts.id = ut.tryout_session_id', 'left')
				->join('tryouts t', 't.id = ts.tryout_id', 'left')
				->where('ut.user_id', $user_id)
				->order_by('ut.created_at', 'desc')
				->limit($limit)
				->get()
				->result();
		} else {
			// Jika kolom tidak ada, gunakan fungsi lama
			return $this->getRecentActivities($user_id, $limit);
		}
	}

	public function getHistoryByUserWithSession($user_id)
	{
		// Cek apakah kolom tryout_session_id ada di tabel
		if ($this->columnExists('tryout_session_id', $this->_table)) {
			return $this->db->select('ut.*, t.title as tryout_title, ts.name as session_name')
				->from('user_tryouts ut')
				->join('tryout_sessions ts', 'ts.id = ut.tryout_session_id', 'left')
				->join('tryouts t', 't.id = ts.tryout_id', 'left')
				->where('ut.user_id', $user_id)
				->order_by('ut.created_at', 'desc')
				->get()
				->result();
		} else {
			// Jika kolom tidak ada, gunakan fungsi lama
			return $this->getHistoryByUser($user_id);
		}
	}

	public function getLeaderboardBySession($session_id, $limit = 10)
	{
		// Cek apakah kolom tryout_session_id ada di tabel
		if ($this->columnExists('tryout_session_id', $this->_table)) {
			return $this->db->select('u.nama_lengkap, ut.total_score')
				->from('user_tryouts ut')
				->join('user u', 'u.id = ut.user_id')
				->where('ut.tryout_session_id', $session_id)
				->where('ut.status', 'completed')
				->order_by('ut.total_score', 'desc')
				->limit($limit)
				->get()
				->result();
		} else {
			// Jika kolom tidak ada, kembalikan array kosong
			return [];
		}
	}
	
	public function getAverageScoreByClassWithSession($class_id, $session_id = null)
	{
		// Cek apakah kolom tryout_session_id ada di tabel
		if (!$this->columnExists('tryout_session_id', $this->_table)) {
			return 0; // Kembalikan 0 jika kolom tidak ada
		}

		// Ambil semua user_id dari kelas
		$this->load->model('StudentClassModel');
		$students = $this->StudentClassModel->getStudentsByClass($class_id);
		if (empty($students)) return 0;

		$user_ids = array_column($students, 'id');
		
		$this->db->select_avg('total_score', 'avg_score')
			->where_in('user_id', $user_ids)
			->where('status', 'completed');
			
		if($session_id) {
			$this->db->where('tryout_session_id', $session_id);
		}
		
		$result = $this->db->get('user_tryouts')->row();
		return $result ? round($result->avg_score, 2) : 0;
	}

	public function countByStudentsWithSession($student_ids, $status = null, $session_id = null)
	{
		if (empty($student_ids)) return 0;
		
		// Cek apakah kolom tryout_session_id ada di tabel
		if (!$this->columnExists('tryout_session_id', $this->_table)) {
			// Jika kolom tidak ada, abaikan parameter session_id
			return $this->countByStudents($student_ids, $status);
		}
		
		$this->db->where_in('user_id', $student_ids);
		if ($status) {
			$this->db->where('status', $status);
		}
		if ($session_id) {
			$this->db->where('tryout_session_id', $session_id);
		}
		return $this->db->count_all_results('user_tryouts');
	}

	public function getRecentByStudentsWithSession($student_ids, $limit = 10)
	{
		if (empty($student_ids)) {
			return [];
		}
		
		// Cek apakah kolom tryout_session_id ada di tabel
		if ($this->columnExists('tryout_session_id', $this->_table)) {
			return $this->db->select('ut.*, u.nama_lengkap, t.title as tryout_title, ts.name as session_name')
				->from('user_tryouts ut')
				->join('tryout_sessions ts', 'ts.id = ut.tryout_session_id', 'left')
				->join('tryouts t', 't.id = ts.tryout_id', 'left')
				->join('user u', 'u.id = ut.user_id')
				->where_in('ut.user_id', $student_ids)
				->order_by('ut.created_at', 'desc')
				->limit($limit)
				->get()
				->result();
		} else {
			// Jika kolom tidak ada, gunakan fungsi lama
			return $this->getRecentByStudents($student_ids, $limit);
		}
	}
	
	/**
	 * Calculate score based on session scoring method
	 */
	public function calculateScore($user_tryout_id, $session_id)
	{
		// Cek apakah kolom tryout_session_id ada di tabel
		if (!$this->columnExists('tryout_session_id', $this->_table)) {
			return 0; // Tidak bisa menghitung jika kolom tidak ada
		}
		
		$this->load->model([
			'TryoutSessionModel',
			'TryoutQuestionModel',
			'UserAnswerModel',
			'QuestionModel'
		]);
		
		// Get session info to determine scoring method
		$session = $this->TryoutSessionModel->getDetail(['id' => $session_id]);
		if (!$session) return 0;
		
		// Get all questions for this session
		$tryout_questions = $this->TryoutQuestionModel->getAll(['tryout_session_id' => $session_id]);
		
		if ($session->scoring_method === 'correct_incorrect') {
			// Method 1: Correct/Incorrect (1 point for correct answer, 0 for incorrect)
			$total_score = 0;
			
			foreach ($tryout_questions as $tq) {
				$question = $this->QuestionModel->getDetail(['id' => $tq->question_id]);
				$user_answer = $this->UserAnswerModel->getAnswer($user_tryout_id, $tq->question_id);
				
				if ($user_answer && $user_answer->answer === $question->correct_option) {
					$total_score += 1;
				}
			}
			
			return $total_score;
		} else {
			// Method 2: Points per question (use points specified for each question)
			$total_score = 0;
			
			foreach ($tryout_questions as $tq) {
				$question = $this->QuestionModel->getDetail(['id' => $tq->question_id]);
				$user_answer = $this->UserAnswerModel->getAnswer($user_tryout_id, $tq->question_id);
				
				if ($user_answer && $user_answer->answer === $question->correct_option) {
					// Add points for this question (default to 1 if not set)
					$total_score += floatval($tq->points);
				}
			}
			
			return $total_score;
		}
	}
	
	/**
	 * Count correct answers for a user tryout
	 */
	public function countCorrectAnswers($user_tryout_id, $session_id)
	{
		// Cek apakah kolom tryout_session_id ada di tabel
		if (!$this->columnExists('tryout_session_id', $this->_table)) {
			return 0; // Tidak bisa menghitung jika kolom tidak ada
		}
		
		$this->load->model([
			'TryoutSessionModel',
			'TryoutQuestionModel',
			'UserAnswerModel',
			'QuestionModel'
		]);
		
		// Get session info
		$session = $this->TryoutSessionModel->getDetail(['id' => $session_id]);
		if (!$session) return 0;
		
		// Get all questions for this session
		$tryout_questions = $this->TryoutQuestionModel->getAll(['tryout_session_id' => $session_id]);
		
		$correct_count = 0;
		
		foreach ($tryout_questions as $tq) {
			$question = $this->QuestionModel->getDetail(['id' => $tq->question_id]);
			$user_answer = $this->UserAnswerModel->getAnswer($user_tryout_id, $tq->question_id);
			
			if ($user_answer && $user_answer->answer === $question->correct_option) {
				$correct_count++;
			}
		}
		
		return $correct_count;
	}
	
	public function getById($id)
	{
		return $this->getDetail(['id' => $id]);
	}
	
	/**
	 * Memeriksa apakah kolom tertentu ada di tabel
	 */
	public function columnExists($column, $table)
	{
		return $this->db->field_exists($column, $table);
	}
}	