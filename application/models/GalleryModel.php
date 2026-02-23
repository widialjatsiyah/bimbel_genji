<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GalleryModel extends CI_Model
{
    private $_table = 'galleries';

    public function rules()
    {
        return [
            [
                'field' => 'title',
                'label' => 'Judul',
                'rules' => 'required|trim|max_length[200]'
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

    public function getAll($params = [], $order = 'order_num ASC, created_at DESC')
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
                'category' => $this->input->post('category'),
                'order_num' => $this->input->post('order_num') ?: 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            // Upload image
            if (!empty($_FILES['image']['name'])) {
                $this->load->library('CpUpload');
                $upload = $this->cpupload->run('image', 'galleries', false, true, 'jpg|jpeg|png|gif');
                if ($upload->status) {
                    $data['image'] = $upload->data->base_path;
                } else {
                    return ['status' => false, 'data' => $upload->data];
                }
            }

            $this->db->insert($this->_table, $data);
            $response = ['status' => true, 'data' => 'Galeri berhasil disimpan.'];
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
                'category' => $this->input->post('category'),
                'order_num' => $this->input->post('order_num') ?: 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            if (!empty($_FILES['image']['name'])) {
                $this->load->library('CpUpload');
                $old = $this->getDetail($id);
                $upload = $this->cpupload->run('image', 'galleries', false, true, 'jpg|jpeg|png|gif');
                if ($upload->status) {
                    // Hapus file lama
                    if ($old && $old->image && file_exists(FCPATH . $old->image)) {
                        unlink(FCPATH . $old->image);
                    }
                    $data['image'] = $upload->data->base_path;
                } else {
                    return ['status' => false, 'data' => $upload->data];
                }
            }

            $this->db->where('id', $id)->update($this->_table, $data);
            $response = ['status' => true, 'data' => 'Galeri berhasil diperbarui.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal memperbarui: ' . $e->getMessage()];
        }
        return $response;
    }

    public function delete($id)
    {
        $response = ['status' => false, 'data' => 'No operation.'];
        try {
            $old = $this->getDetail($id);
            if ($old && $old->image && file_exists(FCPATH . $old->image)) {
                unlink(FCPATH . $old->image);
            }
            $this->db->where('id', $id)->delete($this->_table);
            $response = ['status' => true, 'data' => 'Galeri berhasil dihapus.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal menghapus: ' . $e->getMessage()];
        }
        return $response;
    }
}
