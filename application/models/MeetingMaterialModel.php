<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MeetingMaterialModel extends CI_Model
{
	private $_table = 'meeting_materials';

	public function getById($id)
	{
		return $this->db->where('id', $id)->get($this->_table)->row();
	}
	public function getByMeeting($meeting_id)
	{
		return $this->db->select('mm.*, m.title, m.type, m.url')
			->from($this->_table . ' mm')
			->join('materials m', 'm.id = mm.material_id')
			->where('mm.meeting_id', $meeting_id)
			->order_by('mm.order_num', 'asc')
			->get()
			->result();
	}

	public function addMaterial($meeting_id, $material_id, $order_num = 0)
	{
		$data = [
			'meeting_id' => $meeting_id,
			'material_id' => $material_id,
			'order_num' => $order_num
		];
		$this->db->insert($this->_table, $data);
	}

	public function removeMaterial($id)
	{
		$this->db->delete($this->_table, ['id' => $id]);
	}

	public function removeByMeeting($meeting_id)
	{
		$this->db->delete($this->_table, ['meeting_id' => $meeting_id]);
	}
}
