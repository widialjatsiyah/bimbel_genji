<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserMaterialProgressModel extends CI_Model
{
	private $_table = 'user_material_progress';

	public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
	{
		$this->db->where($params);
		if (!is_null($orderField)) {
			$this->db->order_by($orderField, $orderBy);
		};
		return $this->db->get($this->_table)->result();
	}

	public function getDetail($params = array())
	{
		$this->db->where($params);
		return $this->db->get($this->_table)->row();
	}

	public function getProgress($user_id, $material_id)
	{
		return $this->db->where('user_id', $user_id)
			->where('material_id', $material_id)
			->get($this->_table)
			->row();
	}

	public function updateProgress($user_id, $material_id, $data)
	{
		$existing = $this->getProgress($user_id, $material_id);
		if ($existing) {
			$this->db->where('id', $existing->id)->update($this->_table, $data);
		} else {
			$data['user_id'] = $user_id;
			$data['material_id'] = $material_id;
			$this->db->insert($this->_table, $data);
		}
	}

	public function markAsCompleted($user_id, $material_id)
	{
		$this->updateProgress($user_id, $material_id, [
			'status' => 'completed',
			'progress_percent' => 100,
			'completed_at' => date('Y-m-d H:i:s'),
			'last_accessed' => date('Y-m-d H:i:s')
		]);
	}

	public function getUserProgress($user_id, $params = [])
	{
		$this->db->select('ump.*, m.title, m.type, m.url, m.duration_seconds, m.description')
			->from($this->_table . ' ump')
			->join('materials m', 'm.id = ump.material_id')
			->where('ump.user_id', $user_id);
		if (!empty($params)) {
			$this->db->where($params);
		}
		return $this->db->order_by('ump.last_accessed', 'desc')->get()->result();
	}
	public function delete($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->db->delete($this->_table, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data progres berhasil dihapus.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
		}
		return $response;
	}

	public function countProgress($user_id)
	{
		$total = $this->db->where('user_id', $user_id)->count_all_results('user_material_progress');
		$completed = $this->db->where('user_id', $user_id)->where('status', 'completed')->count_all_results('user_material_progress');
		return ['total' => $total, 'completed' => $completed];
	}
}
