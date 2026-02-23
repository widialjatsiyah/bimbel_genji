<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FaqModel extends CI_Model
{
    private $_table = 'faqs';

    public function rules()
    {
        return [
            [
                'field' => 'question',
                'label' => 'Pertanyaan',
                'rules' => 'required|trim'
            ],
            [
                'field' => 'answer',
                'label' => 'Jawaban',
                'rules' => 'required|trim'
            ],
            [
                'field' => 'category',
                'label' => 'Kategori',
                'rules' => 'trim|max_length[50]'
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
                'question' => $this->input->post('question'),
                'answer' => $this->input->post('answer'),
                'category' => $this->input->post('category'),
                'order_num' => $this->input->post('order_num') ?: 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];
            $this->db->insert($this->_table, $data);
            $response = ['status' => true, 'data' => 'FAQ berhasil disimpan.'];
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
                'question' => $this->input->post('question'),
                'answer' => $this->input->post('answer'),
                'category' => $this->input->post('category'),
                'order_num' => $this->input->post('order_num') ?: 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];
            $this->db->where('id', $id)->update($this->_table, $data);
            $response = ['status' => true, 'data' => 'FAQ berhasil diperbarui.'];
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
            $response = ['status' => true, 'data' => 'FAQ berhasil dihapus.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal menghapus: ' . $e->getMessage()];
        }
        return $response;
    }
}
