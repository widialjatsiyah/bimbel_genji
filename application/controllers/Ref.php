<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Ref extends AppBackend
{
  function __construct()
  {
    parent::__construct();

    $this->load->model(array(
      'AppModel',
      'BarangModel',
      'HargaBarangModel',
      'CustomerModel',
      // 'SupplierbarangModel',
      // 'FakturpembelianModel',
      // 'TujuanModel',
    ));
  }

  public function ajax_get_barang()
  {
    $this->handle_ajax_request();
    $keyword = $this->input->get('q');
    $customerId = $this->input->get('customer_id');
    $data = $this->BarangModel->getSearch(array('lower(kode_barang)', 'lower(nama_barang)'), strtolower($keyword));
    $result = array();

    if (count($data) > 0) {
      foreach ($data as $index => $item) {
        $harga = $this->HargaBarangModel->getDetail(array('barang_id' => $item->id, 'customer_id' => $customerId));
        if ($harga) {
          $item->harga = $harga->harga_agen;
          $item->hpp = $harga->hpp;
        } 
        $result['items'][] = array(
          'id' => $item->id, // Required for select2 result
          'text' => $item->kode_barang . ' | ' . $item->nama_barang, // Required for select2 result
          'kode_barang' => $item->kode_barang,
          'nama_barang' => $item->nama_barang,
          'satuan' => $item->satuan,
          'stock' => $item->stok,
          'stock_formated' => number_format($item->stok),
          'harga_satuan' => $item->harga,
          'harga_satuan_formated' => number_format($item->harga),
          'hpp' => $item->hpp,
          'hpp_formated' => number_format($item->hpp),
        );
      };
    };

    echo json_encode($result);
  }

  

  // public function ajax_get_customer_harga()
  // {
  //   $this->handle_ajax_request();
  //    $data = $this->HargaBarangModel->getAll(array('customer_id' => $supplierId));
  //   $result = array();

  //   if (count($data) > 0) {
  //     foreach ($data as $index => $item) {
  //       $result['items'][] = array(
  //         'id' => $item->id, // Required for select2 result
  //         'text' => $item->kode_barang . ' | ' . $item->nama_barang, // Required for select2 result
  //         'kode_barang' => $item->kode_barang,
  //         'nama_barang' => $item->nama_barang,
  //         'satuan' => $item->satuan,
  //         'stock' => $item->stok,
  //         'stock_formated' => number_format($item->stok),
  //         'harga_satuan' => $item->harga,
  //         'harga_satuan_formated' => number_format($item->harga),
  //         'hpp' => $item->hpp,
  //         'hpp_formated' => number_format($item->hpp),
  //       );
  //     };
  //   };

  //   echo json_encode($result);
  // }

  // public function ajax_get_faktur()
  // {
  //   $this->handle_ajax_request();
  //   $keyword = $this->input->get('q');
  //   $data = $this->FakturpembelianModel->getSearch('lower(no_faktur)', strtolower($keyword));
  //   $result = array();

  //   if (count($data) > 0) {
  //     foreach ($data as $index => $item) {
  //       $result['items'][] = array(
  //         'id' => $item->id_grid, // Required for select2 result
  //         'text' => $item->no_faktur, // Required for select2 result
  //         'no_faktur' => $item->no_faktur,
  //         'tanggal_faktur' => $item->tanggal_faktur,
  //         'source_id' => $item->source_id,
  //         'source_table_name' => $item->source_table_name,
  //         'source' => $item->source,
  //         'faktur_pembelian_keterangan' => $item->faktur_pembelian_keterangan,
  //         'faktur_pembelian_created_at' => $item->faktur_pembelian_created_at,
  //       );
  //     };
  //   };

  //   echo json_encode($result);
  // }

  // public function ajax_get_tujuan()
  // {
  //   $this->handle_ajax_request();
  //   $keyword = $this->input->get('q');
  //   $isKey = $this->input->get('key');
  //   $unit = $this->input->get('unit');
  //   $data = $this->TujuanModel->getSearch(array('nama_unit', 'sub_unit'), $keyword, $isKey, $unit);
  //   $result = array();

  //   if (count($data) > 0) {
  //     foreach ($data as $index => $item) {
  //       $result['items'][] = array(
  //         'id' => $item->combo_key, // Required for select2 result
  //         'text' => $item->combo_label, // Required for select2 result
  //         'unit_id' => $item->unit_id,
  //         'sub_unit_id' => $item->id,
  //         'nama_unit' => $item->nama_unit,
  //         'nama_sub_unit' => $item->nama_sub_unit
  //       );
  //     };
  //   };

  //   echo json_encode($result);
  // }
}
