<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SlideModel extends CI_Model
{
    private $_table = 'slides';

    public function rules()
    {
        return [
            ['field' => 'title', 'label' => 'Judul', 'rules' => 'required|max_length[200]'],
            ['field' => 'button_text', 'label' => 'Teks Tombol', 'rules' => 'max_length[100]'],
            ['field' => 'button_link', 'label' => 'Link Tombol', 'rules' => 'max_length[255]'],
            ['field' => 'order_num', 'label' => 'Urutan', 'rules' => 'integer']
        ];
    }

    public function getAll($params = [], $order = 'order_num ASC')
    {
        if (!empty($params)) $this->db->where($params);
        return $this->db->order_by($order)->get($this->_table)->result();
    }

    public function getDetail($id)
    {
        return $this->db->where('id', $id)->get($this->_table)->row();
    }

    public function insert()
    {
        $data = [
            'title' => $this->input->post('title'),
            'subtitle' => $this->input->post('subtitle'),
            'button_text' => $this->input->post('button_text'),
            'button_link' => $this->input->post('button_link'),
            'order_num' => $this->input->post('order_num') ?: 0,
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        // Handle upload image
        if (!empty($_FILES['image']['name'])) {
            $this->load->library('CpUpload');
            $upload = $this->cpupload->run('image', 'slides', false, true, 'jpg|jpeg|png|gif');
            if ($upload->status) {
                $data['image'] = $upload->data->base_path;
            } else {
                return ['status' => false, 'data' => $upload->data];
            }
        }

        $this->db->insert($this->_table, $data);
        return ['status' => true, 'data' => 'Slide berhasil disimpan'];
    }

    public function update($id)
    {
        $data = [
            'title' => $this->input->post('title'),
            'subtitle' => $this->input->post('subtitle'),
            'button_text' => $this->input->post('button_text'),
            'button_link' => $this->input->post('button_link'),
            'order_num' => $this->input->post('order_num') ?: 0,
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];

        // Handle upload image if any
        if (!empty($_FILES['image']['name'])) {
            $this->load->library('CpUpload');
            $upload = $this->cpupload->run('image', 'slides', false, true, 'jpg|jpeg|png|gif');
            if ($upload->status) {
                // Hapus file lama jika ada
                $old = $this->getDetail($id);
                if ($old && $old->image && file_exists(FCPATH . $old->image)) {
                    unlink(FCPATH . $old->image);
                }
                $data['image'] = $upload->data->base_path;
            } else {
                return ['status' => false, 'data' => $upload->data];
            }
        }

        $this->db->where('id', $id)->update($this->_table, $data);
        return ['status' => true, 'data' => 'Slide berhasil diperbarui'];
    }

    public function delete($id)
    {
        // Hapus file image
        $slide = $this->getDetail($id);
        if ($slide && $slide->image && file_exists(FCPATH . $slide->image)) {
            unlink(FCPATH . $slide->image);
        }
        $this->db->where('id', $id)->delete($this->_table);
        return ['status' => true, 'data' => 'Slide berhasil dihapus'];
    }
}
