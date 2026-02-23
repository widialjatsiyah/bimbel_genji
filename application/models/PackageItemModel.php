<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PackageItemModel extends CI_Model
{
    private $_table = 'package_items';

    public function getByPackage($package_id)
    {
        return $this->db->where('package_id', $package_id)
                        ->order_by('item_type', 'asc')
                        ->get($this->_table)
                        ->result();
    }


    public function removeItem($id)
    {
        $this->db->delete($this->_table, ['id' => $id]);
    }

    public function removeByPackage($package_id)
    {
        $this->db->delete($this->_table, ['package_id' => $package_id]);
    }

    public function getItemsWithDetails($package_id)
    {
        $items = $this->getByPackage($package_id);
        foreach ($items as &$item) {
            switch ($item->item_type) {
                case 'tryout':
                    $this->load->model('TryoutModel');
                    $detail = $this->TryoutModel->getDetail(['id' => $item->item_id]);
                    $item->detail = $detail;
                    break;
                case 'class':
                    $this->load->model('ClassModel');
                    $detail = $this->ClassModel->getDetail(['id' => $item->item_id]);
                    $item->detail = $detail;
                    break;
                case 'material':
                    $this->load->model('MaterialModel');
                    $detail = $this->MaterialModel->getDetail(['id' => $item->item_id]);
                    $item->detail = $detail;
                    break;
                case 'quiz':
                    $this->load->model('TryoutModel'); // asumsikan quiz pakai tryout
                    $detail = $this->TryoutModel->getDetail(['id' => $item->item_id]);
                    $item->detail = $detail;
                    break;
            }
        }
        return $items;
    }

	  public function getItemsByPackage($package_id)
    {
        return $this->db->where('package_id', $package_id)
                        ->order_by('item_type', 'asc')
                        ->get($this->_table)
                        ->result();
    }

    /**
     * Menambahkan item ke paket
     */
    public function addItem($package_id, $item_type, $item_id)
    {
        // Validasi tipe yang diperbolehkan
        if (!in_array($item_type, ['tryout', 'class', 'material'])) {
            return ['status' => false, 'data' => 'Tipe item tidak valid.'];
        }

        // Cek duplikasi
        $exists = $this->db->where('package_id', $package_id)
                           ->where('item_type', $item_type)
                           ->where('item_id', $item_id)
                           ->count_all_results($this->_table);
        if ($exists > 0) {
            return ['status' => false, 'data' => 'Item sudah ada dalam paket.'];
        }

        $data = [
            'package_id' => $package_id,
            'item_type' => $item_type,
            'item_id' => $item_id
        ];
        $this->db->insert($this->_table, $data);
        return ['status' => true, 'data' => 'Item berhasil ditambahkan.'];
    }


    /**
     * Mendapatkan daftar tryout yang belum ada di paket (untuk select2)
     */
    public function getAvailableTryouts($package_id)
    {
        $sub = $this->db->select('item_id')
                        ->from('package_items')
                        ->where('package_id', $package_id)
                        ->where('item_type', 'tryout')
                        ->get_compiled_select();
        return $this->db->select('id, title as text')
                        ->from('tryouts')
                        ->where("id NOT IN ($sub)", NULL, FALSE)
                        ->order_by('title', 'asc')
                        ->get()
                        ->result();
    }

    /**
     * Mendapatkan daftar kelas yang belum ada di paket (untuk select2)
     */
    public function getAvailableClasses($package_id)
    {
        $sub = $this->db->select('item_id')
                        ->from('package_items')
                        ->where('package_id', $package_id)
                        ->where('item_type', 'class')
                        ->get_compiled_select();
        return $this->db->select('c.id, c.name as text, s.name as school')
                        ->from('classes c')
                        ->join('schools s', 's.id = c.school_id', 'left')
                        ->where("c.id NOT IN ($sub)", NULL, FALSE)
                        ->order_by('c.name', 'asc')
                        ->get()
                        ->result();
    }

    /**
     * Mendapatkan daftar materi yang belum ada di paket (untuk select2)
     */
    public function getAvailableMaterials($package_id)
    {
        $sub = $this->db->select('item_id')
                        ->from('package_items')
                        ->where('package_id', $package_id)
                        ->where('item_type', 'material')
                        ->get_compiled_select();
        return $this->db->select('id, title as text')
                        ->from('materials')
                        ->where("id NOT IN ($sub)", NULL, FALSE)
                        ->order_by('title', 'asc')
                        ->get()
                        ->result();
    }
	
	
}
