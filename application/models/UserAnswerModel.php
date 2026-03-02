<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserAnswerModel extends CI_Model
{
    private $_table = 'user_answers';

    public function saveAnswer($user_tryout_id, $question_id, $answer, $is_unsure = 0)
    {
        $data = [
            'user_tryout_id' => $user_tryout_id,
            'question_id' => $question_id,
            'answer' => $answer,
            'is_unsure' => $is_unsure,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $existing = $this->db->where('user_tryout_id', $user_tryout_id)
            ->where('question_id', $question_id)
            ->get($this->_table)
            ->row();

        if ($existing) {
            $this->db->where('id', $existing->id)->update($this->_table, $data);
        } else {
            $this->db->insert($this->_table, $data);
        }
    }

    public function getAnswers($user_tryout_id)
    {
        return $this->db->where('user_tryout_id', $user_tryout_id)
            ->get($this->_table)
            ->result_array();
    }

	public function getAnswer($user_tryout_id, $question_id)
{
    return $this->db->where('user_tryout_id', $user_tryout_id)
                    ->where('question_id', $question_id)
                    ->get('user_answers')
                    ->row();
}

    public function markUnsure($user_tryout_id, $question_id, $is_unsure)
    {
        $this->db->where('user_tryout_id', $user_tryout_id)
            ->where('question_id', $question_id)
            ->update($this->_table, ['is_unsure' => $is_unsure]);
    }

	public function getAnswersByUserTryoutAndSession($user_tryout_id, $session_id)
    {
        return $this->db->select('ua.*')
                        ->from('user_answers ua')
                        ->join('tryout_questions tq', 'tq.question_id = ua.question_id')
                        ->join('tryout_sessions ts', 'ts.id = tq.tryout_session_id')
                        ->where('ua.user_tryout_id', $user_tryout_id)
                        ->where('ts.id', $session_id)
                        ->get()
                        ->result_array();
    }
}
