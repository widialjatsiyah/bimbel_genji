<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AppModel extends CI_Model
{
    /**
     * Optimized version of getData_dtAjax.
     * - Uses helper methods to reduce code duplication.
     * - Keeps logic and results unchanged.
     */
    function getData_dtAjax($config)
    {
        $draw    = $_REQUEST['draw'];
        $length  = $_REQUEST['length'];
        $start   = $_REQUEST['start'];
        $search  = $_REQUEST['search']['value'];
        $order   = $_REQUEST['order'][0];
        $columns = $_REQUEST['columns'];

        // Get config
        $select_column             = $config['select_column'] ?? null;
        $table_name                = $config['table_name'] ?? null;
        $order_column              = $config['order_column'] ?? 1;
        $order_column_dir          = $order['dir'] ?? $config['order_column_dir'];
        $static_conditional        = $config['static_conditional'] ?? [];
        $static_conditional_spec   = $config['static_conditional_spec'] ?? [];
        $static_conditional_in_key = $config['static_conditional_in_key'] ?? null;
        $static_conditional_in     = $config['static_conditional_in'] ?? [];
        $table_join                = $config['table_join'] ?? null;
        $filters                   = $config['filters'] ?? [];
		$group_by                  = $config['group_by'] ?? null;
        $columnItem = [];
        $columnSearchDisable = [];
        $filter_conditional = [];

        // Identifikasi kolom dan filter spesifik
        foreach ($columns as $item) {
            if (!empty($item['data'])) {
                $columnItem[] = $item['data'];
                if ($item['searchable'] === 'false') {
                    $columnSearchDisable[] = $item['data'];
                }
                if (!empty($item['search']['value'])) {
                    $val = trim(stripslashes($item['search']['value']), '^$');
                    $filter_conditional[$item['data']] = $val === 'null' ? null : $val;
                }
            }
        }

        // Column untuk pencarian global
        $columnNoFilter = array_merge(['no'], array_keys($static_conditional), array_keys($static_conditional_spec), $columnSearchDisable);
        $columnSearch = [];
        foreach ($columnItem as $item) {
            if (!in_array($item, $columnNoFilter)) {
                $col = ($table_name == 'customer') ? "customer." . $item : $item;
                $columnSearch[] = "LOWER(CAST($col AS CHAR)) LIKE '%" . strtolower($search) . "%'";
            }
        }

        // Helper to apply all filters and joins
        $apply_filters = function($db) use (
            $table_name, $select_column, $table_join,
            $static_conditional, $static_conditional_spec,
            $filter_conditional, $static_conditional_in_key, $static_conditional_in,
            $search, $columnSearch, $filters, $columnItem, $order, $order_column, $order_column_dir
        ) {
            $db->from($table_name);
            if (!empty($select_column)) {
                $db->select($select_column);
            }
            if (!empty($table_join)) {
                foreach ($table_join as $join) {
                    if (count($join) === 3) {
                        $db->join($join['table_name'], $join['expression'], $join['type']);
                    }
                }
            }
            if (!empty($static_conditional)) {
                $db->like($static_conditional);
            }
            if (!empty($static_conditional_spec)) {
                $db->where($static_conditional_spec);
            }
            if (!empty($filter_conditional)) {
                $db->where($filter_conditional);
            }
            if (!empty($static_conditional_in_key) && !empty($static_conditional_in)) {
                $db->where_in($static_conditional_in_key, $static_conditional_in);
            }
            if (!empty($search) && !empty($columnSearch)) {
                $db->where('(' . implode(' OR ', $columnSearch) . ')');
            }
            foreach ($filters as $key => $value) {
                if ($value && $value != 'all') {
                    if (strpos($key, '_start') !== false) {
                        $dateKey = str_replace('_start', '', $key);
                        if (isset($filters[$dateKey . '_start']) && isset($filters[$dateKey . '_end'])) {
                            $startVal = $filters[$dateKey . '_start'];
                            $endVal = $filters[$dateKey . '_end'];
                            if (!empty($startVal) && !empty($endVal)) {
                                $db->where("$dateKey BETWEEN '$startVal' AND '$endVal'");
                            }
                        }
                    } else if (strpos($key, '_end') !== false) {
                        continue;
                    } else if ($key == 'customer_id' && $table_name != 'view_harga_barang_detail') {
                        $db->group_start();
                        $db->where($key, $value);
                        $db->or_where('agen_id', $value);
                        $db->group_end();
                    } else if ($key == 'tanggal_kirim') {
                        $db->where("DATE(tanggal_kirim) = '$value'");
                    } else {
                        if ($key != 'tanggal_invoice' ) {
                            $db->where($key, $value);
                        }
                    }
                }
            }
        };

        // Hitung total records
        $this->db->from($table_name);
        if (!empty($static_conditional)) {
            $this->db->like($static_conditional);
        }
        if (!empty($static_conditional_spec)) {
            $this->db->where($static_conditional_spec);
        }
        if (!empty($filter_conditional)) {
            $this->db->where($filter_conditional);
        }
        if (!empty($static_conditional_in_key) && !empty($static_conditional_in)) {
            $this->db->where_in($static_conditional_in_key, $static_conditional_in);
        }
        $recordsTotal = $this->db->count_all_results();

        // Ambil data dengan filter
        $apply_filters($this->db);
        $orderBy  = $columnItem[$order['column']] ?? $columnItem[$order_column];
        $this->db->order_by($orderBy, $order_column_dir);
		if (!empty($group_by)) {
			$this->db->group_by($group_by);
		}
        $this->db->limit($length, $start);
		
        $results = $this->db->get()->result_array();

        // Hitung filter count
        $apply_filters($this->db);
        $recordsFiltered = $this->db->count_all_results();
        // var_dump($this->db->last_query()); // Debugging line, can be removed in production
        // Response
        return [
            'draw' => intval($draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $results,
            'filter_cond' => $filter_conditional,
            'column_rendered' => $columnSearch,
            'filter_all' => array_merge($static_conditional, $static_conditional_spec, $filter_conditional),
        ];
    }


    private function get_filtered_count($table, $conditional, $in_key, $in_values, $joins, $columnSearch)
    {
        $this->db->from($table);

        if (!empty($joins)) {
            foreach ($joins as $join) {
                if (count($join) === 3) {
                    $this->db->join($join['table_name'], $join['expression'], $join['type']);
                }
            }
        }

        if (!empty($conditional)) {
            $this->db->where($conditional);
        }

        if (!empty($in_key) && !empty($in_values)) {
            $this->db->where_in($in_key, $in_values);
        }

        if (!empty($columnSearch)) {
            $this->db->group_start();
            foreach ($columnSearch as $likeClause) {
                $this->db->or_where($likeClause, null, false); // raw clause
            }
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function getAll($table, $where = array(), $order_by = null, $order_dir = 'asc', $limit = null, $offset = null)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($order_by)) {
            $this->db->order_by($order_by, $order_dir);
        }
        if (!is_null($limit) && !is_null($offset)) {
            $this->db->limit($limit, $offset);
        } elseif (!is_null($limit)) {
            $this->db->limit($limit);
        }

        $this->db->from($table);
        return $this->db->get()->result();
    }
}
