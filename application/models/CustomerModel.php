<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CustomerModel extends CI_Model
{
  private $_table = 'customer';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'nama',
        'label' => 'Nama',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'telepon',
        'label' => 'Telepon',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'alamat',
        'label' => 'Alamat',
        'rules' => 'required|trim'
      ],
      // [
      //   'field' => 'kota',
      //   'label' => 'Kota',
      //   'rules' => 'required|trim'
      // ],
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
    $this->db->select('customer.*, agen.nama as agen_nama');
    $this->db->join('customer as agen', 'agen.id = customer.agen_id', 'left');
    $this->db->where($params);
    return $this->db->get($this->_table)->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->category = $this->input->post('category');
      $this->nama = $this->input->post('nama');
      $this->telepon = $this->input->post('telepon');
      $this->alamat = $this->input->post('alamat');
      $this->provinsi = $this->input->post('provinsi');
      $this->limit_kredit = $this->input->post('limit_kredit');
      $this->kuota_reject = $this->input->post('kuota_reject');
      $this->agen_id = $this->input->post('agen_id');
      $this->nik = $this->input->post('nik');
      $this->tanggal_bergabung = $this->input->post('tanggal_bergabung');
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
     
      $this->category = $this->input->post('category');
      $this->nama = $this->input->post('nama');
      $this->telepon = $this->input->post('telepon');
      $this->alamat = $this->input->post('alamat');
      $this->provinsi = $this->input->post('provinsi');
      $this->limit_kredit = $this->clean_number($this->input->post('limit_kredit'));
      $this->kuota_reject =  $this->clean_number($this->input->post('kuota_reject'));
      $this->agen_id = $this->input->post('agen_id');
      $this->nik = $this->input->post('nik');
      $this->tanggal_bergabung = $this->input->post('tanggal_bergabung');
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
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
    return preg_replace('/[^0-9]/', '', $number);
  }
}
