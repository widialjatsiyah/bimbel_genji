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
            'PackageModel'       // model paket yang sudah dibuat
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
                'user_packages.package_id'
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
}
