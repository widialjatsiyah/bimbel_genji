<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EssayAnswerModel extends CI_Model
{
    private $_table = 'essay_answers';

    public function rules()
    {
        return array(
            [
                'field' => 'user_tryout_id',
                'label' => 'User Tryout',
                'rules' => 'required|integer'
            ],
            [
                'field' => 'question_id',
                'label' => 'Soal',
                'rules' => 'required|integer'
            ],
            [
                'field' => 'answer_text',
                'label' => 'Jawaban',
                'rules' => 'required'
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

    public function getById($id)
    {
        $this->db->where('id', $id);
        return $this->db->get($this->_table)->row();
    }

    public function getByUserTryoutAndQuestion($user_tryout_id, $question_id)
    {
        $this->db->where('user_tryout_id', $user_tryout_id);
        $this->db->where('question_id', $question_id);
        return $this->db->get($this->_table)->row();
    }

    public function insert()
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $this->user_tryout_id = $this->input->post('user_tryout_id');
            $this->question_id = $this->input->post('question_id');
            $this->answer_text = $this->input->post('answer_text');
            $this->score = 0; // Nilai default sebelum dinilai
            $this->db->insert($this->_table, $this);
            $response = array('status' => true, 'data' => 'Jawaban esai berhasil disimpan.', 'insert_id' => $this->db->insert_id());
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal menyimpan jawaban esai: ' . $th->getMessage());
        }
        return $response;
    }

    public function updateScore($id, $score, $evaluator_id = null)
    {
        $response = array('status' => false, 'data' => 'No operation.');
        try {
            $data = array(
                'score' => $score,
                'evaluated_by' => $evaluator_id,
                'evaluated_at' => date('Y-m-d H:i:s')
            );
            $this->db->update($this->_table, $data, array('id' => $id));
            $response = array('status' => true, 'data' => 'Nilai jawaban esai berhasil diperbarui.');
        } catch (\Throwable $th) {
            $response = array('status' => false, 'data' => 'Gagal memperbarui nilai: ' . $th->getMessage());
        }
        return $response;
    }

    public function calculateScoreBasedOnKeywords($question_id, $answer_text)
    {
        // Ambil soal dan kata kunci yang diharapkan
        $question = $this->db->get_where('questions', array('id' => $question_id))->row();
        
        if (!$question || !$question->expected_keywords) {
            return 0;
        }

        // Decode JSON kata kunci
        $expected_keywords = json_decode($question->expected_keywords, true);
        if (!$expected_keywords) {
            return 0;
        }

        // Hitung jumlah kata kunci yang ditemukan dalam jawaban
        $matches = 0;
        foreach ($expected_keywords as $keyword) {
            // Gunakan pencarian case-insensitive
            if (stripos($answer_text, $keyword['word']) !== false) {
                $matches++;
            }
        }

        // Hitung persentase berdasarkan jumlah kata kunci yang cocok
        $percentage = count($expected_keywords) > 0 ? ($matches / count($expected_keywords)) * 100 : 0;
        
        // Pastikan tidak lebih dari 100%
        return min($percentage, 100);
    }
}