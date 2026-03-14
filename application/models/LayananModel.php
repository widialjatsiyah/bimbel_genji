<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LayananModel extends CI_Model
{
  private $_table = 'layanan';
  private $_tableView = '';

  public function rules()
  {
    return array(
      [
        'field' => 'nama_layanan',
        'label' => 'Nama Layanan',
        'rules' => 'required|trim|max_length[100]'
      ],
      [
        'field' => 'keterangan',
        'label' => 'Keterangan',
        'rules' => 'trim'
      ],
      [
        'field' => 'file_imgae',
        'label' => 'File Image',
        'rules' => 'trim'
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
      $this->nama_layanan = $this->input->post('nama_layanan');
      $this->keterangan = $this->input->post('keterangan');
      
      // Handle image upload
      if (!empty($_FILES['file_imgae']['name'])) {
        $cpUpload = new CpUpload();
        $upload = $cpUpload->run('file_imgae', 'layanan', true, true, 'jpg|jpeg|png|gif');

        $this->file_imgae = '';

        if ($upload->status === true) {
          $this->file_imgae = $upload->data->base_path;
        }
      } else {
        $this->file_imgae = $this->input->post('file_imgae');
      }
      
      $this->created_by = $this->session->userdata('user')['id'];
      $this->db->insert($this->_table, $this);

      $response = array('status' => true, 'data' => 'Data has been saved.', 'last_id' => $this->db->insert_id());
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data: ' . $th->getMessage());
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
      $response = array('status' => false, 'data' => 'Failed to save your data: ' . $th->getMessage());
    };

    return $response;
  }

  public function update($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      $this->nama_layanan = $this->input->post('nama_layanan');
      $this->keterangan = $this->input->post('keterangan');
      
      // Handle image upload
      if (!empty($_FILES['file_imgae']['name'])) {
        $cpUpload = new CpUpload();
        $upload = $cpUpload->run('file_imgae', 'layanan', true, true, 'jpg|jpeg|png|gif');

        $this->file_imgae = '';

        if ($upload->status === true) {
          $this->file_imgae = $upload->data->base_path;
        }
      } else {
        $this->file_imgae = $this->input->post('file_imgae');
      }
      
      $this->updated_by = $this->session->userdata('user')['id'];
      $this->updated_date = date('Y-m-d H:i:s');
      $this->db->update($this->_table, $this, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been saved.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to save your data: ' . $th->getMessage());
    };

    return $response;
  }

  public function delete($id)
  {
    $response = array('status' => false, 'data' => 'No operation.');

    try {
      // Get current record to delete the image file
      $record = $this->getDetail(array('id' => $id));
      if($record && !empty($record->file_imgae)) {
        $filePath = FCPATH . $record->file_imgae;
        if(file_exists($filePath)) {
          unlink($filePath);
        }
      }
      
      $this->db->delete($this->_table, array('id' => $id));

      $response = array('status' => true, 'data' => 'Data has been deleted.');
    } catch (\Throwable $th) {
      $response = array('status' => false, 'data' => 'Failed to delete your data: ' . $th->getMessage());
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
      $response = array('status' => false, 'data' => 'Failed to truncate your data: ' . $th->getMessage());
    };

    return $response;
  }

  /**
   * getData_dtAjax - Method to handle server-side DataTables processing
   */
  function getData_dtAjax($config)
  {
      $draw    = $_REQUEST['draw'];
      $length  = $_REQUEST['length'];
      $start   = $_REQUEST['start'];
      $search  = $_REQUEST['search']['value'];
      $order   = $_REQUEST['order'][0];
      $columns = $_REQUEST['columns'];

      // Get config
      $select_column             = $config['select_column'] ?? null;
      $table_name                = $config['table_name'] ?? null;
      $order_column              = $config['order_column'] ?? 1;
      $order_column_dir          = $order['dir'] ?? $config['order_column_dir'];
      $static_conditional        = $config['static_conditional'] ?? [];
      $static_conditional_spec   = $config['static_conditional_spec'] ?? [];
      $static_conditional_in_key = $config['static_conditional_in_key'] ?? null;
      $static_conditional_in     = $config['static_conditional_in'] ?? [];
      $table_join                = $config['table_join'] ?? null;
      $filters                   = $config['filters'] ?? [];
      $group_by                  = $config['group_by'] ?? null;
      $columnItem = [];
      $columnSearchDisable = [];
      $filter_conditional = [];

      // Identifikasi kolom dan filter spesifik
      foreach ($columns as $item) {
          if (!empty($item['data'])) {
              $columnItem[] = $item['data'];
              if ($item['searchable'] === 'false') {
                  $columnSearchDisable[] = $item['data'];
              }
              if (!empty($item['search']['value'])) {
                  $val = trim(stripslashes($item['search']['value']), '^$');
                  $filter_conditional[$item['data']] = $val === 'null' ? null : $val;
              }
          }
      }

      // Column untuk pencarian global
      $columnNoFilter = array_merge(['no'], array_keys($static_conditional), array_keys($static_conditional_spec), $columnSearchDisable);
      $columnSearch = [];
      foreach ($columnItem as $item) {
          if (!in_array($item, $columnNoFilter)) {
              $col = ($table_name == 'customer') ? "customer." . $item : $item;
              $columnSearch[] = "LOWER(CAST($col AS CHAR)) LIKE '%" . strtolower($search) . "%'";
          }
      }

      // Helper to apply all filters and joins
      $apply_filters = function($db) use (
          $table_name, $select_column, $table_join,
          $static_conditional, $static_conditional_spec,
          $filter_conditional, $static_conditional_in_key, $static_conditional_in,
          $search, $columnSearch, $filters, $columnItem, $order, $order_column, $order_column_dir
      ) {
          $db->from($table_name);
          if (!empty($select_column)) {
              $db->select($select_column);
          }
          if (!empty($table_join)) {
              foreach ($table_join as $join) {
                  if (count($join) === 3) {
                      $db->join($join['table_name'], $join['expression'], $join['type']);
                  }
              }
          }
          if (!empty($static_conditional)) {
              $db->like($static_conditional);
          }
          if (!empty($static_conditional_spec)) {
              $db->where($static_conditional_spec);
          }
          if (!empty($filter_conditional)) {
              $db->where($filter_conditional);
          }
          if (!empty($static_conditional_in_key) && !empty($static_conditional_in)) {
              $db->where_in($static_conditional_in_key, $static_conditional_in);
          }
          if (!empty($search) && !empty($columnSearch)) {
              $db->where('(' . implode(' OR ', $columnSearch) . ')');
          }
          foreach ($filters as $key => $value) {
              if ($value && $value != 'all') {
                  if (strpos($key, '_start') !== false) {
                      $dateKey = str_replace('_start', '', $key);
                      if (isset($filters[$dateKey . '_start']) && isset($filters[$dateKey . '_end'])) {
                          $startVal = $filters[$dateKey . '_start'];
                          $endVal = $filters[$dateKey . '_end'];
                          if (!empty($startVal) && !empty($endVal)) {
                              $db->where("$dateKey BETWEEN '$startVal' AND '$endVal'");
                          }
                      }
                  } else if (strpos($key, '_end') !== false) {
                      continue;
                  } else if ($key == 'customer_id' && $table_name != 'view_harga_barang_detail') {
                      $db->group_start();
                      $db->where($key, $value);
                      $db->or_where('agen_id', $value);
                      $db->group_end();
                  } else if ($key == 'tanggal_kirim') {
                      $db->where("DATE(tanggal_kirim) = '$value'");
                  } else {
                      if ($key != 'tanggal_invoice' ) {
                          $db->where($key, $value);
                      }
                  }
              }
          }
      };

      // Hitung total records
      $this->db->from($table_name);
      if (!empty($static_conditional)) {
          $this->db->like($static_conditional);
      }
      if (!empty($static_conditional_spec)) {
          $this->db->where($static_conditional_spec);
      }
      if (!empty($filter_conditional)) {
          $this->db->where($filter_conditional);
      }
      if (!empty($static_conditional_in_key) && !empty($static_conditional_in)) {
          $this->db->where_in($static_conditional_in_key, $static_conditional_in);
      }
      $recordsTotal = $this->db->count_all_results();

      // Ambil data dengan filter
      $apply_filters($this->db);
      $orderBy  = $columnItem[$order['column']] ?? $columnItem[$order_column];
      $this->db->order_by($orderBy, $order_column_dir);
      if (!empty($group_by)) {
          $this->db->group_by($group_by);
      }
      $this->db->limit($length, $start);
      
      $results = $this->db->get()->result_array();

      // Hitung filter count
      $apply_filters($this->db);
      $recordsFiltered = $this->db->count_all_results();
      
      // Response
      return [
          'draw' => intval($draw),
          'recordsTotal' => $recordsTotal,
          'recordsFiltered' => $recordsFiltered,
          'data' => $results,
          'filter_cond' => $filter_conditional,
          'column_rendered' => $columnSearch,
          'filter_all' => array_merge($static_conditional, $static_conditional_spec, $filter_conditional),
      ];
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