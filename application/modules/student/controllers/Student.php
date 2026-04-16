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
        $this->load->model(['UserModel', 'StudentClassModel', 'ClassModel','SchoolModel','AppModel', 'SubunitModel']);
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
        // Rules untuk user
        $rules = [
            ['field' => 'nama_lengkap', 'label' => 'Nama Lengkap', 'rules' => 'required'],
            ['field' => 'email', 'label' => 'Email', 'rules' => 'required|valid_email'],
            ['field' => 'username', 'label' => 'Username', 'rules' => 'required|is_unique[user.username]'],
        ];
        if (!$id) {
            $rules[] = ['field' => 'password', 'label' => 'Password', 'rules' => 'required|min_length[6]'];
        } else {
            // Update: hanya validasi password jika diisi
            if ($this->input->post('password')) {
                $rules[] = ['field' => 'password', 'label' => 'Password', 'rules' => 'min_length[6]'];
            }
        }
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == true) {
            $data = [
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'email' => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'role' => 'student',
            ];
            
            if ($this->input->post('password')) {
                $data['password'] = md5($this->input->post('password')); // sesuaikan dengan enkripsi Anda
            }
            
            // Unit diisi dengan sekolah admin jika role school_admin
            if ($this->session->userdata('user')['role'] == 'school_admin') {
                $data['unit'] = $this->session->userdata('user')['unit'];
            }
            
            if ($id) {
                $this->db->where('id', $id)->update('user', $data);
                $res = ['status' => true, 'data' => 'Siswa berhasil diperbarui.'];
            } else {
                $this->db->insert('user', $data);
                $res = ['status' => true, 'data' => 'Siswa berhasil ditambahkan.'];
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
                'id' => $class->id,
                'class_name' => $class->name,
                'school_name' => $class->school_id ? $this->SchoolModel->getDetail(['id' => $class->school_id])->name : null
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
        
        // Filter kelas berdasarkan sekolah jika role school_admin
        if ($user['role'] == 'school_admin') {
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
                $available_classes[] = [
                    'id' => $class->id,
                    'text' => $class->name,
                    'school' => $class->school_id ? $this->SchoolModel->getDetail(['id' => $class->school_id])->name : '-'
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
        
        // Cek apakah siswa sudah terdaftar di kelas ini
        if ($this->StudentClassModel->isStudentInClass($student_id, $class_id)) {
            echo json_encode(['status' => false, 'data' => 'Siswa sudah terdaftar di kelas ini.']);
            return;
        }
        
        // Tambahkan siswa ke kelas
        $this->StudentClassModel->addStudentToClass($student_id, $class_id);
        echo json_encode(['status' => true, 'data' => 'Kelas berhasil ditambahkan ke siswa.']);
    }

    // Method untuk menghapus kelas dari siswa
    public function ajax_remove_class($id)
    {
        $this->handle_ajax_request();
        
        // Kita perlu mendapatkan student_id dan class_id dari record yang akan dihapus
        $record = $this->db->where('id', $id)->get('student_class')->row();
        
        if (!$record) {
            echo json_encode(['status' => false, 'data' => 'Data kelas siswa tidak ditemukan.']);
            return;
        }
        
        // Validasi bahwa ini adalah kelas milik siswa yang valid
        $this->db->where('id', $id)->delete('student_class');
        echo json_encode(['status' => true, 'data' => 'Kelas berhasil dihapus dari siswa.']);
    }

    public function assign_class($student_id)
    {
        // Cek apakah siswa ada
        $student = $this->UserModel->getDetail(['id' => $student_id, 'role' => 'student']);
        if (!$student) show_404();

        // Ambil daftar kelas yang tersedia (filter berdasarkan sekolah admin jika perlu)
        $user = $this->session->userdata('user');
        if ($user['role'] == 'admin') {
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
        
        $student = $this->db->where(['id' => $id])->get('user')->row();
        
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
}
