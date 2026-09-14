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

        $ranking_all = $this->UserTryoutModel->getOverallRanking();
        $my_rank = null;
        $my_rank_data = null;
        foreach ($ranking_all as $i => $r) {
            if ((int)$r->user_id === (int)$user_id) {
                $my_rank = $i + 1;
                $my_rank_data = $r;
                break;
            }
        }

        // Ranking berdasarkan paket aktif → tryout → sesi
        $active_tryout_ids = $this->UserTryoutModel->getActivePackageTryoutIds($user_id);
        $active_tryout_id_list = array_column($active_tryout_ids, 'item_id');
        $sessions = $this->UserTryoutModel->getActiveSessionsByTryoutIds($active_tryout_id_list);

        $session_cards = [];
        foreach ($sessions as $sess) {
            $participants = $this->UserTryoutModel->getSessionParticipants($sess->session_id);
            $user_rank = $this->UserTryoutModel->getUserRankInSession($sess->session_id, $user_id);

            // Filter: hanya peserta yang punya user_tryouts dalam 3 bulan terakhir
            $recent_participants = [];
            foreach ($participants as $p) {
                $recent_participants[] = $p;
            }

            $session_cards[] = [
                'session' => $sess,
                'participants' => array_slice($recent_participants, 0, 20),
                'total_participants' => count($participants),
                'user_rank' => $user_rank,
            ];
        }

        // Max 5 card sesi
        $session_cards = array_slice($session_cards, 0, 5);

        // Data untuk dashboard
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('dashboard_siswa'),
            'card_title' => 'Dashboard Siswa',
            'latest_tryout' => $this->UserTryoutModel->getLatestByUser($user_id),
            'progress' => $this->StudentProgressModel->getLatest($user_id),
            'recommendations' => $this->RecommendationModel->getUnreadByUser($user_id, 5),
            'available_tryouts' => $this->TryoutModel->getAvailableForStudent($user_id),
            'material_progress' => $this->UserMaterialProgressModel->countProgress($user_id),
            'daily_checklist_today' => $this->DailyChecklistModel->getToday($user_id),
            'recent_activities' => $this->UserTryoutModel->getRecentActivities($user_id, 5),
            'scheduled_tryouts' => $this->TryoutClassModel->getScheduledForStudent($user_id),
            'ranking_top' => array_slice($ranking_all, 0, 20),
            'ranking_total' => count($ranking_all),
            'my_rank' => $my_rank,
            'my_rank_data' => $my_rank_data,
            'session_cards' => $session_cards,
        ];


        $this->template->set('title', 'Dashboard Siswa | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

	

	 public function history()
    {
        $user_id = $this->session->userdata('user')['id'];
        $data['tryouts'] = $this->UserTryoutModel->getHistoryByUserWithSession($user_id);
      $data = [
			'app' => $this->app(),
			'main_js' => $this->load_main_js('dashboard_siswa'),
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
