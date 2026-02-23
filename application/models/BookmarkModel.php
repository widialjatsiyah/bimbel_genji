<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BookmarkModel extends CI_Model
{
    private $_table = 'bookmarks';

    public function getByUser($user_id)
    {
        return $this->db->select('b.*, q.question_text')
                        ->from($this->_table . ' b')
                        ->join('questions q', 'q.id = b.question_id')
                        ->where('b.user_id', $user_id)
                        ->order_by('b.created_at', 'desc')
                        ->get()
                        ->result();
    }

    public function isBookmarked($user_id, $question_id)
    {
        return $this->db->where('user_id', $user_id)
                        ->where('question_id', $question_id)
                        ->count_all_results($this->_table) > 0;
    }

    public function addBookmark($user_id, $question_id)
    {
        $data = [
            'user_id' => $user_id,
            'question_id' => $question_id
        ];
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    public function removeBookmark($user_id, $question_id)
    {
        $this->db->where('user_id', $user_id)
                 ->where('question_id', $question_id)
                 ->delete($this->_table);
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete($this->_table);
    }
}
