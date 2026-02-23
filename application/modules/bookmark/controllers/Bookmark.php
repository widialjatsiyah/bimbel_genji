<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Bookmark extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            show_error('Akses ditolak', 403);
        }
        $this->load->model('BookmarkModel');
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];
        $bookmarks = $this->BookmarkModel->getByUser($user_id);

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('bookmark'),
            'card_title' => 'Soal Favorit Saya',
            'bookmarks' => $bookmarks
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_add()
    {
        $this->handle_ajax_request();
        $user_id = $this->session->userdata('user')['id'];
        $question_id = $this->input->post('question_id');

        if ($this->BookmarkModel->isBookmarked($user_id, $question_id)) {
            echo json_encode(['status' => false, 'data' => 'Soal sudah ada di bookmark.']);
            return;
        }

        $this->BookmarkModel->addBookmark($user_id, $question_id);
        echo json_encode(['status' => true, 'data' => 'Soal ditambahkan ke bookmark.']);
    }

    public function ajax_remove()
    {
        $this->handle_ajax_request();
        $user_id = $this->session->userdata('user')['id'];
        $question_id = $this->input->post('question_id');

        $this->BookmarkModel->removeBookmark($user_id, $question_id);
        echo json_encode(['status' => true, 'data' => 'Soal dihapus dari bookmark.']);
    }

    public function ajax_remove_by_id($id)
    {
        $this->handle_ajax_request();
        $this->BookmarkModel->delete($id);
        echo json_encode(['status' => true, 'data' => 'Bookmark dihapus.']);
    }
}
