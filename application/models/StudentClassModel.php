<?php
defined('BASEPATH') or exit('No direct script access allowed');

class StudentClassModel extends CI_Model
{
    private $_table = 'student_class';

    /**
     * Mendapatkan daftar siswa dalam satu kelas
     */
    // public function getStudentsByClass($class_id)
    // {
    //     return $this->db->select('u.id, u.nama_lengkap, u.email, u.username')
    //                     ->from($this->_table . ' sc')
    //                     ->join('user u', 'u.id = sc.student_id')
    //                     ->where('sc.class_id', $class_id)
    //                     ->order_by('u.nama_lengkap', 'asc')
    //                     ->get()
    //                     ->result();
    // }

    /**
     * Mendapatkan semua kelas yang diikuti oleh seorang siswa
     */
    public function getClassesByStudent($student_id)
    {
        return $this->db->select('c.*')
                        ->from($this->_table . ' sc')
                        ->join('classes c', 'c.id = sc.class_id')
                        ->where('sc.student_id', $student_id)
                        ->order_by('c.name', 'asc')
                        ->get()
                        ->result();
    }

    /**
     * Menambahkan siswa ke kelas
     */
    public function addStudentToClass($student_id, $class_id)
    {
        $data = [
            'student_id' => $student_id,
            'class_id' => $class_id,
            'joined_at' => date('Y-m-d H:i:s')
        ];
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    /**
     * Menghapus siswa dari kelas
     */
    public function removeStudentFromClass($student_id, $class_id)
    {
        $this->db->where('student_id', $student_id)
                 ->where('class_id', $class_id)
                 ->delete($this->_table);
    }

    /**
     * Cek apakah siswa sudah terdaftar di kelas
     */
    public function isStudentInClass($student_id, $class_id)
    {
        return $this->db->where('student_id', $student_id)
                        ->where('class_id', $class_id)
                        ->count_all_results($this->_table) > 0;
    }

    /**
     * Mendapatkan jumlah siswa per kelas
     */
    public function countStudentsByClass($class_id)
    {
        return $this->db->where('class_id', $class_id)
                        ->count_all_results($this->_table);
    }

	public function getStudentsByClass($class_id)
{
    return $this->db->select('u.id, u.nama_lengkap, u.email, u.username')
                    ->from('student_class sc')
                    ->join('user u', 'u.id = sc.student_id')
                    ->where('sc.class_id', $class_id) // pastikan $class_id bukan array
                    ->order_by('u.nama_lengkap', 'asc')
                    ->get()
                    ->result();
}
}
