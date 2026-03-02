<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class My_payment extends AppBackend
{
	public function __construct()
	{
		parent::__construct();
		// Hanya untuk role student
		$this->load->model(['AppModel']);
	}

	public function index()
	{
		$data = [
			'app' => $this->app(),
			'main_js' => $this->load_main_js('my_payment', false, array('client_keys' => $this->config->item('midtrans_client_key', 'midtrans'))),
			'card_title' => 'Riwayat Pembayaran',
		];
		$this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
		$this->template->load_view('index', $data, TRUE);
		$this->template->render();
	}

	public function ajax_get_all()
	{
		$this->handle_ajax_request();
		$user_id = $this->session->userdata('user')['id'];

		$dtAjax_config = [
			'select_column' => [
				'transactions.id',
				'transactions.order_id',
				'packages.name as package_name',
				'packages.description',
				'transactions.created_at',
				'transactions.transaction_status',
				'transactions.gross_amount',
				'packages.price'
			],
			'table_name' => 'transactions',
			'table_join' => [
				[
					'table_name' => 'packages',
					'expression' => 'packages.id = transactions.package_id',
					'type' => 'left'
				]
			],
			'order_column' => 3,
			'order_column_dir' => 'desc',
		];

		// Tambahkan conditional jika bukan administrator
		if ($this->session->userdata('user')['role'] == 'student') {
			$dtAjax_config['static_conditional'] = [
				'transactions.user_id' => $user_id
			];
		}
		$response = $this->AppModel->getData_dtAjax($dtAjax_config);
		echo json_encode($response);
	}
}
