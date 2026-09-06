<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class User_package extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'AppModel',
            'UserPackageModel',
            'UserModel',        // asumsikan ada model untuk tabel user
            'PackageModel',      // model paket yang sudah dibuat
            'TransactionModel'
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        // Ambil data user dan paket untuk combobox
        $users = $this->UserModel->getAll([], 'nama_lengkap', 'asc');
        $packages = $this->PackageModel->getAll(['is_active' => 1], 'name', 'asc');

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('user_package'),
            'card_title' => 'Paket Pengguna',
            'list_user' => $this->init_list($users, 'id', 'nama_lengkap'),
            'list_package' => $this->init_list($packages, 'id', 'name')
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();

        // Konfigurasi DataTables dengan join ke user dan packages
        $dtAjax_config = array(
            'select_column' => [
                'user_packages.id',
                'user.nama_lengkap as user_name',
                'packages.name as package_name',
                'user_packages.start_date',
                'user_packages.end_date',
                'user_packages.status',
                'user_packages.payment_status',
                'user_packages.user_id',
                'user_packages.package_id',
                'transactions.id as transaction_id',
                'transactions.manual_proof',
                'transactions.manual_note',
                'transactions.manual_verification_status',
                'transactions.payment_type'
            ],
            'table_name' => 'user_packages',
            'table_join' => [
                [
                    'table_name' => 'user',
                    'expression' => 'user.id = user_packages.user_id',
                    'type' => 'left'
                ],
                [
                    'table_name' => 'packages',
                    'expression' => 'packages.id = user_packages.package_id',
                    'type' => 'left'
                ],
                [
                    'table_name' => 'transactions',
                    'expression' => 'transactions.id = (SELECT MAX(t2.id) FROM transactions t2 WHERE t2.user_id = user_packages.user_id AND t2.package_id = user_packages.package_id)',
                    'type' => 'left'
                ]
            ],
            'order_column' => 3, // start_date
            'order_column_dir' => 'desc',
        );
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->form_validation->set_rules($this->UserPackageModel->rules());

        if ($this->form_validation->run() === true) {
            if (is_null($id)) {
                echo json_encode($this->UserPackageModel->insert());
            } else {
                echo json_encode($this->UserPackageModel->update($id));
            }
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(array('status' => false, 'data' => $errors));
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        echo json_encode($this->UserPackageModel->delete($id));
    }

    public function ajax_verify_manual($transaction_id, $decision)
    {
        $this->handle_ajax_request();
        $admin = $this->session->userdata('user');
        if (!$admin || !in_array($admin['role'], ['Administrator', 'school_admin'])) {
            echo json_encode(['status' => false, 'data' => 'Akses ditolak.']);
            return;
        }

        $transaction = $this->TransactionModel->getDetail(['id' => $transaction_id]);
        if (!$transaction || $transaction->payment_type !== 'manual' || $transaction->manual_verification_status !== 'pending') {
            echo json_encode(['status' => false, 'data' => 'Bukti pembayaran tidak menunggu verifikasi.']);
            return;
        }
        if (!in_array($decision, ['approve', 'reject'], true)) {
            echo json_encode(['status' => false, 'data' => 'Keputusan verifikasi tidak valid.']);
            return;
        }

        $now = date('Y-m-d H:i:s');
        if ($decision === 'reject') {
            $this->db->where('id', $transaction_id)->update('transactions', [
                'transaction_status' => 'deny',
                'manual_verification_status' => 'rejected',
                'manual_verified_by' => $admin['id'],
                'manual_verified_at' => $now,
            ]);
            echo json_encode(['status' => true, 'data' => 'Bukti pembayaran ditolak.']);
            return;
        }

        $package = $this->PackageModel->getDetail(['id' => $transaction->package_id]);
        if (!$package) {
            echo json_encode(['status' => false, 'data' => 'Paket transaksi tidak ditemukan.']);
            return;
        }
        $start_date = date('Y-m-d');
        $end_date = (int) $package->duration_days > 0
            ? date('Y-m-d', strtotime('+' . (int) $package->duration_days . ' days'))
            : '2099-12-31';

        $this->db->trans_begin();
        $this->db->where('id', $transaction_id)->update('transactions', [
            'transaction_status' => 'settlement',
            'manual_verification_status' => 'approved',
            'manual_verified_by' => $admin['id'],
            'manual_verified_at' => $now,
        ]);
        $existing = $this->UserPackageModel->getDetail([
            'user_id' => $transaction->user_id,
            'package_id' => $transaction->package_id,
        ]);
        if ($existing) {
            $this->db->where('id', $existing->id)->update('user_packages', [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => 'active',
                'payment_status' => 'paid',
            ]);
        } else {
            $this->db->insert('user_packages', [
                'user_id' => $transaction->user_id,
                'package_id' => $transaction->package_id,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => 'active',
                'payment_status' => 'paid',
            ]);
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            echo json_encode(['status' => false, 'data' => 'Gagal mengaktifkan paket.']);
            return;
        }
        $this->db->trans_commit();
        echo json_encode(['status' => true, 'data' => 'Bukti valid. Paket user sudah diaktifkan.']);
    }
}
