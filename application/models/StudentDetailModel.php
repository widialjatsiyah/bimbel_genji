<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StudentDetailModel extends CI_Model
{
    private $_table = 'student_details';

    public function getByUser($user_id)
    {
        return $this->db->where('user_id', $user_id)->get($this->_table)->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    public function update($user_id, $data)
    {
        $this->db->where('user_id', $user_id)->update($this->_table, $data);
    }

    public function delete($user_id)
    {
        $this->db->where('user_id', $user_id)->delete($this->_table);
    }
}
