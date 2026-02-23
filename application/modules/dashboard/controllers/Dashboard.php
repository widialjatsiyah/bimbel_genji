<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Dashboard extends AppBackend
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'NotificationModel',
			'DashboardModel'
		));
	}

	public function home()
	{
		$data = array(
			'app' => $this->app(),
			'main_js' => $this->load_main_js('dashboard'),
			'page_title' => 'ٱلسَّلَامُ عَلَيْكُمْ‎',
			'page_subTitle' => 'Welcome to ' . $this->app()->app_name . ' v' . $this->app()->app_version,
		);

		$this->template->set('title', $data['app']->app_name, TRUE);
		$this->template->load_view('index', $data, TRUE);
		$this->template->render();
	}
	 public function index()
    {
        $user = $this->session->userdata('user');
		// $role = $user['role'];
		// var_dump($role); // Debug: Tampilkan data user di log browser
        if (!$user) {
            redirect('login');
        }

        switch ($user['role']) {
            case 'admin':
                redirect('dashboard/home');
                break;
            case 'tutor':
                redirect('dashboard_tutor');
                break;
            case 'school_admin':
                redirect('dashboard_sekolah');
                break;
            case 'student':
				redirect('dashboard_siswa');
                break;
            default:
                redirect('dashboard/home');
                break;
        }
    }
}
