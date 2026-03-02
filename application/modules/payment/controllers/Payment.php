<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');
// require_once FCPATH . 'vendor/autoload.php'; // Load Midtrans library via Composer

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class Payment extends AppBackend
{
	public function __construct()
	{
		parent::__construct();



		if (!$this->session->userdata('user')) {
			redirect('login');
		}
		$this->config->load('midtrans', TRUE);
		$this->load->model(['PackageModel', 'TransactionModel', 'UserPackageModel']);

		// Konfigurasi Midtrans
		Config::$serverKey = $this->config->item('midtrans_server_key', 'midtrans');
		Config::$isProduction = $this->config->item('midtrans_is_production', 'midtrans');
		Config::$isSanitized = true;
		Config::$is3ds = true;
	}

	public function checkout($package_id)
	{
		$user = $this->session->userdata('user');
		$package = $this->PackageModel->getDetail(['id' => $package_id]);

		if (!$package) {
			show_404();
		}

		// Generate order ID unik
		$order_id = 'ORDER-' . date('Ymd') . '-' . $user['id'] . '-' . time();

		// Simpan transaksi awal dengan status pending
		$transaction_data = [
			'order_id' => $order_id,
			'user_id' => $user['id'],
			'package_id' => $package->id,
			'gross_amount' => $package->price,
			'transaction_status' => 'pending'
		];
		$this->TransactionModel->insert($transaction_data);

		// Parameter untuk Snap Midtrans [citation:8]
		$params = [
			'transaction_details' => [
				'order_id' => $order_id,
				'gross_amount' => (int) $package->price,
			],
			'customer_details' => [
				'first_name' => $user['nama_lengkap'],
				'email' => $user['email'],
				'phone' => isset($user['phone']) ? $user['phone'] : '',
			],
			'item_details' => [
				[
					'id' => $package->id,
					'price' => (int) $package->price,
					'quantity' => 1,
					'name' => $package->name
				]
			],
			'callbacks' => [
				'finish' => base_url('payment/finish?order_id=' . $order_id),
				'unfinish' => base_url('payment/unfinish'),
				'error' => base_url('payment/error')
			]
		];

		try {
			$snapToken = Snap::getSnapToken($params);

			// Update snap_token ke database
			$this->TransactionModel->update($order_id, ['snap_token' => $snapToken]);
			$client_key = $this->config->item('midtrans_client_key', 'midtrans');
			$data = [
				'app' => $this->app(),
				'main_js' => $this->load_main_js(
					'payment',
					false,
					array('client_key' => $client_key, 'snap_token' => $snapToken, 'order_id' => $order_id)
				),
				'card_title' => 'Checkout',
				'package' => $package,
				'snap_token' => $snapToken,
				'order_id' => $order_id,
				'client_key' => $client_key
			];
			$this->template->set('title', 'Checkout | ' . $data['app']->app_name, TRUE);
			$this->template->load_view('checkout', $data, TRUE);
			$this->template->render();
		} catch (Exception $e) {
			echo 'Error: ' . $e->getMessage();
		}
	}

	public function notification()
	{
		// Handle notifikasi dari Midtrans (webhook)
		$this->load->library('input');
		$json_result = file_get_contents('php://input');
		$notification = json_decode($json_result);

		if ($notification) {
			$order_id = $notification->order_id;
			$transaction_status = $notification->transaction_status;
			$payment_type = $notification->payment_type;

			// Verifikasi signature [citation:3]
			$transaction = $this->TransactionModel->getByOrderId($order_id);
			if ($transaction) {
				$serverKey = Config::$serverKey;
				$hashed = hash("sha512", $order_id . $notification->status_code . $notification->gross_amount . $serverKey);

				if ($hashed == $notification->signature_key) {
					// Update status transaksi
					$update_data = [
						'transaction_status' => $transaction_status,
						'payment_type' => $payment_type,
						'midtrans_response' => json_encode($notification)
					];
					$this->TransactionModel->update($order_id, $update_data);

					// Jika status settlement atau capture, aktifkan paket untuk user
					if (in_array($transaction_status, ['settlement', 'capture'])) {
						$this->_activate_package($transaction->user_id, $transaction->package_id);
					}
				}
			}
		}

		http_response_code(200);
	}

	public function finish()
	{
		$order_id = $this->input->get('order_id');
		$transaction = $this->TransactionModel->getByOrderId($order_id);

		if (!$transaction) {
			show_404();
		}

		$data = [
			'app' => $this->app(),
			'card_title' => 'Pembayaran Selesai',
			'transaction' => $transaction
		];
		$this->template->set('title', 'Pembayaran Selesai | ' . $data['app']->app_name, TRUE);
		$this->template->load_view('finish', $data, TRUE);
		$this->template->render();
	}

	private function _activate_package($user_id, $package_id)
	{
		$package = $this->PackageModel->getDetail(['id' => $package_id]);
		if (!$package) return;

		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d', strtotime("+{$package->duration_days} days"));

		$user_package_data = [
			'user_id' => $user_id,
			'package_id' => $package_id,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'status' => 'active',
			'payment_status' => 'paid'
		];
		$this->UserPackageModel->insert($user_package_data);
	}

	public function get_snap_token($order_id)
	{
		$user = $this->session->userdata('user');
		$transaction = $this->TransactionModel->getByOrderId($order_id);

		if (!$transaction || $transaction->user_id != $user['id']) {
			echo json_encode(['status' => false, 'message' => 'Transaksi tidak ditemukan']);
			return;
		}

		if ($transaction->transaction_status != 'pending') {
			echo json_encode(['status' => false, 'message' => 'Transaksi sudah selesai']);
			return;
		}

		// Coba gunakan snap token yang tersimpan
		if (!empty($transaction->snap_token)) {
			echo json_encode(['status' => true, 'snap_token' => $transaction->snap_token]);
			return;
		}

		// Jika token kosong, regenerasi
		$this->load->model('PackageModel');
		$package = $this->PackageModel->getDetail(['id' => $transaction->package_id]);
		if (!$package) {
			echo json_encode(['status' => false, 'message' => 'Paket tidak ditemukan']);
			return;
		}

		$params = [
			'transaction_details' => [
				'order_id' => $order_id,
				'gross_amount' => (int) $transaction->gross_amount,
			],
			'customer_details' => [
				'first_name' => $user['nama_lengkap'],
				'email' => $user['email'],
			],
			'item_details' => [
				[
					'id' => $package->id,
					'price' => (int) $package->price,
					'quantity' => 1,
					'name' => $package->name
				]
			]
		];

		try {
			$snapToken = \Midtrans\Snap::getSnapToken($params);
			// Update token di database
			$this->TransactionModel->update($order_id, ['snap_token' => $snapToken]);
			echo json_encode(['status' => true, 'snap_token' => $snapToken]);
		} catch (Exception $e) {
			echo json_encode(['status' => false, 'message' => $e->getMessage()]);
		}
	}
}
