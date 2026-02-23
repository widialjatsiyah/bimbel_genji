<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TopicModel extends CI_Model
{
    private $_table = 'topics';

    public function rules()
    {
        return array(
            [
                'field' => 'chapter_id',
                'label' => 'Bab',
                'rules' => 'required|integer'
            ],
            [
                'field' => 'name',
                'label' => 'Nama Topik',
                'rules' => 'required|trim|max_length[100]'
            ],
            [
                'field' => 'order_num',
                'label' => 'Urutan',
                'rules' => 'integer'
            ],
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

    public function insert()
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->chapter_id = $this->input->post('chapter_id');
            $this->name = $this->input->post('name');
            $this->order_num = $this->input->post('order_num') ?: null;
            $this->db->insert($this->_table, $this);
            $response = array('status' => true, 'data' => 'Data topik berhasil disimpan.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menyimpan data: ' . $th->getMessage());
        }
        return $response;
    }

    public function update($id)
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->chapter_id = $this->input->post('chapter_id');
            $this->name = $this->input->post('name');
            $this->order_num = $this->input->post('order_num') ?: null;
            $this->db->update($this->_table, $this, array('id' => $id));
            $response = array('status' => true, 'data' => 'Data topik berhasil diperbarui.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal memperbarui data: ' . $th->getMessage());
        }
        return $response;
    }

    public function delete($id)
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->db->delete($this->_table, array('id' => $id));
            $response = array('status' => true, 'data' => 'Data topik berhasil dihapus.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
        }
        return $response;
    }

    function clean_number($number)
    {
        return preg_replace('/[^0-9]/', '', $number);
    }
}
