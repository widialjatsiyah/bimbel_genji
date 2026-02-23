<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExampleModel extends CI_Model
{
  private $_table = 'example';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'input_text',
        'label' => 'Text',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'input_number',
        'label' => 'Number',
        'rules' => 'required|trim|integer'
      ],
      [
        'field' => 'input_money',
        'label' => 'Money',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'input_date',
        'label' => 'Date',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'input_combobox',
        'label' => 'Combobox',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'input_textarea',
        'label' => 'Textare',
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
    $this->db->where($params);
    return $this->db->get($this->_table)->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->input_text = $this->input->post('input_text');
      $this->input_number = $this->clean_number($this->input->post('input_number'));
      $this->input_money = $this->clean_number($this->input->post('input_money'));
      $this->input_date = $this->input->post('input_date');
      $this->input_combobox = $this->input->post('input_combobox');
      $this->input_textarea = $this->input->post('input_textarea');
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
      $this->input_text = $this->input->post('input_text');
      $this->input_number = $this->clean_number($this->input->post('input_number'));
      $this->input_money = $this->clean_number($this->input->post('input_money'));
      $this->input_date = $this->input->post('input_date');
      $this->input_combobox = $this->input->post('input_combobox');
      $this->input_textarea = $this->input->post('input_textarea');
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
