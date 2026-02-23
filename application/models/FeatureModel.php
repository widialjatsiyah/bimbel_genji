<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FeatureModel extends CI_Model
{
    private $_table = 'features';

    public function rules()
    {
        return [
            [
                'field' => 'title',
                'label' => 'Judul',
                'rules' => 'required|trim|max_length[200]'
            ],
            [
                'field' => 'icon',
                'label' => 'Icon',
                'rules' => 'trim|max_length[100]'
            ],
            [
                'field' => 'order_num',
                'label' => 'Urutan',
                'rules' => 'integer'
            ],
            [
                'field' => 'is_active',
                'label' => 'Status Aktif',
                'rules' => 'in_list[0,1]'
            ]
        ];
    }

    public function getAll($params = [], $order = 'order_num ASC')
    {
        if (!empty($params)) {
            $this->db->where($params);
        }
        return $this->db->order_by($order)->get($this->_table)->result();
    }

    public function getDetail($id)
    {
        return $this->db->where('id', $id)->get($this->_table)->row();
    }

    public function insert()
    {
        $response = ['status' => false, 'data' => 'No operation.'];
        try {
            $data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'icon' => $this->input->post('icon'),
                'order_num' => $this->input->post('order_num') ?: 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];
            $this->db->insert($this->_table, $data);
            $response = ['status' => true, 'data' => 'Fitur berhasil disimpan.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal menyimpan: ' . $e->getMessage()];
        }
        return $response;
    }

    public function update($id)
    {
        $response = ['status' => false, 'data' => 'No operation.'];
        try {
            $data = [
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description'),
                'icon' => $this->input->post('icon'),
                'order_num' => $this->input->post('order_num') ?: 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];
            $this->db->where('id', $id)->update($this->_table, $data);
            $response = ['status' => true, 'data' => 'Fitur berhasil diperbarui.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal memperbarui: ' . $e->getMessage()];
        }
        return $response;
    }

    public function delete($id)
    {
        $response = ['status' => false, 'data' => 'No operation.'];
        try {
            $this->db->where('id', $id)->delete($this->_table);
            $response = ['status' => true, 'data' => 'Fitur berhasil dihapus.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal menghapus: ' . $e->getMessage()];
        }
        return $response;
    }
}
