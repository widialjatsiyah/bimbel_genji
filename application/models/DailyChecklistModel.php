<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DailyChecklistModel extends CI_Model
{
    private $_table = 'daily_checklist';

    public function rules()
    {
        return array(
            [
                'field' => 'date',
                'label' => 'Tanggal',
                'rules' => 'required'
            ],
            [
                'field' => 'mood_rating',
                'label' => 'Mood',
                'rules' => 'integer|in_list[1,2,3,4,5,6,7,8,9,10]'
            ],
            [
                'field' => 'notes',
                'label' => 'Catatan',
                'rules' => 'trim'
            ]
        );
    }

    public function getToday($user_id)
    {
        return $this->db->where('user_id', $user_id)
                        ->where('date', date('Y-m-d'))
                        ->get($this->_table)
                        ->row();
    }

    public function getByDate($user_id, $date)
    {
        return $this->db->where('user_id', $user_id)
                        ->where('date', $date)
                        ->get($this->_table)
                        ->row();
    }

    public function getRange($user_id, $start_date, $end_date)
    {
        return $this->db->where('user_id', $user_id)
                        ->where('date >=', $start_date)
                        ->where('date <=', $end_date)
                        ->order_by('date', 'asc')
                        ->get($this->_table)
                        ->result();
    }

    public function save()
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $user_id = $this->session->userdata('user')['id'];
            $date = $this->input->post('date');

            // Data checklist dalam format JSON
            $checklist_data = [
                'shalat_subuh' => $this->input->post('shalat_subuh') ? true : false,
                'shalat_dzuhur' => $this->input->post('shalat_dzuhur') ? true : false,
                'shalat_ashar' => $this->input->post('shalat_ashar') ? true : false,
                'shalat_maghrib' => $this->input->post('shalat_maghrib') ? true : false,
                'shalat_isya' => $this->input->post('shalat_isya') ? true : false,
                'tilawah' => $this->input->post('tilawah') ? true : false,
                'belajar_menit' => (int)$this->input->post('belajar_menit'),
                'olahraga' => $this->input->post('olahraga') ? true : false,
                'membaca_buku' => $this->input->post('membaca_buku') ? true : false
            ];

            $data = [
                'user_id' => $user_id,
                'date' => $date,
                'checklist_data' => json_encode($checklist_data),
                'mood_rating' => $this->input->post('mood_rating'),
                'notes' => $this->input->post('notes')
            ];

            $existing = $this->getByDate($user_id, $date);
            if ($existing) {
                $this->db->where('id', $existing->id)->update($this->_table, $data);
            } else {
                $this->db->insert($this->_table, $data);
            }

            $response = array('status' => true, 'data' => 'Checklist harian berhasil disimpan.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menyimpan: ' . $th->getMessage());
        }
        return $response;
    }

    public function delete($id)
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->db->delete($this->_table, array('id' => $id));
            $response = array('status' => true, 'data' => 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menghapus: ' . $th->getMessage());
        }
        return $response;
    }
}
