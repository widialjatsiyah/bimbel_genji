<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SubjectModel extends CI_Model
{
    private $_table = 'subjects';

    public function rules()
    {
        return array(
            [
                'field' => 'name',
                'label' => 'Nama Mata Pelajaran',
                'rules' => 'required|trim|max_length[50]'
            ],
            [
                'field' => 'code',
                'label' => 'Kode',
                'rules' => 'trim|max_length[20]'
            ],
            [
                'field' => 'description',
                'label' => 'Deskripsi',
                'rules' => 'trim'
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
            $this->name = $this->input->post('name');
            $this->code = $this->input->post('code');
            $this->description = $this->input->post('description');
            $this->db->insert($this->_table, $this);
            $response = array('status' => true, 'data' => 'Data mata pelajaran berhasil disimpan.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menyimpan data: ' . $th->getMessage());
        }
        return $response;
    }

    public function update($id)
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->name = $this->input->post('name');
            $this->code = $this->input->post('code');
            $this->description = $this->input->post('description');
            $this->db->update($this->_table, $this, array('id' => $id));
            $response = array('status' => true, 'data' => 'Data mata pelajaran berhasil diperbarui.');
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
            $response = array('status' => true, 'data' => 'Data mata pelajaran berhasil dihapus.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
        }
        return $response;
    }

    // Fungsi bantuan untuk membersihkan angka (jika diperlukan)
    function clean_number($number)
    {
        return preg_replace('/[^0-9]/', '', $number);
    }
}
