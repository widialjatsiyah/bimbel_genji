<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ClassMeetingModel extends CI_Model
{
    private $_table = 'class_meetings';

    public function rules()
    {
        return [
            [
                'field' => 'class_id',
                'label' => 'Kelas',
                'rules' => 'required|integer'
            ],
            [
                'field' => 'title',
                'label' => 'Judul Pertemuan',
                'rules' => 'required|trim|max_length[200]'
            ],
            [
                'field' => 'date',
                'label' => 'Tanggal',
                'rules' => 'trim'
            ],
            [
                'field' => 'start_time',
                'label' => 'Waktu Mulai',
                'rules' => 'trim'
            ],
            [
                'field' => 'end_time',
                'label' => 'Waktu Selesai',
                'rules' => 'trim'
            ],
            [
                'field' => 'order_num',
                'label' => 'Urutan',
                'rules' => 'integer'
            ]
        ];
    }

    public function getByClass($class_id)
    {
        return $this->db->where('class_id', $class_id)
                        ->order_by('order_num', 'asc')
                        ->get($this->_table)
                        ->result();
    }

    public function getDetail($params)
    {
        return $this->db->where($params)->get($this->_table)->row();
    }

    public function insert()
    {
        $response = ['status' => false, 'data' => 'No operation.'];
        try {
            $this->class_id = $this->input->post('class_id');
            $this->title = $this->input->post('title');
            $this->description = $this->input->post('description');
            $this->date = $this->input->post('date') ?: null;
            $this->start_time = $this->input->post('start_time') ?: null;
            $this->end_time = $this->input->post('end_time') ?: null;
            $this->meeting_link = $this->input->post('meeting_link') ?: null;
            $this->order_num = $this->input->post('order_num') ?: 0;
            $this->db->insert($this->_table, $this);
            $response = ['status' => true, 'data' => 'Pertemuan berhasil disimpan.'];
        } catch (\Throwable $th) {
            $response = ['status' => false, 'data' => 'Gagal menyimpan: ' . $th->getMessage()];
        }
        return $response;
    }

    public function update($id)
    {
        $response = ['status' => false, 'data' => 'No operation.'];
        try {
            $this->class_id = $this->input->post('class_id');
            $this->title = $this->input->post('title');
            $this->description = $this->input->post('description');
            $this->date = $this->input->post('date') ?: null;
            $this->start_time = $this->input->post('start_time') ?: null;
            $this->end_time = $this->input->post('end_time') ?: null;
            $this->meeting_link = $this->input->post('meeting_link') ?: null;
            $this->order_num = $this->input->post('order_num') ?: 0;
            $this->db->update($this->_table, $this, ['id' => $id]);
            $response = ['status' => true, 'data' => 'Pertemuan berhasil diperbarui.'];
        } catch (\Throwable $th) {
            $response = ['status' => false, 'data' => 'Gagal memperbarui: ' . $th->getMessage()];
        }
        return $response;
    }

    public function delete($id)
    {
        $response = ['status' => false, 'data' => 'No operation.'];
        try {
            $this->db->delete($this->_table, ['id' => $id]);
            $response = ['status' => true, 'data' => 'Pertemuan berhasil dihapus.'];
        } catch (\Throwable $th) {
            $response = ['status' => false, 'data' => 'Gagal menghapus: ' . $th->getMessage()];
        }
        return $response;
    }
}
