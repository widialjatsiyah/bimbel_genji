<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Bookmark extends AppBackend
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['AppModel', 'BookmarkModel', 'QuestionModel', 'UserTryoutModel']);
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = [
			'app' => $this->app(),
			'main_js' => $this->load_main_js('bookmark'),
			'card_title' => 'Bookmark Saya',
			'title' => 'Bookmark Saya'
		];
		$this->template->set('title', $data['title'] . ' | ' . $data['app']->app_name, TRUE);
		$this->template->load_view('index', $data, TRUE);
		$this->template->render();
	}

	public function ajax_get_all()
	{
		$this->handle_ajax_request();
		$dtAjax_config = array(
			'select_column' => [
				'bookmarks.id',
				'bookmarks.created_at',
				'questions.question_text',
				'questions.id as question_id',
				'user_tryouts.tryout_id',
				'tryouts.title as tryout_name',
				'tryout_sessions.name as session_name',
			],
			'table_name' => 'bookmarks',
			'table_join' => [
				[
					'table_name' => 'questions',
					'expression' => 'questions.id = bookmarks.question_id',
					'type' => 'inner'
				],
				[
					'table_name' => 'user_tryouts',
					'expression' => 'user_tryouts.id = bookmarks.user_tryout_id',
					'type' => 'inner'
				],
				[
					'table_name' => 'tryouts',
					'expression' => 'tryouts.id = user_tryouts.tryout_id',
					'type' => 'inner'
				],
				[
					'table_name' => 'tryout_sessions',
					'expression' => 'tryout_sessions.id = user_tryouts.tryout_session_id',
					'type' => 'inner'
				],
			],
			'order_column' => 1,
			'order_column_dir' => 'asc',
		);
		$response = $this->AppModel->getData_dtAjax($dtAjax_config);
		echo json_encode($response);
	}
	public function ajax_get_all_old()
	{
		$this->handle_ajax_request();
		$user_id = $this->session->userdata('user')['id'];

		// Ambil semua bookmark milik pengguna
		$bookmarks = $this->BookmarkModel->getAll(['user_id' => $user_id]);

		// Format data untuk datatables
		$formatted_data = [];
		foreach ($bookmarks as $index => $bookmark) {
			$question = $this->QuestionModel->getDetail(['id' => $bookmark->question_id]);
			$user_tryout = $this->UserTryoutModel->getDetail(['id' => $bookmark->user_tryout_id]);

			$formatted_data[] = [
				'id' => $bookmark->id,
				'no' => $index + 1,
				'tryout_name' => $user_tryout->tryout_name ?? 'Tryout tidak ditemukan',
				'question_text' => strlen(strip_tags($question->question_text ?? '')) > 100 ?
					substr(strip_tags($question->question_text ?? ''), 0, 100) . '...' :
					strip_tags($question->question_text ?? ''),
				'created_at' => date('d M Y H:i', strtotime($bookmark->created_at)),
				'action' => '<div class="form-button">
                               <a href="' . base_url('user_tryout/play/' . $user_tryout->id . '?question=' . $question->id) . '" class="btn btn-sm btn-info" title="Lihat Soal">
                                   <i class="fas fa-eye"></i>
                               </a>
                               <button class="btn btn-sm btn-danger btn-remove" data-id="' . $bookmark->id . '" title="Hapus Bookmark">
                                   <i class="fas fa-trash"></i>
                               </button>
                             </div>'
			];
		}

		// Format respons untuk datatables
		$response = [
			'draw' => isset($_GET['draw']) ? intval($_GET['draw']) : 0,
			'recordsTotal' => count($formatted_data),
			'recordsFiltered' => count($formatted_data),
			'data' => $formatted_data
		];

		echo json_encode($response);
	}

	public function delete($id)
	{
		$this->handle_ajax_request();

		$result = $this->BookmarkModel->delete($id);
		echo json_encode($result);
	}

	public function toggle()
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

		// Toggle bookmark
		$result = $this->BookmarkModel->toggleBookmark($user_id, $question_id, $user_tryout_id);
		echo json_encode($result);
	}

	public function ajax_remove_by_id($id)
	{
		$this->handle_ajax_request();

		$user_id = $this->session->userdata('user')['id'];

		// Check if the bookmark belongs to the current user
		$bookmark = $this->BookmarkModel->getDetail(['id' => $id]);
		if (!$bookmark || $bookmark->user_id != $user_id) {
			echo json_encode(['status' => false, 'message' => 'Bookmark tidak ditemukan atau Anda tidak memiliki izin']);
			return;
		}

		$result = $this->BookmarkModel->delete($id);
		echo json_encode($result);
	}

	public function ajax_save($id = null)
	{
		$this->handle_ajax_request();

		// Ini hanya placeholder karena bookmark sekarang hanya bisa dihapus, tidak diedit
		echo json_encode(['status' => false, 'data' => 'Bookmark tidak dapat diedit melalui form ini']);
	}

	public function ajax_delete($id)
	{
		$this->handle_ajax_request();

		$result = $this->BookmarkModel->delete($id);
		echo json_encode($result);
	}

	public function question($question_id = null)
	{
		$question = $this->QuestionModel->getDetail(array('id' => $question_id));
		// var_dump($question); // Debug: Tampilkan detail pertanyaan di log server
		// die();
		if (!is_null($question)) {
			$agent = new Mobile_Detect;
			$data = array(
				'app' => $this->app(),
				'main_js' => $this->load_main_js('bookmarks'),
				'card_title' => 'Soal › Rincian',
				'controller' => $this,
				'is_mobile' => $agent->isMobile(),
				'question' => $question,
				'question_text_processed' => $this->process_youtube_links($question->video_explanation_url),
			);
			$this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
			$this->template->load_view('detail', $data, TRUE);
			$this->template->render();
		} else {
			show_404();
		};
	}
	
	/**
	 * Helper method to process YouTube links and convert them to embedded iframes
	 * @param string $content Content that may contain YouTube links
	 * @return string Content with YouTube links converted to iframes
	 */
	public function process_youtube_links($content)
	{
		// Pattern untuk mendeteksi URL YouTube
		$patterns = array(
			'/https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube(?:-nocookie)?\.com\S*[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:[\'"][^<>]*>|<\/a>))[?=&+%\w.-]*/i',
			'/\[youtube\](.*?)\[\/youtube\]/i', // Untuk format [youtube]URL[/youtube]
		);

		// Gantilah URL YouTube dengan iframe embed
		foreach ($patterns as $pattern) {
			$content = preg_replace_callback($pattern, function($matches) {
				$url = trim($matches[1]);
				
				// Jika ini adalah URL lengkap, ekstrak ID videonya
				if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
					parse_str(parse_url($url, PHP_URL_QUERY), $params);
					$video_id = $params['v'] ?? '';
					
					// Jika tidak ada 'v', mungkin ini format youtu.be
					if (empty($video_id)) {
						$path = parse_url($url, PHP_URL_PATH);
						$video_id = ltrim($path, '/');
					}
				} else {
					// Jika ini sudah berupa ID video
					$video_id = $url;
				}

				if (!empty($video_id)) {
					return '<div class="youtube-iframe-container">' .
						'<iframe  width="100%" height="300" src="https://www.youtube.com/embed/' . $video_id . 
						'" frameborder="0" allowfullscreen></iframe>' .
						'</div>';
				} else {
					return $matches[0]; // Kembalikan teks asli jika tidak dapat diproses
				}
			}, $content);
		}

		return $content;
	}
}
