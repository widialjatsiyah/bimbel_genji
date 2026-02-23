<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PenjualanModel extends CI_Model
{
  private $_table = 'penjualan';
  private $_tableView = 'view_penjualan';

  public function rules()
  {
    return array(
      [
        'field' => 'supplier_id',
        'label' => 'Supplier',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'nomor_po',
        'label' => 'No. PO',
        'rules' => [
          'required',
          'trim',
          [
            'nomor_exist',
            function ($value) {
              return $this->_nomor_exist($value);
            }
          ]
        ]
      ],
      [
        'field' => 'tanggal_po',
        'label' => 'Tanggal PO',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'penerima',
        'label' => 'Petugas',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'cara_bayar',
        'label' => 'Cara Bayar',
        'rules' => 'required|trim'
      ],
      [
        'field' => 'ppn_persentase',
        'label' => 'PPN',
        'rules' => 'required|trim'
      ],
    );
  }

  private function _nomor_exist($value)
  {
    $temp = $this->db->where(array('nomor_po' => $value))->get($this->_table);

    if ($temp->num_rows() > 0) {
      $this->form_validation->set_message('nomor_exist', 'No. PO "' . $value . '" has been used.');
      return false;
    } else {
      return true;
    };
  }

  public function generateNomor($ref = 'penjualan')
  {
    $unit = $this->session->userdata('user')['unit'];
    $subUnit = $this->session->userdata('user')['sub_unit'];

    $suratNomor = $this->SuratnomorModel->getDetail(array(
      'LOWER(unit)' => strtolower($unit),
      'LOWER(sub_unit)' => strtolower($subUnit),
      'LOWER(ref)' => strtolower($ref)
    ));
    $format = (!is_null($suratNomor)) ? $suratNomor->format_nomor : null;

    if (!is_null($format)) {
      $roman_month = array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
      $month = $roman_month[date('n')];
      $month_num = date('m');
      $year = date('Y');
      $year_2 = date('y');

      // Reformat
      $format = str_replace('{MONTH}', $month, $format);
      $format = str_replace('{MONTH_NUM}', $month_num, $format);
      $format = str_replace('{YEAR}', $year, $format);
      $format = str_replace('{YEAR_2}', $year_2, $format);

      // Get nomor from db
      $this->db->select("lpad(cast(cast(substring(nomor_po, position('{INC}' in '$format'), 5) as integer) + 1 as text), 5, '0') as auto_nomor");
      $this->db->from($this->_table);
      $this->db->order_by('id', 'DESC');
      $this->db->limit(1);
      $temp = $this->db->get()->row();

      if (!is_null($temp) && !empty($temp)) {
        $inc = $temp->auto_nomor;
      } else {
        $inc = '00001';
      };

      // Assign {INC}
      $format = str_replace('{INC}', $inc, $format);
    } else {
      $format = null;
    };

    return $format;
  }

  
  public function getLastFaktur($params = array())
  {
    $this->db->select('no_invoice');
    $this->db->like($params);
    $this->db->order_by('id', 'desc');
    $this->db->limit(1);

    return $this->db->get($this->_table)->row();
  }


  public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
  {
    $this->db->where($params);

    if (!is_null($orderField)) {
      $this->db->order_by($orderField, $orderBy);
    };

    return $this->db->get($this->_tableView)->result();
  }

  public function getDetail($params = array())
  {
    return $this->db->where($params)->get($this->_tableView)->row();
  }

  public function insert()
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->supplier_id = $this->input->post('supplier_id');
      $this->nomor_po = $this->input->post('nomor_po');
      $this->tanggal_po = $this->input->post('tanggal_po');
      $this->penerima = $this->input->post('penerima');
      $this->cara_bayar = $this->input->post('cara_bayar');
      $this->kredit_memo = $this->input->post('kredit_memo');
      $this->keterangan = $this->input->post('keterangan');
      $this->ppn_persentase = $this->clean_number($this->input->post('ppn_persentase'));
      $this->total_amount = $this->clean_number($this->input->post('total_amount'));
      $this->created_by = $this->session->userdata('user')['id'];
      $this->db->insert($this->_table, $this);

      $temp_id = $this->db->insert_id();

      $response = array('status' => true, 'data' => 'Data has been saved.', 'data_id' => $temp_id);
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.', 'data_id' => null);
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
      $this->supplier_id = $this->input->post('supplier_id');
      $this->nomor_po = $this->input->post('nomor_po');
      $this->tanggal_po = $this->input->post('tanggal_po');
      $this->penerima = $this->input->post('penerima');
      $this->cara_bayar = $this->input->post('cara_bayar');
      $this->kredit_memo = $this->input->post('kredit_memo');
      $this->keterangan = $this->input->post('keterangan');
      $this->ppn_persentase = $this->clean_number($this->input->post('ppn_persentase'));
      $this->total_amount = $this->clean_number($this->input->post('total_amount'));
      $this->updated_at = date('Y-m-d H:i:s');
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->db->update($this->_table, $this, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been saved.', 'data_id' => $id);
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data.', 'data_id' => $id);
    };

    return $response;
  }

  public function delete($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->db->delete('penjualan_item', array('penjualan_id' => $id)); // delete items
      $this->db->delete($this->_table, array('id' => $id)); // delete master

      $response = array('status' => true, 'data' => 'Data has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your data.');
    };

    return $response;
  }

  public function cancel($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      // Pembelian persediaan
      $this->db->delete('penjualan_item', array('penjualan_id' => $id)); // items
      $this->db->delete($this->_table, array('id' => $id)); // master

      // Other data
      $this->db->delete('penerimaan_barang', array('penjualan_id' => $id));
      $this->db->delete('faktur_pembelian', array('source_id' => $id, 'source_table_name' => 'penjualan'));

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
