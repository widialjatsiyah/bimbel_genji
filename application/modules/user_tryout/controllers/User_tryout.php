<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class User_tryout extends AppBackend
{
	function __construct()
	{
		parent::__construct();
		$this->load->model([
			'UserTryoutModel',
			'TryoutSessionModel',
			'TryoutQuestionModel',
			'QuestionModel',
			'UserAnswerModel',
			'EssayAnswerModel',
			'BookmarkModel',
		]);
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = [
			'app' => $this->app(),
			'main_js' => $this->load_main_js('user_tryout'),
			'title' => 'Daftar Try Out',
		];

		$this->template->set('title', $data['title'] . ' | ' . $data['app']->app_name, TRUE);
		$this->template->load_view('index', $data, TRUE);
		$this->template->render();
	}

	public function play($id)
	{
		// Ambil data user_tryout
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $id]);

		if (!$user_tryout) {
			show_404('User tryout tidak ditemukan');
			return;
		}

		// Dapatkan sesi tryout
		if ($user_tryout->tryout_session_id) {
			$session = $this->TryoutSessionModel->getDetail(['id' => $user_tryout->tryout_session_id]);
		} else {
			$this->load->model('TryoutModel');
			$tryout = $this->TryoutModel->getDetail(['id' => $user_tryout->tryout_id]);

			if (!$tryout) {
				$this->session->set_flashdata('error', 'Tryout tidak ditemukan');
				redirect('tryout_list');
				return;
			}

			$session = $this->TryoutSessionModel->getFirstSession($tryout->id);
		}

		if (!$session) {
			$this->session->set_flashdata('error', 'Sesi tryout tidak ditemukan');
			redirect('tryout_list');
			return;
		}

		// Ambil soal-soal untuk sesi ini
		$questions = $this->TryoutQuestionModel->getQuestionsBySession($session->id, $session->is_random);

		if (empty($questions)) {
			$this->session->set_flashdata('error', 'Tidak ada soal dalam sesi ini.');
			redirect('tryout_list');
			return;
		}

		$total_questions = count($questions);

		// Ambil jawaban pengguna
		$answer_map = [];

		// Jawaban pilihan ganda
		$answers = $this->UserAnswerModel->getAnswers($user_tryout->id);
		foreach ($answers as $answer) {
			$answer_obj = (object)$answer;
			$answer_map[$answer_obj->question_id] = $answer_obj;
		}

		// Jawaban esai
		$essay_answers = $this->EssayAnswerModel->getAll(['user_tryout_id' => $user_tryout->id]);
		foreach ($essay_answers as $answer) {
			$answer_map[$answer->question_id] = $answer;
		}

		// Ambil bookmark pengguna
		$bookmarks = $this->BookmarkModel->getByUserAndTryout(
			$this->session->userdata('user')['id'],
			$user_tryout->id
		);

		// Buat map untuk bookmark
		$bookmark_map = [];
		foreach ($bookmarks as $bookmark) {
			$bookmark_map[$bookmark->question_id] = true;
		}

		// Siapkan data untuk view
		$data = [
			'app' => $this->app(),
			'main_js' => $this->load_main_js('user_tryout', [
				'user_tryout_id' => $user_tryout->id,
				'session_id' => $session->id,
				'total_questions' => $total_questions
			]),
			'title' => $session->name,
			'user_tryout' => $user_tryout,
			'session' => $session,
			'questions' => $questions,
			'total_questions' => $total_questions,
			'answer_map' => $answer_map,
			'bookmark_map' => $bookmark_map,
		];

		// Set title dan tampilkan view
		// $this->template->set('title', $data['title'] . ' | ' . $data['app']->app_name, TRUE);
		// $this->template->load_view('user_tryout/play', $data, TRUE);
		// $this->template->render();
		$this->load->view('play', $data);
	}

	public function toggle_bookmark()
	{
		$this->handle_ajax_request();

		$user_tryout_id = $this->input->post('user_tryout_id');
		$question_id = $this->input->post('question_id');
		$user_id = $this->session->userdata('user')['id'];

		// Validasi input
		if (!$user_tryout_id || !$question_id) {
			echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
			return;
		}

		$this->load->model('BookmarkModel');

		// Toggle bookmark
		$result = $this->BookmarkModel->toggleBookmark($user_id, $question_id, $user_tryout_id);
		echo json_encode($result);
	}

	public function submit_session($user_tryout_id, $session_id)
	{
		// Sebelum menghitung skor, pastikan semua jawaban essay telah dinilai
		$this->load->model('EssayAnswerModel');
		$this->db->where('user_tryout_id', $user_tryout_id);
		$essay_answers = $this->db->get('essay_answers')->result();

		foreach ($essay_answers as $answer) {
			// Hitung dan update skor otomatis untuk semua jawaban essay
			$this->EssayAnswerModel->calculateAndUpdateScore($user_tryout_id, $answer->question_id);
		}

		// Cek apakah kolom tryout_session_id ada di tabel
		if ($this->UserTryoutModel->columnExists('tryout_session_id', 'user_tryouts')) {
			// Hitung skor berdasarkan metode yang ditentukan di sesi
			$score = $this->UserTryoutModel->calculateScore($user_tryout_id, $session_id);

			// Simpan skor ke database
			$this->UserTryoutModel->completeTryout($user_tryout_id, $score);
		} else {
			// Jika kolom tidak ada, gunakan metode lama
			$this->UserTryoutModel->completeTryout($user_tryout_id);
		}

		redirect('user_tryout/result/' . $user_tryout_id);
	}

	public function result($id)
	{
		// Tampilkan hasil tryout
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $id]);

		// Cek apakah user_tryout memiliki tryout_session_id
		if ($user_tryout->tryout_session_id) {
			$session = $this->TryoutSessionModel->getDetail(['id' => $user_tryout->tryout_session_id]);
		} else {
			// Jika tidak ada tryout_session_id, maka kita perlu mengambil sesi pertama dari tryout
			$this->load->model('TryoutModel');
			$tryout = $this->TryoutModel->getDetail(['id' => $user_tryout->tryout_id]);
			$session = $this->TryoutSessionModel->getFirstSession($tryout->id);
		}

		if (!$user_tryout || !$session) {
			show_404();
			return;
		}

		// Hitung skor jika belum dihitung sebelumnya
		if ($user_tryout->total_score === null) {
			// Cek apakah kolom tryout_session_id ada di tabel
			if ($this->UserTryoutModel->columnExists('tryout_session_id', 'user_tryouts')) {
				$score = $this->UserTryoutModel->calculateScore($user_tryout->id, $user_tryout->tryout_session_id);
				$this->UserTryoutModel->completeTryout($user_tryout->id, $score);

				// Ambil ulang data untuk mendapatkan skor terbaru
				$user_tryout = $this->UserTryoutModel->getDetail(['id' => $id]);
			} else {
				// Jika kolom tidak ada, gunakan metode lama
				$this->UserTryoutModel->completeTryout($user_tryout->id);
			}
		}

		// Dapatkan sesi berikutnya (jika ada)
		$next_session = $this->TryoutSessionModel->getNextSession($session->tryout_id, $session->id);

		// Muat file main.js.php yang benar
		$main_js = $this->load_main_js('user_tryout_result', [
			'user_tryout_id' => $user_tryout->id,
			'session_id' => $session->id
		]);

		$data = [
			'app' => $this->app(),
			'main_js' => $main_js,
			'title' => 'Hasil Try Out',
			'user_tryout' => $user_tryout,
			'session' => $session,
			'next_session' => $next_session
		];

		// $this->load->view('result', $data);
		$this->template->set('title', $data['title'] . ' | ' . $data['app']->app_name, TRUE);
		$this->template->load_view('result', $data, TRUE);
		$this->template->render();
	}

	public function ajax_save_answer()
	{
		$this->handle_ajax_request();

		$user_tryout_id = $this->input->post('user_tryout_id');
		$question_id = $this->input->post('question_id');
		$answer = $this->input->post('answer');
		$is_unsure = $this->input->post('is_unsure') ?? 0;

		// Validasi input
		if (!$user_tryout_id || !$question_id || !$answer) {
			echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
			return;
		}

		// Simpan jawaban
		$result = $this->UserAnswerModel->saveAnswer($user_tryout_id, $question_id, $answer, $is_unsure);
		if($result) {
			$result = ['status' => true, 'message' => 'Jawaban disimpan'];
		} else {
			$result = ['status' => false, 'message' => 'Gagal menyimpan jawaban'];
		}
		echo json_encode($result);
	}

	public function recalculate_essay_scores($user_tryout_id = null)
	{
		$this->handle_ajax_request();

		if (!$user_tryout_id) {
			$user_tryout_id = $this->input->post('user_tryout_id');
		}

		if (!$user_tryout_id) {
			echo json_encode(['status' => false, 'message' => 'User tryout ID diperlukan']);
			return;
		}

		// Ambil semua jawaban essay untuk user_tryout ini
		$this->load->model('EssayAnswerModel');
		$this->db->where('user_tryout_id', $user_tryout_id);
		$essay_answers = $this->db->get('essay_answers')->result();

		$updated_count = 0;
		$error_count = 0;

		foreach ($essay_answers as $answer) {
			// Hitung dan update skor otomatis
			$result = $this->EssayAnswerModel->calculateAndUpdateScore($user_tryout_id, $answer->question_id);
			if ($result['status']) {
				$updated_count++;
			} else {
				$error_count++;
			}
		}

		// Hitung ulang total skor untuk user_tryout ini
		$this->load->model('UserTryoutModel');
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $user_tryout_id]);
		$new_total_score = $this->UserTryoutModel->calculateScore($user_tryout_id, $user_tryout->tryout_session_id);

		// Update total skor di tabel user_tryouts
		$this->db->where('id', $user_tryout_id);
		$this->db->update('user_tryouts', ['total_score' => $new_total_score]);

		echo json_encode([
			'status' => true,
			'message' => "$updated_count jawaban essay berhasil diperbarui, $error_count gagal."
		]);
	}

	public function ajax_calculate_essay_score()
	{
		$this->handle_ajax_request();

		$user_tryout_id = $this->input->post('user_tryout_id');
		$question_id = $this->input->post('question_id');

		// Validasi input
		if (!$user_tryout_id || !$question_id) {
			echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
			return;
		}

		// Load EssayAnswerModel
		$this->load->model('EssayAnswerModel');

		// Hitung dan update skor otomatis
		$result = $this->EssayAnswerModel->calculateAndUpdateScore($user_tryout_id, $question_id);

		echo json_encode($result);
	}

	public function ajax_save_essay_answer()
	{
		$this->handle_ajax_request();

		$user_tryout_id = $this->input->post('user_tryout_id');
		$question_id = $this->input->post('question_id');
		$answer_text = $this->input->post('answer_text');
		$is_unsure = $this->input->post('is_unsure') ?? 0;

		// Validasi input
		if (!$user_tryout_id || !$question_id) {
			echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
			return;
		}

		// Cek apakah sudah ada jawaban sebelumnya
		$existing_answer = $this->EssayAnswerModel->getByUserTryoutAndQuestion($user_tryout_id, $question_id);

		if ($existing_answer) {
			// Update jawaban yang sudah ada
			$this->db->where([
				'user_tryout_id' => $user_tryout_id,
				'question_id' => $question_id
			]);
			$this->db->update('essay_answers', [
				'answer_text' => $answer_text,
				'is_unsure' => $is_unsure,
				'updated_at' => date('Y-m-d H:i:s')
			]);

			$result = ['status' => true, 'message' => 'Jawaban esai diperbarui'];
		} else {
			// Simpan jawaban baru
			$this->db->insert('essay_answers', [
				'user_tryout_id' => $user_tryout_id,
				'question_id' => $question_id,
				'answer_text' => $answer_text,
				'is_unsure' => $is_unsure,
				'created_at' => date('Y-m-d H:i:s')
			]);

			$result = ['status' => true, 'message' => 'Jawaban esai disimpan'];
		}

		// Secara otomatis hitung skor berdasarkan kata kunci
		$this->EssayAnswerModel->calculateAndUpdateScore($user_tryout_id, $question_id);

		echo json_encode($result);
	}

	public function ajax_mark_unsure()
	{
		$this->handle_ajax_request();

		$user_tryout_id = $this->input->post('user_tryout_id');
		$question_id = $this->input->post('question_id');
		$is_unsure = $this->input->post('is_unsure');

		// Validasi input
		if (!$user_tryout_id || !$question_id || !isset($is_unsure)) {
			echo json_encode(['status' => false, 'message' => 'Parameter tidak lengkap']);
			return;
		}

		// Update status unsure di tabel jawaban
		$this->db->where([
			'user_tryout_id' => $user_tryout_id,
			'question_id' => $question_id
		]);
		$this->db->update('user_answers', ['is_unsure' => $is_unsure]);

		echo json_encode(['status' => true, 'message' => 'Status ragu dirubah']);
	}

	public function start($session_id)
	{
		$user_id = $this->session->userdata('user')['id'];

		// Pastikan sesi ini adalah bagian dari tryout yang dapat diakses oleh pengguna
		$session = $this->TryoutSessionModel->getDetail(['id' => $session_id]);
		if (!$session) {
			show_404();
			return;
		}

		// Ambil tryout untuk memastikan pengguna memiliki akses
		$this->load->model('TryoutModel');
		$tryout = $this->TryoutModel->getDetail(['id' => $session->tryout_id]);

		$this->load->model('UserPackageModel');
		$accessible_tryouts = $this->UserPackageModel->getAccessibleItems($user_id, 'tryout');

		if (!$tryout || !in_array($tryout->id, $accessible_tryouts)) {
			$this->session->set_flashdata('error', 'Anda tidak memiliki akses ke tryout ini.');
			redirect('tryout_list');
			return;
		}

		// Cek apakah kolom tryout_session_id ada di tabel
		if ($this->UserTryoutModel->columnExists('tryout_session_id', 'user_tryouts')) {
			// Mulai sesi
			$user_tryout_id = $this->UserTryoutModel->startTryoutWithSession($user_id, $session_id);
		} else {
			// Jika kolom tidak ada, kembali ke metode lama
			$user_tryout_id = $this->UserTryoutModel->startTryout($user_id, $tryout->id);
		}

		// Jika user_tryout_id adalah 0, artinya gagal membuat entri
		if ($user_tryout_id == 0) {
			$this->session->set_flashdata('error', 'Gagal memulai tryout. Silakan coba lagi.');
			redirect('tryout_list');
			return;
		}

		redirect('user_tryout/play/' . $user_tryout_id);
	}

	public function start_next($prev_user_tryout_id, $next_session_id)
	{
		$user_id = $this->session->userdata('user')['id'];

		// Dapatkan informasi sesi sebelumnya
		$prev_tryout = $this->UserTryoutModel->getDetail(['id' => $prev_user_tryout_id]);

		// Cek apakah kolom tryout_session_id ada
		if ($prev_tryout->tryout_session_id) {
			$prev_session = $this->TryoutSessionModel->getDetail(['id' => $prev_tryout->tryout_session_id]);
		} else {
			// Jika tidak ada tryout_session_id, maka kita perlu mengambil sesi pertama dari tryout
			$this->load->model('TryoutModel');
			$tryout = $this->TryoutModel->getDetail(['id' => $prev_tryout->tryout_id]);
			$prev_session = $this->TryoutSessionModel->getFirstSession($tryout->id);
		}

		$next_session = $this->TryoutSessionModel->getDetail(['id' => $next_session_id]);

		// Pastikan sesi berikutnya adalah bagian dari tryout yang sama dan merupakan sesi berikutnya secara urutan
		if ($prev_session->tryout_id != $next_session->tryout_id) {
			$this->session->set_flashdata('error', 'Akses tidak sah.');
			redirect('tryout_list');
			return;
		}

		// Dapatkan sesi berikutnya yang seharusnya berdasarkan urutan
		$expected_next_session = $this->TryoutSessionModel->getNextSession($prev_session->tryout_id, $prev_session->id);

		if (!$expected_next_session || $expected_next_session->id != $next_session_id) {
			$this->session->set_flashdata('error', 'Akses tidak sah.');
			redirect('tryout_list');
			return;
		}

		// Cek apakah kolom tryout_session_id ada di tabel
		if ($this->UserTryoutModel->columnExists('tryout_session_id', 'user_tryouts')) {
			// Mulai sesi berikutnya
			$user_tryout_id = $this->UserTryoutModel->startTryoutWithSession($user_id, $next_session_id);
		} else {
			// Jika kolom tidak ada, kembali ke metode lama
			$user_tryout_id = $this->UserTryoutModel->startTryout($user_id, $next_session_id);
		}

		// Jika user_tryout_id adalah 0, artinya gagal membuat entri
		if ($user_tryout_id == 0) {
			$this->session->set_flashdata('error', 'Gagal memulai tryout. Silakan coba lagi.');
			redirect('tryout_list');
			return;
		}

		redirect('user_tryout/play/' . $user_tryout_id);
	}

	public function export_pdf($id)
	{
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $id]);

		if (!$user_tryout) {
			show_404();
			return;
		}

		// Dapatkan session berdasarkan tryout_session_id
		if ($user_tryout->tryout_session_id) {
			$session = $this->TryoutSessionModel->getDetail(['id' => $user_tryout->tryout_session_id]);
		} else {
			$this->load->model('TryoutModel');
			$tryout = $this->TryoutModel->getDetail(['id' => $user_tryout->tryout_id]);
			$session = $this->TryoutSessionModel->getFirstSession($tryout->id);
		}

		// Hitung ulang skor jika belum pernah dihitung atau jika ini adalah sesi tryout
		if (($user_tryout->tryout_session_id && $user_tryout->total_score === null) ||
			($user_tryout->tryout_session_id && $user_tryout->total_score == 0)
		) {

			$new_total_score = $this->UserTryoutModel->calculateScore($user_tryout->id, $session->id);

			// Update total skor di tabel user_tryouts
			$this->db->where('id', $id);
			$this->db->update('user_tryouts', ['total_score' => $new_total_score]);

			// Ambil ulang data untuk mendapatkan skor terbaru
			$user_tryout = $this->UserTryoutModel->getDetail(['id' => $id]);
		}

		// Ambil soal-soal untuk sesi ini
		$questions = $this->TryoutQuestionModel->getQuestionsBySession($session->id);

		// Ambil jawaban yang diberikan oleh pengguna
		$answers = $this->UserAnswerModel->getAnswers($user_tryout->id);
		$answer_map = [];
		foreach ($answers as $answer) {
			$answer_obj = (object)$answer;
			$answer_map[$answer_obj->question_id] = $answer_obj;
		}

		$answer_map_essay = [];
		// Ambil jawaban essay dan gabungkan ke answer_map
		$essay_answers = $this->EssayAnswerModel->getAll(['user_tryout_id' => $user_tryout->id]);
		foreach ($essay_answers as $answer) {
			$answer_map_essay[$answer->question_id] = $answer;
		}

		// Hitung jawaban benar menggunakan fungsi dari model
		$correct_count = $this->UserTryoutModel->countCorrectAnswers($user_tryout->id, $session->id);

		// Siapkan data untuk PDF
		$data = [
			'user_tryout' => $user_tryout,
			'session' => $session,
			'questions' => $questions,
			'answers' => $answer_map,
			'answers_essay' => $answer_map_essay,
			'correct_count' => $correct_count,
		];

		// Buat HTML untuk PDF
		$html = $this->load->view('user_tryout/export_pdf_html', $data, true);

		// Generate PDF menggunakan fungsi dari AppBackend
		$filename = 'hasil_tryout_' . $session->name . '_' . date('Y-m-d') . '.pdf';
		$this->generatePDF($html, $filename);
	}

	public function resume($id)
	{
		// Ambil user_tryout berdasarkan ID
		$user_tryout = $this->UserTryoutModel->getDetail(['id' => $id]);

		// Pastikan user_tryout ditemukan dan milik pengguna saat ini
		if (!$user_tryout || $user_tryout->user_id != $this->session->userdata('user')['id']) {
			show_404();
			return;
		}

		// Jika status sudah completed, arahkan ke hasil
		if ($user_tryout->status === 'completed') {
			redirect('user_tryout/result/' . $id);
			return;
		}

		// Jika status masih in_progress, arahkan ke halaman play
		redirect('user_tryout/play/' . $id);
	}
}
