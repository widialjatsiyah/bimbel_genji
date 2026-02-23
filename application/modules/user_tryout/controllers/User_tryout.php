<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_tryout extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		// Pastikan user sudah login
		if (!$this->session->userdata('user')) {
			redirect('auth/login');
		}
		$this->load->model([
			'TryoutModel',
			'TryoutSessionModel',
			'TryoutQuestionModel',
			'UserTryoutModel',
			'UserAnswerModel',
			'SessionResultModel',
			'QuestionModel'
		]);
		$this->load->helper('form');
	}

	public function start($tryout_id)
	{
		$user_id = $this->session->userdata('user')['id'];
		// Cek apakah sudah ada tryout yang sedang berjalan
		$active = $this->UserTryoutModel->getActiveUserTryout($user_id, $tryout_id);
		if (!$active) {
			// Buat record baru
			$user_tryout_id = $this->UserTryoutModel->startTryout($user_id, $tryout_id);
		} else {
			$user_tryout_id = $active->id;
		}

		// Ambil sesi pertama dari tryout ini
		$first_session = $this->TryoutSessionModel->getFirstSession($tryout_id);
		if (!$first_session) {
			show_error('Try out ini belum memiliki sesi.');
		}

		// Redirect ke halaman sesi pertama
		redirect("user_tryout/session/{$user_tryout_id}/{$first_session->id}");
	}

	public function session($user_tryout_id, $session_id)
	{
		$user_id = $this->session->userdata('user')['id'];
		// Validasi kepemilikan user_tryout
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $user_tryout_id, 'user_id' => $user_id]);
		if (!$user_tryout) {
			show_error('Akses ditolak.');
		}

		// Ambil data sesi
		$session = $this->TryoutSessionModel->getDetail(['id' => $session_id]);
		if (!$session) {
			show_error('Sesi tidak ditemukan.');
		}

		// Ambil daftar soal dalam sesi ini (urut)
		$questions = $this->TryoutQuestionModel->getQuestionsBySession($session_id,true);
		if (empty($questions)) {
			show_error('Sesi ini belum memiliki soal.');
		}

		// Ambil jawaban yang sudah disimpan user untuk sesi ini
		$answers = $this->UserAnswerModel->getAnswersByUserTryoutAndSession($user_tryout_id, $session_id);
		$answer_map = [];
		foreach ($answers as $ans) {
			$answer_map[$ans['question_id']] = $ans;
		}

		$data = [
			'user_tryout' => $user_tryout,
			'session' => $session,
			'questions' => $questions,
			'answer_map' => $answer_map,
			'total_questions' => count($questions)
		];

		$this->load->view('header', ['title' => $session->name]);
		$this->load->view('play', $data);
		$this->load->view('footer');
	}

	public function ajax_save_answer()
	{
		$this->handle_ajax_request();
		$user_tryout_id = $this->input->post('user_tryout_id');
		$question_id = $this->input->post('question_id');
		$answer = $this->input->post('answer');
		$is_unsure = $this->input->post('is_unsure') ? 1 : 0;

		// Validasi kepemilikan
		$user_id = $this->session->userdata('user')['id'];
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $user_tryout_id, 'user_id' => $user_id]);
		if (!$user_tryout) {
			echo json_encode(['status' => false, 'data' => 'Akses ditolak']);
			return;
		}

		$this->UserAnswerModel->saveAnswer($user_tryout_id, $question_id, $answer, $is_unsure);
		echo json_encode(['status' => true]);
	}

	public function ajax_mark_unsure()
	{
		$this->handle_ajax_request();
		$user_tryout_id = $this->input->post('user_tryout_id');
		$question_id = $this->input->post('question_id');
		$is_unsure = $this->input->post('is_unsure') ? 1 : 0;

		$user_id = $this->session->userdata('user')['id'];
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $user_tryout_id, 'user_id' => $user_id]);
		if (!$user_tryout) {
			echo json_encode(['status' => false, 'data' => 'Akses ditolak']);
			return;
		}

		$this->UserAnswerModel->markUnsure($user_tryout_id, $question_id, $is_unsure);
		echo json_encode(['status' => true]);
	}

	public function submit_session($user_tryout_id, $session_id)
	{
		$user_id = $this->session->userdata('user')['id'];
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $user_tryout_id, 'user_id' => $user_id]);
		if (!$user_tryout) {
			show_error('Akses ditolak.');
		}

		// Hitung hasil sesi
		$questions = $this->TryoutQuestionModel->getQuestionsBySession($session_id);
		$answers = $this->UserAnswerModel->getAnswersByUserTryoutAndSession($user_tryout_id, $session_id);
		$answer_map = [];
		foreach ($answers as $a) {
			$answer_map[$a['question_id']] = $a;
		}

		$correct = 0;
		$wrong = 0;
		$skipped = 0;
		foreach ($questions as $q) {
			$question = $this->QuestionModel->getDetail(['id' => $q->question_id]);
			if (isset($answer_map[$q->question_id])) {
				$ans = $answer_map[$q->question_id];
				if ($ans['answer'] === null) {
					$skipped++;
				} elseif ($ans['answer'] == $question->correct_option) {
					$correct++;
				} else {
					$wrong++;
				}
			} else {
				$skipped++;
			}
		}

		// Simpan hasil sesi
		$total = count($questions);
		$score = ($total > 0) ? round(($correct / $total) * 100, 2) : 0;
		$this->SessionResultModel->saveResult($user_tryout_id, $session_id, $correct, $wrong, $skipped, $score);

		// Cek apakah masih ada sesi berikutnya
		$next_session = $this->TryoutSessionModel->getNextSession($user_tryout->tryout_id, $session_id);
		if ($next_session) {
			// Redirect ke sesi berikutnya
			redirect("user_tryout/session/{$user_tryout_id}/{$next_session->id}");
		} else {
			// Semua sesi selesai, update user_tryout selesai
			$this->UserTryoutModel->completeTryout($user_tryout_id, $score); // score total? perlu dihitung dari semua sesi
			$total_score = $this->calculateTotalScore($user_tryout_id);
			$this->UserTryoutModel->completeTryout($user_tryout_id, $total_score);
			$this->updateRanking($user_tryout->tryout_id);
			// redirect("user_tryout/finish/{$user_tryout_id}");
			redirect("user_tryout/finish/{$user_tryout_id}");
		}
	}

	public function finish($user_tryout_id)
	{
		$user_id = $this->session->userdata('user')['id'];
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $user_tryout_id, 'user_id' => $user_id]);
		if (!$user_tryout) {
			show_error('Akses ditolak.');
		}

		// Ambil hasil semua sesi
		$results = $this->SessionResultModel->getByUserTryout($user_tryout_id);
		$data['results'] = $results;
		$data['user_tryout'] = $user_tryout;

		$this->load->view('header', ['title' => 'Try Out Selesai']);
		$this->load->view('finish', $data);
		$this->load->view('footer');
	}

	/**
	 * Hitung skor total dari semua sesi
	 */
	private function calculateTotalScore($user_tryout_id)
	{
		$this->load->model('SessionResultModel');
		$results = $this->SessionResultModel->getByUserTryout($user_tryout_id);
		if (empty($results)) return 0;

		$total = 0;
		foreach ($results as $r) {
			$total += $r->score;
		}
		// Rata-rata skor (atau bisa juga dijumlah, tergantung kebutuhan)
		return round($total / count($results), 2);
	}

	/**
	 * Update ranking nasional, sekolah, dan bimbel
	 */
	private function updateRanking($tryout_id)
	{
		// Ambil semua user_tryout untuk tryout ini dengan status completed, urut skor descending
		$this->db->select('ut.id, ut.user_id, ut.total_score, u.unit as school_id')
			->from('user_tryouts ut')
			->join('user u', 'u.id = ut.user_id')
			->where('ut.tryout_id', $tryout_id)
			->where('ut.status', 'completed')
			->order_by('ut.total_score', 'desc');
		$query = $this->db->get();
		$results = $query->result();

		// Ranking nasional
		$rank_national = 1;
		foreach ($results as $row) {
			$this->db->where('id', $row->id)
				->update('user_tryouts', ['ranking_national' => $rank_national]);
			$rank_national++;
		}

		// Ranking per sekolah
		$schools = [];
		foreach ($results as $row) {
			$schools[$row->school_id][] = $row;
		}
		foreach ($schools as $school_id => $students) {
			$rank_school = 1;
			foreach ($students as $s) {
				$this->db->where('id', $s->id)
					->update('user_tryouts', ['ranking_school' => $rank_school]);
				$rank_school++;
			}
		}

		// Jika ada bimbel (misal dari kolom sub_unit), bisa ditambahkan serupa
	}

	/**
	 * Ekspor hasil try out ke PDF
	 */
	public function export_pdf($user_tryout_id)
	{
		$user_id = $this->session->userdata('user')['id'];
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $user_tryout_id, 'user_id' => $user_id]);
		if (!$user_tryout) {
			show_error('Akses ditolak.');
		}

		// Load data lengkap
		$tryout = $this->TryoutModel->getDetail(['id' => $user_tryout->tryout_id]);
		$user = $this->db->where('id', $user_id)->get('user')->row();

		$this->load->model('TryoutSessionModel');
		$this->load->model('SessionResultModel');
		$this->load->model('TryoutQuestionModel');
		$this->load->model('QuestionModel');
		$this->load->model('UserAnswerModel');

		$sessions = $this->TryoutSessionModel->getByTryout($tryout->id);
		foreach ($sessions as $s) {
			$s->result = $this->SessionResultModel->getByUserTryoutAndSession($user_tryout_id, $s->id);
			$questions = $this->TryoutQuestionModel->getQuestionsBySession($s->id);
			foreach ($questions as $q) {
				$q->detail = $this->QuestionModel->getDetail(['id' => $q->question_id]);
				$q->user_answer = $this->UserAnswerModel->getAnswer($user_tryout_id, $q->question_id);
			}
			$s->questions = $questions;
		}

		$data = [
			'user_tryout' => $user_tryout,
			'tryout' => $tryout,
			'user' => $user,
			'sessions' => $sessions
		];

		// Load view PDF
		$html = $this->load->view('user_tryout/export_pdf', $data, true);

		// Gunakan MPDF
		require_once FCPATH . 'vendor/autoload.php'; // sudah di autoload oleh AppBackend, tapi pastikan
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4',
			'orientation' => 'P'
		]);
		$mpdf->WriteHTML($html);
		$mpdf->Output('hasil_tryout_' . $user_tryout_id . '.pdf', 'I'); // I = inline, D = download
		exit;
	}

	private function handle_ajax_request()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
	}
}
