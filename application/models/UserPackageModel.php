<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserPackageModel extends CI_Model
{
	private $_table = 'user_packages';

	public function rules()
	{
		return array(
			[
				'field' => 'user_id',
				'label' => 'Pengguna',
				'rules' => 'required|integer'
			],
			[
				'field' => 'package_id',
				'label' => 'Paket',
				'rules' => 'required|integer'
			],
			[
				'field' => 'start_date',
				'label' => 'Tanggal Mulai',
				'rules' => 'required'
			],
			[
				'field' => 'end_date',
				'label' => 'Tanggal Akhir',
				'rules' => 'required'
			],
			[
				'field' => 'status',
				'label' => 'Status',
				'rules' => 'required|in_list[active,expired,cancelled]'
			],
			[
				'field' => 'payment_status',
				'label' => 'Status Pembayaran',
				'rules' => 'required|in_list[pending,paid,failed]'
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

	public function getDetail($params = array())
	{
		$this->db->where($params);
		return $this->db->get($this->_table)->row();
	}

	public function insert()
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->user_id = $this->input->post('user_id');
			$this->package_id = $this->input->post('package_id');
			$this->start_date = $this->input->post('start_date');
			$this->end_date = $this->input->post('end_date');
			$this->status = $this->input->post('status');
			$this->payment_status = $this->input->post('payment_status');
			$this->created_at = date('Y-m-d H:i:s');
			$this->db->insert($this->_table, $this);
			$response = array('status' => true, 'data' => 'Data paket pengguna berhasil disimpan.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menyimpan data: ' . $th->getMessage());
		}
		return $response;
	}

	public function update($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->user_id = $this->input->post('user_id');
			$this->package_id = $this->input->post('package_id');
			$this->start_date = $this->input->post('start_date');
			$this->end_date = $this->input->post('end_date');
			$this->status = $this->input->post('status');
			$this->payment_status = $this->input->post('payment_status');
			$this->db->update($this->_table, $this, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data paket pengguna berhasil diperbarui.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal memperbarui data: ' . $th->getMessage());
		}
		return $response;
	}

	public function delete($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->db->delete($this->_table, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data paket pengguna berhasil dihapus.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
		}
		return $response;
	}

	public function hasAccessTo($user_id, $item_type, $item_id)
	{
		// Cari paket aktif user
		$this->db->where('user_id', $user_id)
			->where('status', 'active')
			->where('end_date >=', date('Y-m-d'));
		$active = $this->db->get('user_packages')->result();
		if (empty($active)) return false;

		$package_ids = array_column($active, 'package_id');

		// Cek apakah item termasuk dalam paket-paket tersebut
		$this->db->where_in('package_id', $package_ids)
			->where('item_type', $item_type)
			->where('item_id', $item_id);
		return $this->db->count_all_results('package_items') > 0;
	}

	// Untuk mendapatkan semua item yang dimiliki user (misal semua tryout yang bisa diakses)
	public function getAccessibleItems($user_id, $item_type)
	{
		$this->db->select('item_id')
			->from('package_items pi')
			->join('user_packages up', 'up.package_id = pi.package_id')
			->where('up.user_id', $user_id)
			->where('up.status', 'active')
			->where('up.end_date >=', date('Y-m-d'))
			->where('pi.item_type', $item_type);
		$result = $this->db->get()->result();
		return array_column($result, 'item_id');
	}
}
