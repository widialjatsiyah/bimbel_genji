<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Settings_home extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
       
        $this->load->model(['SettingHomeModel']);
    }

    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('settings_home'),
            'card_title' => 'Pengaturan Website',
            'settings' => $this->SettingHomeModel->getAllSettings()
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function save()
    {
        $this->handle_ajax_request();

        // Handle text inputs
        $this->SettingHomeModel->updateBatch($this->input->post());

        // Handle file uploads
        $uploadFields = [
            'site_logo' => 'logo',
            'favicon' => 'favicon'
        ];

        foreach ($uploadFields as $key => $fieldName) {
            if (!empty($_FILES[$fieldName]['name'])) {
                $upload = $this->SettingHomeModel->handleUpload($fieldName, $key, 'settings');
                if (!$upload['status']) {
                    echo json_encode(['status' => false, 'data' => $upload['data']]);
                    return;
                }
            }
        }

        echo json_encode(['status' => true, 'data' => 'Pengaturan berhasil disimpan.']);
    }
}
