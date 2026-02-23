<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MeetingQuizModel extends CI_Model
{
    private $_table = 'meeting_quizzes';

    public function getByMeeting($meeting_id)
    {
        return $this->db->select('mq.*, t.title, t.description, t.total_duration')
                        ->from($this->_table . ' mq')
                        ->join('tryouts t', 't.id = mq.quiz_id')
                        ->where('mq.meeting_id', $meeting_id)
                        ->order_by('mq.order_num', 'asc')
                        ->get()
                        ->result();
    }

    public function addQuiz($meeting_id, $quiz_id, $order_num = 0)
    {
        $data = [
            'meeting_id' => $meeting_id,
            'quiz_id' => $quiz_id,
            'order_num' => $order_num
        ];
        $this->db->insert($this->_table, $data);
    }

    public function removeQuiz($id)
    {
        $this->db->delete($this->_table, ['id' => $id]);
    }

    public function removeByMeeting($meeting_id)
    {
        $this->db->delete($this->_table, ['meeting_id' => $meeting_id]);
    }
}
