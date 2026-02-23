<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TryoutClassModel extends CI_Model
{
    private $_table = 'tryout_class';

    public function rules()
    {
        return array(
            [
                'field' => 'tryout_id',
                'label' => 'Try Out',
                'rules' => 'required|integer'
            ],
            [
                'field' => 'class_id',
                'label' => 'Kelas',
                'rules' => 'required|integer'
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
            ]
        );
    }

    public function getByClass($class_id)
    {
        return $this->db->select('tc.*, t.title as tryout_title, t.description, t.type, t.mode, t.total_duration')
                        ->from($this->_table . ' tc')
                        ->join('tryouts t', 't.id = tc.tryout_id')
                        ->where('tc.class_id', $class_id)
                        ->where('tc.is_active', 1)
                        ->order_by('tc.start_time', 'asc')
                        ->get()
                        ->result();
    }

    public function getByTryout($tryout_id)
    {
        return $this->db->select('tc.*, c.name as class_name')
                        ->from($this->_table . ' tc')
                        ->join('classes c', 'c.id = tc.class_id')
                        ->where('tc.tryout_id', $tryout_id)
                        ->order_by('tc.start_time', 'asc')
                        ->get()
                        ->result();
    }

    public function getScheduledForStudent($student_id)
    {
        // Ambil kelas siswa
        $this->load->model('StudentClassModel');
        $classes = $this->StudentClassModel->getClassesByStudent($student_id);
        if (empty($classes)) return [];

        $class_ids = array_column($classes, 'id');

        $now = date('Y-m-d H:i:s');
        return $this->db->select('tc.*, t.title, t.description, t.type, t.mode, t.total_duration')
                        ->from($this->_table . ' tc')
                        ->join('tryouts t', 't.id = tc.tryout_id')
                        ->where_in('tc.class_id', $class_ids)
                        ->where('tc.is_active', 1)
                        ->where('t.is_published', 1)
                        ->group_start()
                            ->where('tc.start_time <=', $now)
                            ->where('tc.end_time >=', $now)
                            ->or_where('tc.start_time IS NULL', null, false)
                        ->group_end()
                        ->order_by('tc.start_time', 'asc')
                        ->get()
                        ->result();
    }

    public function isScheduledForStudent($student_id, $tryout_id)
    {
        $classes = $this->StudentClassModel->getClassesByStudent($student_id);
        if (empty($classes)) return false;
        $class_ids = array_column($classes, 'id');
        $this->db->where('tryout_id', $tryout_id)
                 ->where_in('class_id', $class_ids)
                 ->where('is_active', 1);
        return $this->db->count_all_results($this->_table) > 0;
    }

    public function insert()
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->tryout_id = $this->input->post('tryout_id');
            $this->class_id = $this->input->post('class_id');
            $this->start_time = $this->input->post('start_time') ?: null;
            $this->end_time = $this->input->post('end_time') ?: null;
            $this->is_active = $this->input->post('is_active') ? 1 : 0;
            $this->created_at = date('Y-m-d H:i:s');
            $this->db->insert($this->_table, $this);
            $response = array('status' => true, 'data' => 'Jadwal try out berhasil ditambahkan.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menyimpan: ' . $th->getMessage());
        }
        return $response;
    }

    public function update($id)
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->tryout_id = $this->input->post('tryout_id');
            $this->class_id = $this->input->post('class_id');
            $this->start_time = $this->input->post('start_time') ?: null;
            $this->end_time = $this->input->post('end_time') ?: null;
            $this->is_active = $this->input->post('is_active') ? 1 : 0;
            $this->db->update($this->_table, $this, array('id' => $id));
            $response = array('status' => true, 'data' => 'Jadwal try out berhasil diperbarui.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal memperbarui: ' . $th->getMessage());
        }
        return $response;
    }

    public function delete($id)
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->db->delete($this->_table, array('id' => $id));
            $response = array('status' => true, 'data' => 'Jadwal try out berhasil dihapus.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menghapus: ' . $th->getMessage());
        }
        return $response;
    }
}
