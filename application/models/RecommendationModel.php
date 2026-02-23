<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RecommendationModel extends CI_Model
{
	private $_table = 'recommendations';

	public function getUnreadByUser($user_id, $limit = null)
	{
		$this->db->where('user_id', $user_id)
			->where('is_read', 0)
			->order_by('created_at', 'desc');
		if ($limit) $this->db->limit($limit);
		return $this->db->get($this->_table)->result();
	}

	public function getAllByUser($user_id, $limit = null)
	{
		$this->db->where('user_id', $user_id)
			->order_by('created_at', 'desc');
		if ($limit) $this->db->limit($limit);
		return $this->db->get($this->_table)->result();
	}

	public function markAsRead($id)
	{
		$this->db->where('id', $id)->update($this->_table, ['is_read' => 1]);
	}

	public function markAllAsRead($user_id)
	{
		$this->db->where('user_id', $user_id)->update($this->_table, ['is_read' => 1]);
	}

	public function insertRecommendation($user_id, $text, $type = 'latihan')
	{
		$data = [
			'user_id' => $user_id,
			'recommendation_text' => $text,
			'type' => $type,
			'created_at' => date('Y-m-d H:i:s')
		];
		$this->db->insert($this->_table, $data);
	}

	/**
	 * Generate rekomendasi otomatis berdasarkan data siswa
	 * @param int $user_id
	 * @param string $source Sumber pemicu: 'tryout', 'daily', 'manual'
	 */
	public function generateForUser($user_id, $source = 'tryout')
	{
		// Load model yang diperlukan
		$this->load->model([
			'UserTryoutModel',
			'SessionResultModel',
			'StudentProgressModel',
			'DailyChecklistModel'
		]);

		// Ambil data terbaru siswa
		$latest_tryout = $this->UserTryoutModel->getLatestByUser($user_id);
		$progress = $this->StudentProgressModel->getLatest($user_id);
		$checklist = $this->DailyChecklistModel->getRecent($user_id, 7); // 7 hari terakhir

		$recommendations = [];

		// 1. Rekomendasi berdasarkan hasil try out (jika ada)
		if ($latest_tryout && $latest_tryout->status == 'completed') {
			// Ambil hasil per sesi
			$session_results = $this->SessionResultModel->getByUserTryout($latest_tryout->id);
			foreach ($session_results as $session) {
				if ($session->score < 60) {
					$recommendations[] = [
						'text' => "Perbanyak latihan soal untuk sesi {$session->session_name} karena skor Anda masih di bawah 60.",
						'type' => 'latihan'
					];
				}
			}

			// Jika skor total turun drastis
			$previous = $this->UserTryoutModel->getPreviousTryout($user_id, $latest_tryout->id);
			if ($previous && ($previous->total_score - $latest_tryout->total_score) > 20) {
				$recommendations[] = [
					'text' => "Skor try out Anda turun cukup signifikan. Evaluasi kembali persiapan dan perbanyak latihan soal.",
					'type' => 'motivasi'
				];
			}
		}

		// 2. Rekomendasi berdasarkan konsistensi belajar (dari checklist)
		if (!empty($checklist)) {
			$total_ibadah = 0;
			$total_belajar = 0;
			foreach ($checklist as $c) {
				$data = json_decode($c->checklist_data);
				$total_ibadah += ($data->shalat_subuh ?? 0) + ($data->shalat_dzuhur ?? 0) + ($data->shalat_ashar ?? 0) + ($data->shalat_maghrib ?? 0) + ($data->shalat_isya ?? 0) + ($data->tilawah ?? 0);
				$total_belajar += $data->belajar_menit ?? 0;
			}
			$avg_ibadah = $total_ibadah / count($checklist);
			$avg_belajar = $total_belajar / count($checklist);

			if ($avg_ibadah < 3) {
				$recommendations[] = [
					'text' => "Konsistensi ibadah Anda perlu ditingkatkan. Luangkan waktu untuk shalat tepat waktu dan tilawah setiap hari.",
					'type' => 'spiritual'
				];
			}
			if ($avg_belajar < 60) {
				$recommendations[] = [
					'text' => "Rata-rata waktu belajar Anda kurang dari 1 jam per hari. Targetkan minimal 2 jam sehari untuk hasil optimal.",
					'type' => 'motivasi'
				];
			}
		}

		// 3. Rekomendasi berdasarkan status kesiapan dari student_progress
		if ($progress) {
			if ($progress->status_kesiapan == 'Perlu Pendampingan Intensif') {
				$recommendations[] = [
					'text' => "Status kesiapan Anda masih merah. Segera hubungi tutor untuk pendampingan intensif.",
					'type' => 'remedial'
				];
			} elseif ($progress->status_kesiapan == 'Perlu Penguatan') {
				$recommendations[] = [
					'text' => "Anda perlu penguatan di beberapa area. Fokuslah pada rekomendasi yang diberikan dan perbanyak latihan soal.",
					'type' => 'latihan'
				];
			}
		}

		// 4. Simpan rekomendasi ke database
		foreach ($recommendations as $rec) {
			// Cek apakah rekomendasi serupa sudah ada dalam 3 hari terakhir? (opsional)
			$this->insertRecommendation($user_id, $rec['text'], $rec['type']);
		}

		return count($recommendations);
	}

	/**
	 * Generate rekomendasi untuk semua siswa (dijalankan oleh cron job)
	 */
	public function generateForAllStudents()
	{
		$this->load->model('UserModel');
		$students = $this->UserModel->getAll(['role' => 'student']);
		$total = 0;
		foreach ($students as $s) {
			$total += $this->generateForUser($s->id, 'cron');
		}
		return $total;
	}

	public function getRecentForTeacher($teacher_id, $limit = 10)
	{
		// Ambil semua kelas yang diajar guru
		$this->load->model('ClassModel');
		$classes = $this->ClassModel->getByTeacher($teacher_id);
		if (empty($classes)) return [];

		$class_ids = array_column($classes, 'id');

		// Ambil semua siswa di kelas tersebut
		$this->load->model('StudentClassModel');
		$this->db->select('student_id')
			->from('student_class')
			->where_in('class_id', $class_ids);
		$student_query = $this->db->get();
		$student_ids = array_column($student_query->result(), 'student_id');

		if (empty($student_ids)) return [];

		$this->db->select('r.*, u.nama_lengkap')
			->from('recommendations r')
			->join('user u', 'u.id = r.user_id')
			->where_in('r.user_id', $student_ids)
			->order_by('r.created_at', 'desc')
			->limit($limit);
		return $this->db->get()->result();
	}

	public function getStudentsByClass($class_id)
	{
		return $this->db->select('u.id, u.nama_lengkap, u.email, u.username')
			->from('student_class sc')
			->join('user u', 'u.id = sc.student_id')
			->where('sc.class_id', $class_id)
			->order_by('u.nama_lengkap', 'asc')
			->get()
			->result();
	}

	public function getRecentByStudents($student_ids, $limit = 10)
	{
		if (empty($student_ids)) {
			return [];
		}
		return $this->db->select('r.*, u.nama_lengkap')
			->from('recommendations r')
			->join('user u', 'u.id = r.user_id')
			->where_in('r.user_id', $student_ids)
			->order_by('r.created_at', 'desc')
			->limit($limit)
			->get()
			->result();
	}

}
