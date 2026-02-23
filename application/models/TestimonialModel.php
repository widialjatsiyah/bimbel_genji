<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TestimonialModel extends CI_Model
{
    private $_table = 'testimonials';

    public function rules()
    {
        return [
            [
                'field' => 'name',
                'label' => 'Nama',
                'rules' => 'required|trim|max_length[100]'
            ],
            [
                'field' => 'content',
                'label' => 'Testimoni',
                'rules' => 'required|trim'
            ],
            [
                'field' => 'rating',
                'label' => 'Rating',
                'rules' => 'integer|in_list[1,2,3,4,5]'
            ],
            [
                'field' => 'order_num',
                'label' => 'Urutan',
                'rules' => 'integer'
            ],
            [
                'field' => 'is_approved',
                'label' => 'Disetujui',
                'rules' => 'in_list[0,1]'
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
                'user_id' => $this->session->userdata('user')['id'], // jika testimoni dari user terdaftar
                'name' => $this->input->post('name'),
                'position' => $this->input->post('position'),
                'company' => $this->input->post('company'),
                'content' => $this->input->post('content'),
                'rating' => $this->input->post('rating') ?: null,
                'is_approved' => $this->input->post('is_approved') ? 1 : 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'order_num' => $this->input->post('order_num') ?: 0
            ];

            // Upload foto jika ada
            if (!empty($_FILES['photo']['name'])) {
                $this->load->library('CpUpload');
                $upload = $this->cpupload->run('photo', 'testimonials', false, true, 'jpg|jpeg|png|gif');
                if ($upload->status) {
                    $data['photo'] = $upload->data->base_path;
                } else {
                    return ['status' => false, 'data' => $upload->data];
                }
            }

            $this->db->insert($this->_table, $data);
            $response = ['status' => true, 'data' => 'Testimoni berhasil disimpan.'];
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
                'name' => $this->input->post('name'),
                'position' => $this->input->post('position'),
                'company' => $this->input->post('company'),
                'content' => $this->input->post('content'),
                'rating' => $this->input->post('rating') ?: null,
                'is_approved' => $this->input->post('is_approved') ? 1 : 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0,
                'order_num' => $this->input->post('order_num') ?: 0
            ];

            // Upload foto baru jika ada
            if (!empty($_FILES['photo']['name'])) {
                $this->load->library('CpUpload');
                $upload = $this->cpupload->run('photo', 'testimonials', false, true, 'jpg|jpeg|png|gif');
                if ($upload->status) {
                    // Hapus foto lama
                    $old = $this->getDetail($id);
                    if ($old && $old->photo && file_exists(FCPATH . $old->photo)) {
                        unlink(FCPATH . $old->photo);
                    }
                    $data['photo'] = $upload->data->base_path;
                } else {
                    return ['status' => false, 'data' => $upload->data];
                }
            }

            $this->db->where('id', $id)->update($this->_table, $data);
            $response = ['status' => true, 'data' => 'Testimoni berhasil diperbarui.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal memperbarui: ' . $e->getMessage()];
        }
        return $response;
    }

    public function delete($id)
    {
        $response = ['status' => false, 'data' => 'No operation.'];
        try {
            // Hapus file foto
            $testi = $this->getDetail($id);
            if ($testi && $testi->photo && file_exists(FCPATH . $testi->photo)) {
                unlink(FCPATH . $testi->photo);
            }
            $this->db->where('id', $id)->delete($this->_table);
            $response = ['status' => true, 'data' => 'Testimoni berhasil dihapus.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal menghapus: ' . $e->getMessage()];
        }
        return $response;
    }
}
