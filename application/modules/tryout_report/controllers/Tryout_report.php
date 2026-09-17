<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Tryout_report extends AppBackend
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'AppModel',
            'TryoutModel',
            'TryoutSessionModel',
            'UserTryoutModel',
        ));
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = array(
            'app' => $this->app(),
            'main_js' => $this->load_main_js('tryout_report'),
            'card_title' => 'Laporan Peringkat & Hasil Sesi Try Out',
        );

        // Data pendukung untuk filter dropdown
        $data['tryouts'] = $this->TryoutModel->getAll(array('is_published' => 1), 'title', 'asc');
        $data['sessions'] = $this->TryoutSessionModel->getAll(array(), 'tryout_id', 'asc');

        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    /**
     * DataTables server-side: peringkat per tryout
     */
    public function ajax_get_leaderboard()
    {
        $this->handle_ajax_request();

        $tryout_id = $this->input->post('tryout_id');
        $session_id = $this->input->post('session_id');

        $select_column = array(
            'user_tryouts.id',
            'user.nama_lengkap',
            'tryouts.title',
            'tryout_sessions.name',
            'user_tryouts.total_score',
            'user_tryouts.status',
            'user_tryouts.start_time',
            'user_tryouts.end_time',
            'user_tryouts.created_at',
        );

        $table_join = array(
            array(
                'table_name' => 'user',
                'expression' => 'user.id = user_tryouts.user_id',
                'type' => 'left'
            ),
            array(
                'table_name' => 'tryouts',
                'expression' => 'tryouts.id = user_tryouts.tryout_id',
                'type' => 'left'
            ),
            array(
                'table_name' => 'tryout_sessions',
                'expression' => 'tryout_sessions.id = user_tryouts.tryout_session_id',
                'type' => 'left'
            ),
        );

        $static_conditional = array();
        $static_conditional_spec = array(
            'user_tryouts.status' => 'completed',
        );

        // Filter dinamis
        if (!empty($tryout_id)) {
            $static_conditional_spec['user_tryouts.tryout_id'] = $tryout_id;
        }
        if (!empty($session_id)) {
            $static_conditional_spec['user_tryouts.tryout_session_id'] = $session_id;
        }

        $dtAjax_config = array(
            'table_name' => 'user_tryouts',
            'select_column' => $select_column,
            'table_join' => $table_join,
            'static_conditional_spec' => $static_conditional_spec,
            'order_column' => 5, // total_score
            'order_column_dir' => 'desc',
        );

        $response = $this->AppModel->getData_dtAjax($dtAjax_config);

        echo json_encode($response);
    }

    /**
     * Statistik ringkasan untuk header report
     */
    public function ajax_get_summary()
    {
        $this->handle_ajax_request();

        $tryout_id = $this->input->post('tryout_id');
        $session_id = $this->input->post('session_id');

        $this->db->select('
            COUNT(DISTINCT user_tryouts.user_id) AS total_participants,
            COUNT(user_tryouts.id) AS total_attempts,
            AVG(user_tryouts.total_score) AS average_score,
            MAX(user_tryouts.total_score) AS highest_score,
            MIN(user_tryouts.total_score) AS lowest_score
        ');
        $this->db->from('user_tryouts');
        $this->db->where('user_tryouts.status', 'completed');

        if (!empty($tryout_id)) {
            $this->db->where('user_tryouts.tryout_id', $tryout_id);
        }
        if (!empty($session_id)) {
            $this->db->where('user_tryouts.tryout_session_id', $session_id);
        }

        $row = $this->db->get()->row_array();

        echo json_encode(array(
            'status' => true,
            'data' => array(
                'total_participants' => (int) ($row['total_participants'] ?? 0),
                'total_attempts' => (int) ($row['total_attempts'] ?? 0),
                'average_score' => round((float) ($row['average_score'] ?? 0), 2),
                'highest_score' => round((float) ($row['highest_score'] ?? 0), 2),
                'lowest_score' => round((float) ($row['lowest_score'] ?? 0), 2),
            )
        ));
    }

    /**
     * Ambil daftar sesi berdasarkan tryout (untuk filter dinamis)
     */
    public function ajax_get_sessions_by_tryout($tryout_id)
    {
        $this->handle_ajax_request();

        $sessions = $this->TryoutSessionModel->getByTryout($tryout_id);
        echo json_encode(array('status' => true, 'data' => $sessions));
    }

    /**
     * Export CSV peringkat & hasil sesi
     */
    public function export_csv()
    {
        $tryout_id = $this->input->get('tryout_id');
        $session_id = $this->input->get('session_id');

        $this->db->select('
            user.nama_lengkap,
            tryouts.title AS tryout_title,
            tryout_sessions.name AS session_name,
            user_tryouts.total_score,
            user_tryouts.status,
            user_tryouts.start_time,
            user_tryouts.end_time
        ');
        $this->db->from('user_tryouts');
        $this->db->join('user', 'user.id = user_tryouts.user_id', 'left');
        $this->db->join('tryouts', 'tryouts.id = user_tryouts.tryout_id', 'left');
        $this->db->join('tryout_sessions', 'tryout_sessions.id = user_tryouts.tryout_session_id', 'left');
        $this->db->where('user_tryouts.status', 'completed');

        if (!empty($tryout_id)) {
            $this->db->where('user_tryouts.tryout_id', $tryout_id);
        }
        if (!empty($session_id)) {
            $this->db->where('user_tryouts.tryout_session_id', $session_id);
        }
        $this->db->order_by('user_tryouts.total_score', 'desc');

        $rows = $this->db->get()->result_array();

        $filename = 'laporan_tryout_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, array('Peringkat', 'Nama Lengkap', 'Try Out', 'Sesi', 'Skor', 'Status', 'Mulai', 'Selesai'));

        $rank = 1;
        foreach ($rows as $r) {
            fputcsv($output, array(
                $rank++,
                $r['nama_lengkap'],
                $r['tryout_title'],
                $r['session_name'],
                $r['total_score'],
                $r['status'],
                $r['start_time'],
                $r['end_time'],
            ));
        }

        fclose($output);
        exit;
    }

    /**
     * Export Excel peringkat & hasil sesi
     */
    public function export_excel()
    {
        $tryout_id = $this->input->get('tryout_id');
        $session_id = $this->input->get('session_id');

        $this->db->select('
            user.nama_lengkap,
            tryouts.title AS tryout_title,
            tryout_sessions.name AS session_name,
            user_tryouts.total_score,
            user_tryouts.status,
            user_tryouts.start_time,
            user_tryouts.end_time
        ');
        $this->db->from('user_tryouts');
        $this->db->join('user', 'user.id = user_tryouts.user_id', 'left');
        $this->db->join('tryouts', 'tryouts.id = user_tryouts.tryout_id', 'left');
        $this->db->join('tryout_sessions', 'tryout_sessions.id = user_tryouts.tryout_session_id', 'left');
        $this->db->where('user_tryouts.status', 'completed');

        if (!empty($tryout_id)) {
            $this->db->where('user_tryouts.tryout_id', $tryout_id);
        }
        if (!empty($session_id)) {
            $this->db->where('user_tryouts.tryout_session_id', $session_id);
        }
        $this->db->order_by('user_tryouts.total_score', 'desc');

        $rows = $this->db->get()->result_array();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Peringkat Try Out');

        // Header
        $headers = ['Peringkat', 'Nama Lengkap', 'Try Out', 'Sesi', 'Skor', 'Status', 'Waktu Mulai', 'Waktu Selesai'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '1', $h);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }

        $rowNum = 2;
        $rank = 1;
        foreach ($rows as $r) {
            $sheet->setCellValue('A' . $rowNum, $rank++);
            $sheet->setCellValue('B' . $rowNum, $r['nama_lengkap']);
            $sheet->setCellValue('C' . $rowNum, $r['tryout_title']);
            $sheet->setCellValue('D' . $rowNum, $r['session_name']);
            $sheet->setCellValue('E' . $rowNum, $r['total_score']);
            $sheet->setCellValue('F' . $rowNum, $r['status']);
            $sheet->setCellValue('G' . $rowNum, $r['start_time']);
            $sheet->setCellValue('H' . $rowNum, $r['end_time']);
            $rowNum++;
        }

        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'laporan_tryout_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}