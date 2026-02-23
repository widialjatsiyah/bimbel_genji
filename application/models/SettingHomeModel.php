<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SettingHomeModel extends CI_Model
{
    private $_table = 'settings';

    /**
     * Ambil semua setting dalam bentuk key-value
     * @return array
     */
    public function getAllSettings()
    {
        $result = [];
        $rows = $this->db->get($this->_table)->result();
        foreach ($rows as $row) {
            $result[$row->key] = $row->value;
        }
        return $result;
    }

    /**
     * Ambil satu setting berdasarkan key
     */
    public function getSetting($key)
    {
        $row = $this->db->where('key', $key)->get($this->_table)->row();
        return $row ? $row->value : null;
    }

    /**
     * Update atau insert setting
     * @param string $key
     * @param string $value
     * @param string $type (text|textarea|image|file)
     */
    public function updateSetting($key, $value, $type = 'text')
    {
        $exists = $this->db->where('key', $key)->get($this->_table)->row();
        if ($exists) {
            $this->db->where('key', $key)->update($this->_table, ['value' => $value]);
        } else {
            $this->db->insert($this->_table, [
                'key' => $key,
                'value' => $value,
                'type' => $type
            ]);
        }
    }

    /**
     * Update banyak setting sekaligus (dari input POST)
     */
    public function updateBatch($data)
    {
        foreach ($data as $key => $value) {
            // Abaikan jika key mengandung 'submit' atau 'csrf'
            if (strpos($key, 'submit') !== false || $key === $this->security->get_csrf_token_name()) {
                continue;
            }
            $this->updateSetting($key, $value);
        }
    }

    /**
     * Handle upload file untuk setting tipe image/file
     * @param string $fieldName Nama field input file
     * @param string $key Nama key setting
     * @param string $uploadPath Subfolder di dalam directory/
     * @return array Status upload
     */
    public function handleUpload($fieldName, $key, $uploadPath = 'settings')
    {
        if (empty($_FILES[$fieldName]['name'])) {
            return ['status' => false, 'data' => 'No file uploaded.'];
        }

        $this->load->library('CpUpload');
        $upload = $this->cpupload->run($fieldName, $uploadPath, true, true, 'jpg|jpeg|png|gif|ico|svg');

        if ($upload->status) {
            // Hapus file lama jika ada
            $oldFile = $this->getSetting($key);
            if ($oldFile && file_exists(FCPATH . $oldFile)) {
                unlink(FCPATH . $oldFile);
            }
            // Update setting dengan path baru
            $this->updateSetting($key, $upload->data->base_path, 'image');
            return ['status' => true, 'data' => $upload->data];
        } else {
            return ['status' => false, 'data' => $upload->data];
        }
    }
}
