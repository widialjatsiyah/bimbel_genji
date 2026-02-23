<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TeamModel extends CI_Model
{
    private $_table = 'teams';

    public function rules()
    {
        return [
            [
                'field' => 'name',
                'label' => 'Nama',
                'rules' => 'required|trim|max_length[100]'
            ],
            [
                'field' => 'position',
                'label' => 'Jabatan',
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
                'name' => $this->input->post('name'),
                'position' => $this->input->post('position'),
                'bio' => $this->input->post('bio'),
                'social_facebook' => $this->input->post('social_facebook'),
                'social_twitter' => $this->input->post('social_twitter'),
                'social_instagram' => $this->input->post('social_instagram'),
                'social_linkedin' => $this->input->post('social_linkedin'),
                'order_num' => $this->input->post('order_num') ?: 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            // Upload foto jika ada
            if (!empty($_FILES['photo']['name'])) {
                $this->load->library('CpUpload');
                $upload = $this->cpupload->run('photo', 'teams', false, true, 'jpg|jpeg|png|gif');
                if ($upload->status) {
                    $data['photo'] = $upload->data->base_path;
                } else {
                    return ['status' => false, 'data' => $upload->data];
                }
            }

            $this->db->insert($this->_table, $data);
            $response = ['status' => true, 'data' => 'Anggota tim berhasil disimpan.'];
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
                'bio' => $this->input->post('bio'),
                'social_facebook' => $this->input->post('social_facebook'),
                'social_twitter' => $this->input->post('social_twitter'),
                'social_instagram' => $this->input->post('social_instagram'),
                'social_linkedin' => $this->input->post('social_linkedin'),
                'order_num' => $this->input->post('order_num') ?: 0,
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            // Upload foto baru jika ada
            if (!empty($_FILES['photo']['name'])) {
                $this->load->library('CpUpload');
                $upload = $this->cpupload->run('photo', 'teams', false, true, 'jpg|jpeg|png|gif');
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
            $response = ['status' => true, 'data' => 'Anggota tim berhasil diperbarui.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal memperbarui: ' . $e->getMessage()];
        }
        return $response;
    }

    public function delete($id)
    {
        $response = ['status' => false, 'data' => 'No operation.'];
        try {
            // Hapus foto
            $team = $this->getDetail($id);
            if ($team && $team->photo && file_exists(FCPATH . $team->photo)) {
                unlink(FCPATH . $team->photo);
            }
            $this->db->where('id', $id)->delete($this->_table);
            $response = ['status' => true, 'data' => 'Anggota tim berhasil dihapus.'];
        } catch (\Exception $e) {
            $response = ['status' => false, 'data' => 'Gagal menghapus: ' . $e->getMessage()];
        }
        return $response;
    }
}
