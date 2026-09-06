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

	/**
	 * Import pengguna ke paket dari Excel.
	 * Kolom: A=email, B=package ID/nama paket (opsional), C=start_date,
	 * D=end_date, E=status, F=payment_status.
	 */
	public function import_users_from_excel($package_id)
	{
		$this->handle_ajax_request();
		$this->load->library('upload');

		$package = $this->PackageModel->getDetail(['id' => $package_id]);
		if (!$package) {
			echo json_encode(['status' => false, 'data' => 'Paket tidak ditemukan.']);
			return;
		}

		$config = [
			'upload_path' => './uploads/temp/',
			'allowed_types' => 'xlsx|xls',
			'max_size' => '2048',
			'file_ext_tolower' => true,
		];
		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0755, true);
		}
		$this->upload->initialize($config);

		if (!$this->upload->do_upload('import_file')) {
			echo json_encode(['status' => false, 'data' => $this->upload->display_errors()]);
			return;
		}

		$upload_data = $this->upload->data();
		$file_path = $config['upload_path'] . $upload_data['file_name'];
		$imported = 0;
		$failed = 0;
		$errors = [];
		$seen_emails = [];

		try {
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
			$sheet = $spreadsheet->getActiveSheet();
			$last_row = $sheet->getHighestRow();

			$this->db->trans_begin();
			for ($row = 2; $row <= $last_row; $row++) {
				$email = strtolower(trim((string) $sheet->getCell('A' . $row)->getFormattedValue()));
				$package_value = trim((string) $sheet->getCell('B' . $row)->getFormattedValue());
				$start_date = trim((string) $sheet->getCell('C' . $row)->getFormattedValue());
				$end_date = trim((string) $sheet->getCell('D' . $row)->getFormattedValue());
				$status = strtolower(trim((string) $sheet->getCell('E' . $row)->getFormattedValue()));
				$payment_status = strtolower(trim((string) $sheet->getCell('F' . $row)->getFormattedValue()));

				// Compatibility with the previous five-column template.
				if ($package_value !== '' && strtotime($package_value) !== false) {
					$payment_status = $status;
					$status = $end_date;
					$end_date = $start_date;
					$start_date = $package_value;
					$package_value = '';
				}

				if ($email === '') {
					if ($package_value === '' && $start_date === '' && $end_date === '' && $status === '' && $payment_status === '') {
						continue;
					}
					$failed++;
					$errors[] = 'Baris ' . $row . ': Email wajib diisi.';
					continue;
				}
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$failed++;
					$errors[] = 'Baris ' . $row . ': Format email tidak valid.';
					continue;
				}

				$row_package = $package;
				if ($package_value !== '') {
					$row_package = is_numeric($package_value)
						? $this->PackageModel->getDetail(['id' => $package_value])
						: $this->db->where('LOWER(name)', strtolower($package_value))->get('packages')->row();
					if (!$row_package) {
						$failed++;
						$errors[] = 'Baris ' . $row . ': Paket tidak ditemukan (' . $package_value . ').';
						continue;
					}
				}
				$row_package_id = $row_package->id;
				$default_start = date('Y-m-d');
				$default_end = ((int) $row_package->duration_days > 0)
					? date('Y-m-d', strtotime('+' . (int) $row_package->duration_days . ' days'))
					: '2099-12-31';

				$user = $this->db->where('LOWER(email)', $email)->get('user')->row();
				if (!$user) {
					$failed++;
					$errors[] = 'Baris ' . $row . ': Email user tidak ditemukan (' . $email . ').';
					continue;
				}

				$duplicate_key = $email . '|' . $row_package_id;
				if (isset($seen_emails[$duplicate_key])) {
					$failed++;
					$errors[] = 'Baris ' . $row . ': Email sudah berulang untuk paket ini.';
					continue;
				}
				$seen_emails[$duplicate_key] = true;

				$exists = $this->db->where([
					'user_id' => $user->id,
					'package_id' => $row_package_id,
				])->count_all_results('user_packages');
				if ($exists > 0) {
					$failed++;
					$errors[] = 'Baris ' . $row . ': User sudah terdaftar pada paket ini (' . $email . ').';
					continue;
				}

				$start_date = $this->normalize_import_date($start_date, $default_start);
				$end_date = $this->normalize_import_date($end_date, $default_end);
				if ($start_date === false || $end_date === false || $end_date < $start_date) {
					$failed++;
					$errors[] = 'Baris ' . $row . ': Rentang tanggal tidak valid.';
					continue;
				}

				$status = in_array($status, ['active', 'expired', 'cancelled'], true) ? $status : 'active';
				$payment_status = in_array($payment_status, ['pending', 'paid', 'failed'], true) ? $payment_status : 'pending';
				$this->db->insert('user_packages', [
					'user_id' => $user->id,
					'package_id' => $row_package_id,
					'start_date' => $start_date,
					'end_date' => $end_date,
					'status' => $status,
					'payment_status' => $payment_status,
				]);
				if ($this->db->affected_rows() !== 1) {
					$failed++;
					$errors[] = 'Baris ' . $row . ': Gagal menyimpan relasi user-package.';
					continue;
				}
				$imported++;
			}

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				echo json_encode(['status' => false, 'data' => 'Import dibatalkan karena terjadi kesalahan database.']);
				return;
			}
			$this->db->trans_commit();

			$message = 'Berhasil mengimpor ' . $imported . ' user ke paket.';
			if ($failed > 0) {
				$message .= ' Gagal: ' . $failed . '. ' . implode(' ', array_slice($errors, 0, 5));
			}
			echo json_encode(['status' => true, 'data' => $message]);
		} catch (Exception $e) {
			$this->db->trans_rollback();
			echo json_encode(['status' => false, 'data' => 'Gagal memproses Excel: ' . $e->getMessage()]);
		} finally {
			if (file_exists($file_path)) {
				unlink($file_path);
			}
		}
	}

	private function normalize_import_date($value, $fallback)
	{
		if ($value === '') {
			return $fallback;
		}
		if (is_numeric($value)) {
			$date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
			return $date->format('Y-m-d');
		}
		$timestamp = strtotime($value);
		return $timestamp ? date('Y-m-d', $timestamp) : false;
	}

	public function download_user_package_template($package_id)
	{
		$package = $this->PackageModel->getDetail(['id' => $package_id]);
		if (!$package) {
			show_404();
		}
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$headers = ['Email User', 'Paket (ID/Nama)', 'Tanggal Mulai', 'Tanggal Akhir', 'Status', 'Status Pembayaran'];
		foreach ($headers as $index => $header) {
			$sheet->setCellValueByColumnAndRow($index + 1, 1, $header);
		}
		$sheet->fromArray([['user@example.com', $package->name, date('Y-m-d'), '', 'active', 'paid']], null, 'A2');
		$sheet->getStyle('A1:F1')->getFont()->setBold(true);
		foreach (range('A', 'F') as $column) {
			$sheet->getColumnDimension($column)->setWidth(22);
		}
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="template_import_user_package.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		exit;
	}
}
