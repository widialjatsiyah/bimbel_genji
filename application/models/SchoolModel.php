<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SchoolModel extends CI_Model
{
    private $_table = 'schools';

    public function rules()
    {
        return array(
            [
                'field' => 'name',
                'label' => 'Nama Sekolah',
                'rules' => 'required|trim|max_length[100]'
            ],
            [
                'field' => 'address',
                'label' => 'Alamat',
                'rules' => 'trim'
            ],
            [
                'field' => 'contact_email',
                'label' => 'Email Kontak',
                'rules' => 'trim|valid_email|max_length[100]'
            ],
            [
                'field' => 'contact_phone',
                'label' => 'No. Telepon',
                'rules' => 'trim|max_length[20]'
            ],
            [
                'field' => 'logo_url',
                'label' => 'URL Logo',
                'rules' => 'trim|max_length[255]'
            ],
        );
    }

    public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
    {
        $this->db->where($params);
        if (!is_null($orderField)) {
            $this->db->order_by($orderField, $orderBy);
        }
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
            $this->address = $this->input->post('address');
            $this->contact_email = $this->input->post('contact_email');
            $this->contact_phone = $this->input->post('contact_phone');
            $this->logo_url = $this->input->post('logo_url');
            $this->db->insert($this->_table, $this);
            $response = array('status' => true, 'data' => 'Data sekolah berhasil disimpan.');
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
            $this->address = $this->input->post('address');
            $this->contact_email = $this->input->post('contact_email');
            $this->contact_phone = $this->input->post('contact_phone');
            $this->logo_url = $this->input->post('logo_url');
            $this->db->update($this->_table, $this, array('id' => $id));
            $response = array('status' => true, 'data' => 'Data sekolah berhasil diperbarui.');
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
            $response = array('status' => true, 'data' => 'Data sekolah berhasil dihapus.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
        }
        return $response;
    }

    function clean_number($number)
    {
        return preg_replace('/[^0-9]/', '', $number);
    }

	function countAll(){
		 return $this->db->count_all($this->_table);
	} 
}
