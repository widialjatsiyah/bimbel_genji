<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Register extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('StudentDetailModel');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('register'),
            'page_title' => 'Daftar | ' . $this->app()->app_name
        );
        $this->load->view('index', $data);
    }

    public function do_register()
    {
        $this->form_validation->set_rules($this->UserModel->rules_register_student());

        if ($this->form_validation->run() == false) {
            // Jika validasi gagal, tampilkan kembali form dengan error
            $data = array(
                'app' => $this->app(),
                'main_js' => $this->load_main_js('register'),
                'page_title' => 'Daftar | ' . $this->app()->app_name
            );
            $this->load->view('index', $data);
        } else {

            // Data untuk tabel user
            $user_data = [
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'password' =>  md5($this->input->post('password')),
                'role' => 'student',
                'created_date' => date('Y-m-d H:i:s')
            ];
            $user_id = $this->UserModel->insertData($user_data);

            if ($user_id) {
                // Data untuk tabel student_details
                $student_data = [
                    'user_id' => $user_id,
                    'nis' => $this->input->post('nis'),
                    'asal_sekolah' => $this->input->post('asal_sekolah'),
                    'pilihan_kampus1' => $this->input->post('pilihan_kampus1'),
                    'pilihan_kampus2' => $this->input->post('pilihan_kampus2')
                ];
                $this->StudentDetailModel->insert($student_data);

                // Set session login
                $session_data = [
                    'id' => $user_id,
                    'username' => $user_data['username'],
                    'nama_lengkap' => $user_data['nama_lengkap'],
                    'role' => 'student',
                    'is_login' => true
                ];
                $this->session->set_userdata('user', $session_data);

                redirect('select_package');
            } else {
                $this->session->set_flashdata('error', 'Gagal mendaftar, coba lagi.');
                redirect('register');
            }
        }
    }
}
