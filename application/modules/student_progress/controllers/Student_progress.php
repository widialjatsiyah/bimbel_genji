<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Student_progress extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        // Hanya untuk siswa? Atau untuk tutor/orang tua juga? Kita batasi dulu untuk siswa.
        if ($this->session->userdata('user')['role'] != 'student' and $this->session->userdata('user')['role'] != 'Administrator') {
            show_error('Akses ditolak.', 403);
        }
        $this->load->model([
            'StudentProgressModel',
            'UserTryoutModel',
            'SessionResultModel',
            'DailyChecklistModel',
            'UserMaterialProgressModel'
        ]);
    }

    public function index()
    {
        $user_id = $this->session->userdata('user')['id'];
        $latest = $this->StudentProgressModel->getLatest($user_id);
        $history = $this->StudentProgressModel->getHistory($user_id, 30);

        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('student_progress'),
            'card_title' => 'Perkembangan Saya',
            'latest' => $latest,
            'history' => $history
        );
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_calculate()
    {
        $this->handle_ajax_request();
        $user_id = $this->session->userdata('user')['id'];
        $result = $this->calculateProgress($user_id);
        echo json_encode($result);
    }

    private function calculateProgress($user_id)
    {
        // 1. Skor Akademik (dari tryout)
        $akademik = $this->calculateAcademicScore($user_id);

        // 2. Skor Konsistensi (dari daily checklist dan aktivitas)
        $konsistensi = $this->calculateConsistencyScore($user_id);

        // 3. Skor Psikologis (dari mood dan ketahanan)
        $psikologis = $this->calculatePsychologicalScore($user_id);

        // 4. Skor Spiritual (dari daily checklist ibadah)
        $spiritual = $this->calculateSpiritualScore($user_id);

        // Bobot: akademik 60%, konsistensi 20%, psikologis 10%, spiritual 10%
        $skor_kesiapan = ($akademik * 0.6) + ($konsistensi * 0.2) + ($psikologis * 0.1) + ($spiritual * 0.1);

        // Tentukan status
        if ($skor_kesiapan >= 80) {
            $status = 'Siap';
        } elseif ($skor_kesiapan >= 60) {
            $status = 'Perlu Penguatan';
        } else {
            $status = 'Perlu Pendampingan Intensif';
        }

        $data = [
            'skor_akademik' => $akademik,
            'skor_konsistensi' => $konsistensi,
            'skor_psikologis' => $psikologis,
            'skor_spiritual' => $spiritual,
            'skor_kesiapan' => round($skor_kesiapan, 2),
            'status_kesiapan' => $status
        ];

        $this->StudentProgressModel->saveProgress($user_id, $data);

        return $data;
    }

    private function calculateAcademicScore($user_id)
    {
        // Ambil 3 tryout terakhir yang completed
        $tryouts = $this->UserTryoutModel->getRecentCompleted($user_id, 3);
        if (empty($tryouts)) return 0;

        $total = 0;
        foreach ($tryouts as $t) {
            $total += $t->total_score;
        }
        return round($total / count($tryouts), 2);
    }

    private function calculateConsistencyScore($user_id)
    {
        // Hitung berdasarkan:
        // - Persentase hari aktif dalam 30 hari terakhir (dari daily checklist)
        // - Rata-rata durasi belajar per hari
        // - Jumlah latihan soal yang dikerjakan

        $start = date('Y-m-d', strtotime('-30 days'));
        $end = date('Y-m-d');

        // Ambil daily checklist 30 hari terakhir
        $checklists = $this->DailyChecklistModel->getRange($user_id, $start, $end);
        $total_days = 30;
        $active_days = count($checklists);
        $active_percent = ($active_days / $total_days) * 100;

        // Rata-rata durasi belajar
        $total_belajar = 0;
        foreach ($checklists as $c) {
            $data = json_decode($c->checklist_data);
            $total_belajar += isset($data->belajar_menit) ? (int)$data->belajar_menit : 0;
        }
        $avg_belajar = $active_days > 0 ? $total_belajar / $active_days : 0;

        // Jumlah soal dikerjakan (dari user_answers) - perlu dihitung
        $this->load->model('UserAnswerModel');
        $total_answers = $this->UserAnswerModel->countByUser($user_id, $start, $end);

        // Skor konsistensi: kombinasi
        $score = ($active_percent * 0.4) + (min($avg_belajar, 300) / 300 * 100 * 0.3) + (min($total_answers, 500) / 500 * 100 * 0.3);
        return round(min($score, 100), 2);
    }

    private function calculatePsychologicalScore($user_id)
    {
        // Dari mood rating di daily checklist
        $start = date('Y-m-d', strtotime('-30 days'));
        $end = date('Y-m-d');
        $checklists = $this->DailyChecklistModel->getRange($user_id, $start, $end);
        if (empty($checklists)) return 70; // default

        $total_mood = 0;
        $count = 0;
        foreach ($checklists as $c) {
            if ($c->mood_rating) {
                $total_mood += $c->mood_rating;
                $count++;
            }
        }
        if ($count == 0) return 70;
        $avg_mood = $total_mood / $count; // 1-10
        return round(($avg_mood / 10) * 100, 2);
    }

    private function calculateSpiritualScore($user_id)
    {
        // Dari checklist ibadah
        $start = date('Y-m-d', strtotime('-30 days'));
        $end = date('Y-m-d');
        $checklists = $this->DailyChecklistModel->getRange($user_id, $start, $end);
        if (empty($checklists)) return 50;

        $total_ibadah = 0;
        $max_ibadah_per_day = 6; // 5 shalat + tilawah
        foreach ($checklists as $c) {
            $data = json_decode($c->checklist_data);
            $count = 0;
            if (isset($data->shalat_subuh) && $data->shalat_subuh) $count++;
            if (isset($data->shalat_dzuhur) && $data->shalat_dzuhur) $count++;
            if (isset($data->shalat_ashar) && $data->shalat_ashar) $count++;
            if (isset($data->shalat_maghrib) && $data->shalat_maghrib) $count++;
            if (isset($data->shalat_isya) && $data->shalat_isya) $count++;
            if (isset($data->tilawah) && $data->tilawah) $count++;
            $total_ibadah += $count;
        }
        $max_possible = count($checklists) * $max_ibadah_per_day;
        $score = ($max_possible > 0) ? ($total_ibadah / $max_possible) * 100 : 0;
        return round($score, 2);
    }
}
