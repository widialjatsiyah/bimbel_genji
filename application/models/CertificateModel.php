<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CertificateModel extends CI_Model
{
    private $_table = 'certificates';

    public function getByUser($user_id)
    {
        return $this->db->select('c.*, t.title as tryout_title')
                        ->from($this->_table . ' c')
                        ->join('tryouts t', 't.id = c.tryout_id', 'left')
                        ->where('c.user_id', $user_id)
                        ->order_by('c.issued_at', 'desc')
                        ->get()
                        ->result();
    }

    public function getDetail($id)
    {
        return $this->db->where('id', $id)->get($this->_table)->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }
}
