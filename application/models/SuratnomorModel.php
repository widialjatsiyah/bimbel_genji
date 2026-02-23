<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SuratnomorModel extends CI_Model
{
  private $_table = 'surat_nomor';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'unit',
        'label' => 'Unit',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'sub_unit',
        'label' => 'Sub Unit',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'format_nomor',
        'label' => 'Format Nomor',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'ref',
        'label' => 'Ref',
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
      $this->unit = $this->input->post('unit');
      $this->sub_unit = $this->input->post('sub_unit');
      $this->format_nomor = $this->input->post('format_nomor');
      $this->ref = $this->input->post('ref');
      $this->updated_at = date('Y-m-d H:i:s');
      $this->updated_by = $this->session->userdata('user')['id'];
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
      $this->unit = $this->input->post('unit');
      $this->sub_unit = $this->input->post('sub_unit');
      $this->format_nomor = $this->input->post('format_nomor');
      $this->ref = $this->input->post('ref');
      $this->updated_at = date('Y-m-d H:i:s');
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->db->update($this->_table, $this, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.');
    };

    return $response;
  }

  public function setFormatByRef($ref, $value)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $unit = $this->session->userdata('user')['unit'];
      $subUnit = $this->session->userdata('user')['sub_unit'];

      $suratnomor = $this->getDetail(array(
        'LOWER(unit)' => strtolower($unit),
        'LOWER(sub_unit)' => strtolower($subUnit),
        'ref' => $ref
      ));

      if (!is_null($suratnomor)) {
        // UPDATE
        $this->format_nomor = $value;
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = $this->session->userdata('user')['id'];
        $this->db->update($this->_table, $this, array(
          'LOWER(unit)' => strtolower($unit),
          'LOWER(sub_unit)' => strtolower($subUnit),
          'ref' => $ref
        ));
      } else {
        // CREATE
        $this->unit = $unit;
        $this->sub_unit = $subUnit;
        $this->format_nomor = $value;
        $this->ref = $ref;
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = $this->session->userdata('user')['id'];
        $this->db->insert($this->_table, $this);
      };

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
    return preg_replace('/[^0-9.]/', '', $number);
  }
}
