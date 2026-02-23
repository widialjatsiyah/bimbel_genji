<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Example extends AppBackend
{
  function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'AppModel',
      'ExampleModel',
    ));
    $this->load->library('form_validation');
  }

  public function index()
  {
    // Get combobox list
    $listCombobox_model = (object) array(
      array('value' => 'Satu', 'text' => 'Label Satu'),
      array('value' => 'Dua', 'text' => 'Label Dua'),
      array('value' => 'Tiga', 'text' => 'Label Tiga'),
    );
    /*
      Untuk mengambil data dari tabel lain return harus dalam bentuk multiple array/object :
      $listCombobox_model = $this->NamaModel->getAll();
    */
    // END ## Get combobox list

    $data = array(
      'app' => $this->app(),
      'main_js' => $this->load_main_js('example'),
      'card_title' => 'Example',
      'list_combobox' => $this->init_list($listCombobox_model, 'value', 'text'),
    );
    $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
    $this->template->load_view('index', $data, TRUE);
    $this->template->render();
  }

  public function ajax_get_all()
  {
    $this->handle_ajax_request();
    $dtAjax_config = array(
      'table_name' => 'example',
      'order_column' => 6, // index in datatables column
      'order_column_dir' => 'desc',
    );
    $response = $this->AppModel->getData_dtAjax($dtAjax_config);
    echo json_encode($response);
  }

  public function ajax_save($id = null)
  {
    $this->handle_ajax_request();
    $this->form_validation->set_rules($this->ExampleModel->rules());

    if ($this->form_validation->run() === true) {
      if (is_null($id)) {
        echo json_encode($this->ExampleModel->insert());
      } else {
        echo json_encode($this->ExampleModel->update($id));
      };
    } else {
      $errors = validation_errors('<div>- ', '</div>');
      echo json_encode(array('status' => false, 'data' => $errors));
    };
  }

  public function ajax_delete($id)
  {
    $this->handle_ajax_request();
    echo json_encode($this->ExampleModel->delete($id));
  }
}
