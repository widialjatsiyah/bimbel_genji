<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PackageModel extends CI_Model
{
    private $_table = 'packages';

    public function rules()
    {
        return array(
            [
                'field' => 'name',
                'label' => 'Nama Paket',
                'rules' => 'required|trim|max_length[100]'
            ],
            [
                'field' => 'price',
                'label' => 'Harga',
                'rules' => 'required|numeric'
            ],
            [
                'field' => 'duration_days',
                'label' => 'Durasi (hari)',
                'rules' => 'required|integer'
            ],
            [
                'field' => 'features',
                'label' => 'Fitur',
                'rules' => 'trim'
            ],
            [
                'field' => 'is_active',
                'label' => 'Status Aktif',
                'rules' => 'in_list[0,1]'
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

    public function insert()
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->name = $this->input->post('name');
            $this->description = $this->input->post('description');
            $this->price = $this->clean_number($this->input->post('price'));
            $this->duration_days = $this->input->post('duration_days');
            // Features disimpan sebagai JSON string
            $features = $this->input->post('features');
            $this->features = !empty($features) ? json_encode(explode("\n", trim($features))) : null;
            $this->is_active = $this->input->post('is_active') ? 1 : 0;
            $this->created_at = date('Y-m-d H:i:s');
            $this->db->insert($this->_table, $this);
            $response = array('status' => true, 'data' => 'Data paket berhasil disimpan.');
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
            $this->description = $this->input->post('description');
            $this->price = $this->clean_number($this->input->post('price'));
            $this->duration_days = $this->input->post('duration_days');
            $features = $this->input->post('features');
            $this->features = !empty($features) ? json_encode(explode("\n", trim($features))) : null;
            $this->is_active = $this->input->post('is_active') ? 1 : 0;
            $this->db->update($this->_table, $this, array('id' => $id));
            $response = array('status' => true, 'data' => 'Data paket berhasil diperbarui.');
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
            $response = array('status' => true, 'data' => 'Data paket berhasil dihapus.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
        }
        return $response;
    }

    function clean_number($number)
    {
        return preg_replace('/[^0-9.]/', '', $number);
    }
}
