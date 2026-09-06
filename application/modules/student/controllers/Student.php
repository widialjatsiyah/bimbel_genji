<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'controllers/AppBackend.php');

class Student extends AppBackend
{
    public function __construct()
    {
        parent::__construct();
        // Hanya admin dan admin sekolah yang boleh akses
        $role = $this->session->userdata('user')['role'];
        if (!in_array($role, ['Administrator', 'school_admin'])) {
            // show_error('Akses ditolak', 403);
        }
        $this->load->model(['UserModel', 'StudentDetailModel', 'StudentClassModel', 'ClassModel','SchoolModel','AppModel', 'SubunitModel']);
    }

    public function index()
    {
        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('student'),
            'card_title' => 'Manajemen Siswa'
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('index', $data, TRUE);
        $this->template->render();
    }

    public function ajax_get_all()
    {
        $this->handle_ajax_request();
        $user = $this->session->userdata('user');
        $dtAjax_config = [
            'select_column' => ['id', 'nama_lengkap', 'email', 'username', 'unit', 'sub_unit'],
            'table_name' => 'user',
            'static_conditional' => ['role' => 'student'],
            'order_column' => 1,
            'order_column_dir' => 'asc',
        ];
        // Jika admin sekolah, filter berdasarkan unit (school_id)
        if ($user['role'] == 'school_admin') {
            $dtAjax_config['static_conditional']['unit'] = $user['unit'];
        }
        $response = $this->AppModel->getData_dtAjax($dtAjax_config);
        echo json_encode($response);
    }

    public function ajax_save($id = null)
    {
        $this->handle_ajax_request();
        $this->load->library('form_validation');
        // Rules untuk user - is_unique hanya untuk insert, update cek manual
        $rules = [
            ['field' => 'nama_lengkap', 'label' => 'Nama Lengkap', 'rules' => 'required|trim'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required|trim|valid_email' . (!$id ? '|is_unique[user.email]' : '')],
            ['field' => 'username', 'label' => 'Username', 'rules' => 'required|trim|min_length[3]|max_length[30]'],
        ];
        if (!$id) {
            $rules[2]['rules'] .= '|is_unique[user.username]';
            $rules[] = ['field' => 'password', 'label' => 'Password', 'rules' => 'required|min_length[6]'];
        } else {
            if ($this->input->post('password')) {
                $rules[] = ['field' => 'password', 'label' => 'Password', 'rules' => 'min_length[6]'];
            }
        }
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == true) {
            $current_user = $this->session->userdata('user');
            if ($id && $current_user['role'] === 'school_admin') {
                $owned_student = $this->db->where(['id' => $id, 'role' => 'student', 'unit' => $current_user['unit']])->count_all_results('user');
                if (!$owned_student) {
                    echo json_encode(['status' => false, 'data' => 'Anda tidak berhak mengubah siswa ini.']);
                    return;
                }
            }

            // Manual unique check untuk update (exclude current id)
            if ($id) {
                $email_exists = $this->db->where('email', $this->input->post('email'))->where('id !=', $id)->get('user')->num_rows() > 0;
                if ($email_exists) {
                    echo json_encode(['status' => false, 'data' => '<div>- Email sudah digunakan.</div>']);
                    return;
                }
                $username_exists = $this->db->where('username', $this->input->post('username'))->where('id !=', $id)->get('user')->num_rows() > 0;
                if ($username_exists) {
                    echo json_encode(['status' => false, 'data' => '<div>- Username sudah digunakan.</div>']);
                    return;
                }
            }

            $data = [
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'role' => 'student',
                'is_active' => $this->input->post('is_active') ? '1' : '0',
            ];

            if ($this->input->post('password')) {
                $data['password'] = md5($this->input->post('password'));
            } elseif ($id) {
                $temp = $this->db->where('id', $id)->get('user')->row();
                if ($temp) $data['password'] = $temp->password;
            }

            $user_role = $this->session->userdata('user')['role'];
            if ($user_role == 'school_admin') {
                $data['unit'] = $this->session->userdata('user')['unit'];
                $data['sub_unit'] = $this->input->post('sub_unit') ?? '';
            } else {
                // Administrator bisa set unit/sub_unit
                $data['unit'] = $this->input->post('unit') ?? null;
                $data['sub_unit'] = $this->input->post('sub_unit') ?? null;
            }

            if ($id) {
                $saved = $this->db->where('id', $id)->update('user', $data);
                $res = ['status' => true, 'data' => 'Siswa berhasil diperbarui.'];
            } else {
                $saved = $this->db->insert('user', $data);
                $res = ['status' => true, 'data' => 'Siswa berhasil ditambahkan.'];
            }
            if (!$saved) {
                echo json_encode(['status' => false, 'data' => 'Gagal menyimpan siswa: ' . $this->db->error()['message']]);
                return;
            }
            echo json_encode($res);
        } else {
            $errors = validation_errors('<div>- ', '</div>');
            echo json_encode(['status' => false, 'data' => $errors]);
        }
    }

    public function ajax_delete($id)
    {
        $this->handle_ajax_request();
        
        // Hapus terlebih dahulu relasi kelas siswa
        $this->db->where('student_id', $id)->delete('student_class');
        
        // Baru hapus data user
        $this->db->where('id', $id)->delete('user');
        echo json_encode(['status' => true, 'data' => 'Siswa berhasil dihapus.']);
    }

    // Method untuk mendapatkan kelas siswa
    public function ajax_get_student_classes($student_id)
    {
        $this->handle_ajax_request();
        
        $classes = $this->StudentClassModel->getClassesByStudent($student_id);
        $result = [];
        
        foreach ($classes as $class) {
            $result[] = [
                'class_id' => $class->id,
                'class_name' => $class->name,
                'school_name' => $class->school_id ? (($school = $this->SchoolModel->getDetail(['id' => $class->school_id])) ? $school->name : null) : null
            ];
        }
        
        echo json_encode($result);
    }

    // Method untuk mendapatkan kelas yang tersedia
    public function ajax_get_available_classes($student_id)
    {
        $this->handle_ajax_request();
        
        $user = $this->session->userdata('user');
        $conditions = [];
        $student = $this->UserModel->getDetail(['id' => $student_id, 'role' => 'student']);
        if (!$student) {
            echo json_encode([]);
            return;
        }
        
        // Filter kelas berdasarkan sekolah jika role school_admin
        if ($user['role'] == 'school_admin') {
            if ((string) $student->unit !== (string) $user['unit']) {
                echo json_encode([]);
                return;
            }
            $conditions['school_id'] = $user['unit'];
        }
        
        // Dapatkan semua kelas
        $all_classes = $this->ClassModel->getAll($conditions, 'name', 'asc');
        
        // Dapatkan kelas yang sudah diambil siswa
        $enrolled_classes = $this->StudentClassModel->getClassesByStudent($student_id);
        $enrolled_class_ids = array_column($enrolled_classes, 'id');
        
        $available_classes = [];
        foreach ($all_classes as $class) {
            if (!in_array($class->id, $enrolled_class_ids)) {
                $school = $class->school_id ? $this->SchoolModel->getDetail(['id' => $class->school_id]) : null;
                $available_classes[] = [
                    'id' => $class->id,
                    'text' => $class->name,
                    'school' => $school ? $school->name : '-'
                ];
            }
        }
        
        echo json_encode($available_classes);
    }

    // Method untuk menambahkan kelas ke siswa
    public function ajax_add_class()
    {
        $this->handle_ajax_request();
        
        $student_id = $this->input->post('student_id');
        $class_id = $this->input->post('class_id');
        
        // Cek apakah siswa dan kelas valid
        $student = $this->UserModel->getDetail(['id' => $student_id, 'role' => 'student']);
        $class = $this->ClassModel->getDetail(['id' => $class_id]);
        
        if (!$student || !$class) {
            echo json_encode(['status' => false, 'data' => 'Siswa atau kelas tidak ditemukan.']);
            return;
        }

        $user = $this->session->userdata('user');
        if ($user['role'] === 'school_admin' && (string) $student->unit !== (string) $user['unit']) {
            echo json_encode(['status' => false, 'data' => 'Siswa bukan bagian dari sekolah Anda.']);
            return;
        }
        if ($user['role'] === 'school_admin' && (string) $class->school_id !== (string) $user['unit']) {
            echo json_encode(['status' => false, 'data' => 'Kelas bukan bagian dari sekolah Anda.']);
            return;
        }
        
        // Cek apakah siswa sudah terdaftar di kelas ini
        if ($this->StudentClassModel->isStudentInClass($student_id, $class_id)) {
            echo json_encode(['status' => false, 'data' => 'Siswa sudah terdaftar di kelas ini.']);
            return;
        }
        
        // Tambahkan siswa ke kelas
        if (!$this->StudentClassModel->addStudentToClass($student_id, $class_id)) {
            echo json_encode(['status' => false, 'data' => 'Gagal menambahkan siswa ke kelas.']);
            return;
        }
        echo json_encode(['status' => true, 'data' => 'Kelas berhasil ditambahkan ke siswa.']);
    }

    // Method untuk menghapus kelas dari siswa
    public function ajax_remove_class($student_id, $class_id)
    {
        $this->handle_ajax_request();
        
        $record = $this->db->where([
            'student_id' => $student_id,
            'class_id' => $class_id
        ])->get('student_class')->row();
        
        if (!$record) {
            echo json_encode(['status' => false, 'data' => 'Data kelas siswa tidak ditemukan.']);
            return;
        }
        
        $user = $this->session->userdata('user');
        if ($user['role'] === 'school_admin') {
            $student = $this->UserModel->getDetail(['id' => $student_id, 'role' => 'student']);
            $class = $this->ClassModel->getDetail(['id' => $class_id]);
            if (!$student || !$class || (string) $student->unit !== (string) $user['unit'] || (string) $class->school_id !== (string) $user['unit']) {
                echo json_encode(['status' => false, 'data' => 'Anda tidak berhak menghapus pendaftaran ini.']);
                return;
            }
        }

        $deleted = $this->db->where([
            'student_id' => $student_id,
            'class_id' => $class_id
        ])->delete('student_class');
        if (!$deleted) {
            echo json_encode(['status' => false, 'data' => 'Gagal menghapus kelas: ' . $this->db->error()['message']]);
            return;
        }
        echo json_encode(['status' => true, 'data' => 'Kelas berhasil dihapus dari siswa.']);
    }

    public function assign_class($student_id)
    {
        // Cek apakah siswa ada
        $student = $this->UserModel->getDetail(['id' => $student_id, 'role' => 'student']);
        if (!$student) show_404();

        // Ambil daftar kelas yang tersedia (filter berdasarkan sekolah admin jika perlu)
        $user = $this->session->userdata('user');
        if ($user['role'] == 'Administrator') {
            $classes = $this->ClassModel->getAll([], 'name', 'asc');
        } else {
            $classes = $this->ClassModel->getAll(['school_id' => $user['unit']], 'name', 'asc');
        }

        // Ambil kelas yang sudah diikuti siswa
        $enrolled = $this->StudentClassModel->getClassesByStudent($student_id);
        $enrolled_ids = array_column($enrolled, 'id');

        $data = [
            'app' => $this->app(),
            'main_js' => $this->load_main_js('student_assign'),
            'card_title' => 'Atur Kelas untuk ' . $student->nama_lengkap,
            'student' => $student,
            'classes' => $classes,
            'enrolled_ids' => $enrolled_ids
        ];
        $this->template->set('title', $data['card_title'] . ' | ' . $data['app']->app_name, TRUE);
        $this->template->load_view('assign_class', $data, TRUE);
        $this->template->render();
    }

    public function ajax_save_assign()
    {
        $this->handle_ajax_request();
        $student_id = $this->input->post('student_id');
        $class_ids = $this->input->post('class_ids'); // array

        // Hapus semua kelas lama
        $this->db->where('student_id', $student_id)->delete('student_class');

        // Tambahkan yang baru
        if (!empty($class_ids)) {
            foreach ($class_ids as $class_id) {
                $this->db->insert('student_class', ['student_id' => $student_id, 'class_id' => $class_id]);
            }
        }
        echo json_encode(['status' => true, 'data' => 'Kelas berhasil diperbarui.']);
    }
    
    // Method untuk mendapatkan detail siswa
    public function ajax_get_detail($id)
    {
        $this->handle_ajax_request();
        
        $student = $this->db->where(['id' => $id, 'role' => 'student'])->get('user')->row();
        $user = $this->session->userdata('user');
        if ($student && $user['role'] === 'school_admin' && (string) $student->unit !== (string) $user['unit']) {
            $student = null;
        }
        
        if ($student) {
            echo json_encode([
                'id' => $student->id,
                'nama_lengkap' => $student->nama_lengkap,
                'email' => $student->email,
                'username' => $student->username,
                'unit' => $student->unit,
                'sub_unit' => $student->sub_unit,
                'is_active' => $student->is_active
            ]);
        } else {
            echo json_encode(null);
        }
    }

    // Import siswa dari Excel
    public function import_from_excel()
    {
        $this->handle_ajax_request();
        $this->load->library('upload');
        
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size'] = '2048';
        
        if (!is_dir('./uploads/temp/')) {
            mkdir('./uploads/temp/', 0755, true);
        }
        
        $this->upload->initialize($config);
        
        if (!$this->upload->do_upload('import_file')) {
            echo json_encode(['status' => false, 'data' => $this->upload->display_errors()]);
            return;
        }
        
        $upload_data = $this->upload->data();
        
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('./uploads/temp/' . $upload_data['file_name']);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $imported_count = 0;
            $failed_count = 0;
            $errors = [];
            $file_emails = [];
            $file_usernames = [];
            $user = $this->session->userdata('user');
            
            $highestRow = $worksheet->getHighestRow();
            
            for ($row = 2; $row <= $highestRow; $row++) {
                $nama_lengkap = trim($worksheet->getCell('A' . $row)->getValue());
                $email = trim($worksheet->getCell('B' . $row)->getValue());
                $username = trim($worksheet->getCell('C' . $row)->getValue());
                $password = trim($worksheet->getCell('D' . $row)->getValue());
                $unit = trim($worksheet->getCell('E' . $row)->getValue());
                $sub_unit = trim($worksheet->getCell('F' . $row)->getValue());
                $is_active = trim($worksheet->getCell('G' . $row)->getValue());
                $nis = trim((string) $worksheet->getCell('H' . $row)->getFormattedValue());
                $asal_sekolah = trim((string) $worksheet->getCell('I' . $row)->getFormattedValue());
                $nama_orang_tua = trim((string) $worksheet->getCell('J' . $row)->getFormattedValue());
                $kontak_orang_tua = trim((string) $worksheet->getCell('K' . $row)->getFormattedValue());
                
                if (empty($nama_lengkap) || empty($email) || empty($username)) {
                    $failed_count++;
                    $errors[] = "Baris " . $row . ": Nama, email, dan username wajib diisi";
                    continue;
                }
                
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $failed_count++;
                    $errors[] = "Baris " . $row . ": Email tidak valid";
                    continue;
                }

                if (isset($file_emails[strtolower($email)]) || isset($file_usernames[strtolower($username)])) {
                    $failed_count++;
                    $errors[] = "Baris " . $row . ": Email/username duplikat di file Excel";
                    continue;
                }
                
                $existing = $this->db->where('email', $email)->or_where('username', $username)->get('user')->row();
                if ($existing) {
                    $failed_count++;
                    $errors[] = "Baris " . $row . ": Email/username sudah terdaftar";
                    continue;
                }

                $file_emails[strtolower($email)] = true;
                $file_usernames[strtolower($username)] = true;

                if ($user['role'] === 'school_admin') {
                    $unit = $user['unit'];
                } elseif ($unit !== '') {
                    $school = is_numeric($unit)
                        ? $this->SchoolModel->getDetail(['id' => $unit])
                        : $this->SchoolModel->getDetail(['name' => $unit]);
                    if (!$school) {
                        $failed_count++;
                        $errors[] = "Baris " . $row . ": Sekolah tidak ditemukan";
                        continue;
                    }
                    $unit = $school->id;
                } else {
                    $unit = null;
                }

                if ($sub_unit !== '') {
                    $subunit = is_numeric($sub_unit)
                        ? $this->SubunitModel->getDetail(['sub_unit.id' => $sub_unit])
                        : $this->SubunitModel->getDetail(['nama_sub_unit' => $sub_unit]);
                    if (!$subunit) {
                        $failed_count++;
                        $errors[] = "Baris " . $row . ": Sub sekolah tidak ditemukan";
                        continue;
                    }
                    $sub_unit = $subunit->nama_sub_unit;
                }
                
                $user_data = [
                    'nama_lengkap' => $nama_lengkap,
                    'email' => $email,
                    'username' => $username,
                    'role' => 'student',
                    'password' => !empty($password) ? md5($password) : md5('123456'),
                    'unit' => $unit,
                    'sub_unit' => $sub_unit,
                    'is_active' => $is_active === '' || $is_active == '1' ? '1' : '0',
                ];
                
                if ($this->db->insert('user', $user_data)) {
                    $user_id = $this->db->insert_id();
                    $detail_saved = $this->db->insert('student_details', [
                        'user_id' => $user_id,
                        'nis' => $nis !== '' ? $nis : null,
                        'asal_sekolah' => $asal_sekolah !== '' ? $asal_sekolah : null,
                        'nama_orang_tua' => $nama_orang_tua !== '' ? $nama_orang_tua : null,
                        'kontak_orang_tua' => $kontak_orang_tua !== '' ? $kontak_orang_tua : null,
                    ]);

                    if (!$detail_saved) {
                        // Do not leave an orphan user when the detail row fails.
                        $this->db->where('id', $user_id)->delete('user');
                        $failed_count++;
                        $errors[] = "Baris " . $row . ": Gagal menyimpan detail siswa";
                        continue;
                    }
                    $imported_count++;
                } else {
                    $failed_count++;
                    $errors[] = "Baris " . $row . ": Gagal menyimpan data siswa";
                }
            }
            
            unlink('./uploads/temp/' . $upload_data['file_name']);
            
            $message = "Berhasil mengimpor {$imported_count} siswa";
            if ($failed_count > 0) {
                $message .= ", gagal {$failed_count} siswa.";
                if (!empty($errors)) {
                    $message .= " Error: " . implode(', ', array_slice($errors, 0, 5));
                    if (count($errors) > 5) {
                        $message .= " dan " . (count($errors) - 5) . " error lainnya";
                    }
                }
            }
            
            echo json_encode(['status' => true, 'data' => $message]);
            
        } catch (Exception $e) {
            if (file_exists('./uploads/temp/' . $upload_data['file_name'])) {
                unlink('./uploads/temp/' . $upload_data['file_name']);
            }
            echo json_encode(['status' => false, 'data' => 'Error: ' . $e->getMessage()]);
        }
    }

    // Download template import siswa Excel
    public function download_template()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setTitle('Template Import Siswa');
        
        $headers = [
            'A1' => 'Nama Lengkap',
            'B1' => 'Email',
            'C1' => 'Username',
            'D1' => 'Password',
            'E1' => 'Sekolah (Unit) - Opsional',
            'F1' => 'Sub Sekolah - Opsional',
            'G1' => 'Aktif (1/0)',
            'H1' => 'NIS',
            'I1' => 'Asal Sekolah',
            'J1' => 'Nama Orang Tua',
            'K1' => 'Kontak Orang Tua',
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E6E6FA']]
        ];
        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);
        
        $sampleData = [
            'A2' => 'Budi Santoso',
            'B2' => 'budi@example.com',
            'C2' => 'budisantoso',
            'D2' => '123456',
            'E2' => '',
            'F2' => '',
            'G2' => '1',
            'H2' => '123456',
            'I2' => 'SMA Negeri 1',
            'J2' => 'Nama Orang Tua',
            'K2' => '08123456789',
        ];
        foreach ($sampleData as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(25);
        $sheet->getColumnDimension('J')->setWidth(25);
        $sheet->getColumnDimension('K')->setWidth(20);
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="template_import_siswa.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}
