<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Form extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'UnitModel',
      'SubunitModel',
      'JeniskerusakanModel',
      'PerbaikanModel',
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('form'),
      'page_title' => 'Permintaan Perbaikan & Pemeliharaan | ' . $this->app()->app_name,
      'list_unit' => $this->init_list($this->UnitModel->getAll(array(), 'nama_unit'), 'nama_unit', 'nama_unit'),
      'list_sub_unit' => $this->init_list($this->SubunitModel->getAll(array(), 'nama_sub_unit'), 'nama_sub_unit', 'nama_sub_unit'),
      'list_jenis_kerusakan' => $this->init_list($this->JeniskerusakanModel->getAll(array(), 'nama_kerusakan'), 'nama_kerusakan', 'nama_kerusakan'),
    );
    $this->load->view('index', $data);
  }

  public function ajax_get_sub_unit()
  {
    $this->handle_ajax_request();
    $unit = $this->input->post('unit');
    $unitData = $this->UnitModel->getDetail(array('LOWER(nama_unit)' => strtolower($unit)));

    if (!is_null($unitData)) {
      $response = $this->SubunitModel->getAll(array('unit_id' => $unitData->id));
    } else {
      $response = array();
    };

    echo json_encode($response);
  }

  public function ajax_submit()
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->PerbaikanModel->rules());

    if ($this->form_validation->run() === true) {
      echo json_encode($this->PerbaikanModel->insert());
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }
}
