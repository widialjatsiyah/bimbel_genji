<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BookmarkModel extends CI_Model
{
	private $_table = 'bookmarks';

	public function rules()
	{
		return array(
			[
				'field' => 'user_id',
				'label' => 'User',
				'rules' => 'required|integer'
			],
			[
				'field' => 'question_id',
				'label' => 'Soal',
				'rules' => 'required|integer'
			],
			[
				'field' => 'user_tryout_id',
				'label' => 'Tryout Pengguna',
				'rules' => 'required|integer'
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

	public function getByUserAndTryout($user_id, $user_tryout_id)
	{
		return $this->db->where('user_id', $user_id)
			->where('user_tryout_id', $user_tryout_id)
			->get($this->_table)
			->result();
	}

	public function isBookmarked($user_id, $question_id, $user_tryout_id)
	{
		$result = $this->db->where('user_id', $user_id)
			->where('question_id', $question_id)
			->where('user_tryout_id', $user_tryout_id)
			->get($this->_table)
			->row();
		return $result !== null;
	}

	public function toggleBookmark($user_id, $question_id, $user_tryout_id)
	{
		$bookmark = $this->getDetail([
			'user_id' => $user_id,
			'question_id' => $question_id,
			'user_tryout_id' => $user_tryout_id
		]);

		if ($bookmark) {
			// Hapus bookmark
			$this->db->where('id', $bookmark->id)->delete($this->_table);
			return ['status' => true, 'action' => 'removed', 'message' => 'Bookmark dihapus'];
		} else {
			// Tambah bookmark
			$data = [
				'user_id' => $user_id,
				'question_id' => $question_id,
				'user_tryout_id' => $user_tryout_id,
				'created_at' => date('Y-m-d H:i:s')
			];
			$this->db->insert($this->_table, $data);
			return ['status' => true, 'action' => 'added', 'message' => 'Bookmark ditambahkan'];
		}
	}

	public function insert()
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->user_id = $this->input->post('user_id');
			$this->question_id = $this->input->post('question_id');
			$this->user_tryout_id = $this->input->post('user_tryout_id');
			$this->db->insert($this->_table, $this);
			$response = array('status' => true, 'data' => 'Bookmark berhasil ditambahkan.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menambahkan bookmark: ' . $th->getMessage());
		}
		return $response;
	}

	public function delete($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->db->where('id', $id);
			$this->db->delete($this->_table);
			$response = array('status' => true, 'data' => 'Bookmark berhasil dihapus.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menghapus bookmark: ' . $th->getMessage());
		}
		return $response;
	}
}
