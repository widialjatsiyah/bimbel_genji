<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TransactionModel extends CI_Model
{
	private $_table = 'transactions';

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

	public function getByOrderId($order_id)
	{
		return $this->db->where('order_id', $order_id)->get($this->_table)->row();
	}

	public function insert($data)
	{
		$this->db->insert($this->_table, $data);
		return $this->db->insert_id();
	}

	public function update($order_id, $data)
	{
		$this->db->where('order_id', $order_id)->update($this->_table, $data);
	}

	public function getUserTransactions($user_id)
	{
		return $this->db->where('user_id', $user_id)
			->order_by('created_at', 'desc')
			->get($this->_table)
			->result();
	}
}
