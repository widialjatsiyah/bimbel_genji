<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Package extends AppBackend
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'AppModel',
			'PackageModel'
		));
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data = array(
			'app' => $this->app(),
			'main_js' => $this->load_main_js('package'),
			'card_title' => 'Paket Berlangganan',
		);
		$this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
		$this->template->load_view('index', $data, TRUE);
		$this->template->render();
	}

	public function ajax_get_all()
	{
		$this->handle_ajax_request();
		$dtAjax_config = array(
			'table_name' => 'packages',
			'order_column' => 1, // kolom name
			'order_column_dir' => 'asc',
		);
		$response = $this->AppModel->getData_dtAjax($dtAjax_config);
		// Decode features JSON untuk ditampilkan di tabel
		foreach ($response['data'] as &$row) {
			if (!empty($row['features'])) {
				$features = json_decode($row['features'], true);
				$row['features_display'] = is_array($features) ? implode(', ', $features) : '';
			} else {
				$row['features_display'] = '';
			}
		}
		echo json_encode($response);
	}

	public function ajax_save($id = null)
	{
		$this->handle_ajax_request();
		$this->form_validation->set_rules($this->PackageModel->rules());

		if ($this->form_validation->run() === true) {
			if (is_null($id)) {
				echo json_encode($this->PackageModel->insert());
			} else {
				echo json_encode($this->PackageModel->update($id));
			}
		} else {
			$errors = validation_errors('<div>- ', '</div>');
			echo json_encode(array('status' => false, 'data' => $errors));
		}
	}

	public function ajax_delete($id)
	{
		$this->handle_ajax_request();
		echo json_encode($this->PackageModel->delete($id));
	}

	/**
	 * Halaman detail paket (kelola item)
	 */
	public function detail($id)
	{
		$package = $this->PackageModel->getDetail(['id' => $id]);
		if (!$package) show_404();

		$this->load->model('PackageItemModel');
		$items = $this->PackageItemModel->getItemsByPackage($id);

		// Ambil nama item untuk ditampilkan
		foreach ($items as &$item) {
			switch ($item->item_type) {
				case 'tryout':
					$this->load->model('TryoutModel');
					$obj = $this->TryoutModel->getDetail(['id' => $item->item_id]);
					$item->item_name = $obj ? $obj->title : '-';
					break;
				case 'class':
					$this->load->model('ClassModel');
					$obj = $this->ClassModel->getDetail(['id' => $item->item_id]);
					$item->item_name = $obj ? $obj->name : '-';
					break;
				case 'material':
					$this->load->model('MaterialModel');
					$obj = $this->MaterialModel->getDetail(['id' => $item->item_id]);
					$item->item_name = $obj ? $obj->title : '-';
					break;
				default:
					$item->item_name = '-';
			}
		}

		$data = [
			'app' => $this->app(),
			'main_js' => $this->load_main_js('package/views/main_detail.js.php',true,
			array(
				'package_id' => $id
			)),
			'card_title' => 'Detail Paket: ' . $package->name,
			'package' => $package,
			'items' => $items
		];
		$this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
		$this->template->load_view('detail', $data, TRUE);
		$this->template->render();
	}

	/**
	 * AJAX: Mendapatkan item yang tersedia untuk ditambahkan ke paket
	 */
	public function ajax_get_available_items()
	{
		$this->handle_ajax_request();
		$package_id = $this->input->get('package_id');
		$type = $this->input->get('type');
		$this->load->model('PackageItemModel');
		$data = [];
		switch ($type) {
			case 'tryout':
				$data = $this->PackageItemModel->getAvailableTryouts($package_id);
				break;
			case 'class':
				$data = $this->PackageItemModel->getAvailableClasses($package_id);
				break;
			case 'material':
				$data = $this->PackageItemModel->getAvailableMaterials($package_id);
				break;
			default:
				$data = [];
		}
		echo json_encode($data);
	}

	/**
	 * AJAX: Menambahkan item ke paket
	 */
	public function ajax_add_item()
	{
		$this->handle_ajax_request();
		$package_id = $this->input->post('package_id');
		$type = $this->input->post('type');
		$item_id = $this->input->post('item_id');
		$this->load->model('PackageItemModel');
		$res = $this->PackageItemModel->addItem($package_id, $type, $item_id);
		echo json_encode($res);
	}

	/**
	 * AJAX: Menghapus item dari paket
	 */
	public function ajax_remove_item($id)
	{
		$this->handle_ajax_request();
		$this->load->model('PackageItemModel');
		$res = $this->PackageItemModel->removeItem($id);
		echo json_encode($res);
	}
}
