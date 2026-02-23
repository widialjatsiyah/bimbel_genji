<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenjualanitemModel extends CI_Model
{
  private $_table = 'penjualan_item';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'penjualan_id',
        'label' => 'Penjualan ID',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'barang_id',
        'label' => 'Barang ID',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'nama_barang',
        'label' => 'Nama Barang',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'quantity',
        'label' => 'Qty',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'harga_satuan',
        'label' => 'HPP',
        'rules' => 'required|trim'
      ],
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
    return $this->db->where($params)->get($this->_table)->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->penjualan_id = $this->input->post('penjualan_id');
      $this->barang_id = $this->input->post('barang_id');
      $this->kode_barang = $this->input->post('kode_barang');
      $this->nama_barang = $this->input->post('nama_barang');
      $this->quantity = $this->clean_number($this->input->post('quantity'));
      $this->satuan = $this->input->post('satuan');
      $this->harga_satuan = $this->clean_number($this->input->post('harga_satuan'));
      $this->diskon = $this->clean_number($this->input->post('diskon'));
      $this->ppn = $this->clean_number($this->input->post('ppn'));
      $this->sub_total = $this->clean_number($this->input->post('sub_total'));
      $this->created_by = $this->session->userdata('user')['id'];
      $this->db->insert($this->_table, $this);

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function insertBatch($data)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->insert_batch($this->_table, $data);

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function update($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->penjualan_id = $this->input->post('penjualan_id');
      $this->barang_id = $this->input->post('barang_id');
      $this->kode_barang = $this->input->post('kode_barang');
      $this->nama_barang = $this->input->post('nama_barang');
      $this->quantity = $this->clean_number($this->input->post('quantity'));
      $this->satuan = $this->input->post('satuan');
      $this->harga_satuan = $this->clean_number($this->input->post('harga_satuan'));
      $this->diskon = $this->clean_number($this->input->post('diskon'));
      $this->ppn = $this->clean_number($this->input->post('ppn'));
      $this->sub_total = $this->clean_number($this->input->post('sub_total'));
      $this->db->update($this->_table, $this, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function delete($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->delete($this->_table, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your data.');
    };

    return $response;
  }

  public function deleteByPembelianPersediaan($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->delete($this->_table, array('penjualan_id' => $id));

      $response = array('status' => true, 'data' => 'Data has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your data.');
    };

    return $response;
  }

  public function truncate()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->truncate($this->_table);

      $response = array('status' => true, 'data' => 'Data has been truncated.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to truncate your data.');
    };

    return $response;
  }

  function br2nl($text)
  {
    return str_replace("\r\n", '<br/>', htmlspecialchars_decode($text));
  }

  function clean_number($number)
  {
    return preg_replace('/[^0-9.]/', '', $number);
  }
}
