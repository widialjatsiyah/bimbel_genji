<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Dashboard_siswa extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            // show_error('Akses ditolak', 403);
        }
        $this->load->model([
            'UserTryoutModel',
            'StudentProgressModel',
            'RecommendationModel',
            'TryoutModel',
            'UserMaterialProgressModel',
            'DailyChecklistModel',
			'TryoutClassModel'
        ]);
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];

        // Data untuk dashboard
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('dashboard_siswa'),
            'card_title' => 'Dashboard Siswa',
            'latest_tryout' => $this->UserTryoutModel->getLatestByUser($user_id),
            'progress' => $this->StudentProgressModel->getLatest($user_id),
            'recommendations' => $this->RecommendationModel->getUnreadByUser($user_id, 5),
            'available_tryouts' => $this->TryoutModel->getAvailableForUser($user_id),
            'material_progress' => $this->UserMaterialProgressModel->countProgress($user_id),
            'daily_checklist_today' => $this->DailyChecklistModel->getToday($user_id),
            'recent_activities' => $this->UserTryoutModel->getRecentActivities($user_id, 5),
			'scheduled_tryouts' => $this->TryoutClassModel->getScheduledForStudent($user_id)
        ];


        $this->template->set('title', 'Dashboard Siswa | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

	

	 public function history()
    {
        $user_id = $this->session->userdata('user')['id'];
        $data['tryouts'] = $this->UserTryoutModel->getHistoryByUser($user_id);
      $data = [
			'app' => $this->app(),
			'main_js' => $this->load_main_js('dashboard_siswa_history'),
			'card_title' => 'Riwayat Try Out',
			'tryouts' => $data['tryouts']
		];

		 $this->template->set('title', 'Riwayat Try Out | ' . $data['app']->app_name, TRUE);

        // $this->template->set('title', 'Riwayat Try Out | ' . $this->app()->app_name, TRUE);
        $this->template->load_view('history', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_chart_data()
    {
        $this->handle_ajax_request();
        $user_id = $this->session->userdata('user')['id'];
        $data = $this->StudentProgressModel->getChartData($user_id, 30); // 30 hari terakhir
        echo json_encode($data);
    }
}
