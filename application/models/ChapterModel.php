<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ChapterModel extends CI_Model
{
    private $_table = 'chapters';

    public function rules()
    {
        return array(
            [
                'field' => 'subject_id',
                'label' => 'Mata Pelajaran',
                'rules' => 'required|trim|integer'
            ],
            [
                'field' => 'name',
                'label' => 'Nama Bab',
                'rules' => 'required|trim|max_length[100]'
            ],
            [
                'field' => 'order_num',
                'label' => 'Urutan',
                'rules' => 'trim|integer'
            ],
        );
    }

    public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
    {
        $this->db->where($params);
        if (!is_null($orderField)) {
            $this->db->order_by($orderField, $orderBy);
        };
        // Join dengan subjects untuk menampilkan nama subject
        $this->db->select('chapters.*, subjects.name as subject_name');
        $this->db->join('subjects', 'subjects.id = chapters.subject_id', 'left');
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
            $this->subject_id = $this->input->post('subject_id');
            $this->name = $this->input->post('name');
            $this->order_num = $this->input->post('order_num') ?: 0;
            $this->db->insert($this->_table, $this);
            $response = array('status' => true, 'data' => 'Data bab berhasil disimpan.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menyimpan data: ' . $th->getMessage());
        }
        return $response;
    }

    public function update($id)
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->subject_id = $this->input->post('subject_id');
            $this->name = $this->input->post('name');
            $this->order_num = $this->input->post('order_num') ?: 0;
            $this->db->update($this->_table, $this, array('id' => $id));
            $response = array('status' => true, 'data' => 'Data bab berhasil diperbarui.');
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
            $response = array('status' => true, 'data' => 'Data bab berhasil dihapus.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
        }
        return $response;
    }

    // Untuk dropdown combobox subject
    public function getSubjects()
    {
        $this->db->order_by('name', 'asc');
        return $this->db->get('subjects')->result();
    }
}
