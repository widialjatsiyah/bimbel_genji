<?php
defined('BASEPATH') or exit('No direct script access allowed');

class QuestionModel extends CI_Model
{
	private $_table = 'questions';

	public function rules()
	{
		return array(
			[
				'field' => 'subject_id',
				'label' => 'Mata Pelajaran',
				'rules' => 'required|integer'
			],
			[
				'field' => 'chapter_id',
				'label' => 'Bab',
				'rules' => 'integer'
			],
			[
				'field' => 'topic_id',
				'label' => 'Topik',
				'rules' => 'integer'
			],
			[
				'field' => 'group_id',
				'label' => 'Grup Soal',
				'rules' => 'trim'
			],
			[
				'field' => 'group_order',
				'label' => 'Urutan dalam Grup',
				'rules' => 'integer|greater_than_equal_to[1]'
			],
			[
				'field' => 'is_group_main',
				'label' => 'Soal Utama Grup',
				'rules' => 'integer|in_list[0,1]'
			],
			[
				'field' => 'difficulty',
				'label' => 'Tingkat Kesulitan',
				'rules' => 'required'
			],
			[
				'field' => 'curriculum',
				'label' => 'Kurikulum',
				'rules' => 'required'
			],
			[
				'field' => 'question_type',
				'label' => 'Jenis Soal',
				'rules' => 'required|in_list[multiple_choice,essay]'
			],
			[
				'field' => 'option_type',
				'label' => 'Tipe Opsi',
				'rules' => 'required|in_list[text,image]'
			],
			[
				'field' => 'question_text',
				'label' => 'Teks Soal',
				'rules' => 'required'
			],
			[
				'field' => 'option_a',
				'label' => 'Pilihan A',
				'rules' => 'required'
			],
			[
				'field' => 'option_b',
				'label' => 'Pilihan B',
				'rules' => 'required'
			],
			[
				'field' => 'option_c',
				'label' => 'Pilihan C',
				'rules' => 'required'
			],
			[
				'field' => 'option_d',
				'label' => 'Pilihan D',
				'rules' => 'required'
			],
			[
				'field' => 'option_e',
				'label' => 'Pilihan E',
				'rules' => 'required'
			],
			[
				'field' => 'correct_option',
				'label' => 'Jawaban Benar',
				'rules' => 'required'
			],
			[
				'field' => 'explanation',
				'label' => 'Pembahasan',
				'rules' => 'trim'
			],
			[
				'field' => 'video_explanation_url',
				'label' => 'URL Video Pembahasan',
				'rules' => 'trim|valid_url'
			],
		);
	}

	public function rulesWithImage()
	{
		return array(
			[
				'field' => 'subject_id',
				'label' => 'Mata Pelajaran',
				'rules' => 'required|integer'
			],
			[
				'field' => 'chapter_id',
				'label' => 'Bab',
				'rules' => 'integer'
			],
			[
				'field' => 'topic_id',
				'label' => 'Topik',
				'rules' => 'integer'
			],
			[
				'field' => 'group_id',
				'label' => 'Grup Soal',
				'rules' => 'trim'
			],
			[
				'field' => 'group_order',
				'label' => 'Urutan dalam Grup',
				'rules' => 'integer|greater_than_equal_to[1]'
			],
			[
				'field' => 'is_group_main',
				'label' => 'Soal Utama Grup',
				'rules' => 'integer|in_list[0,1]'
			],
			[
				'field' => 'difficulty',
				'label' => 'Tingkat Kesulitan',
				'rules' => 'required'
			],
			[
				'field' => 'curriculum',
				'label' => 'Kurikulum',
				'rules' => 'required'
			],
			[
				'field' => 'question_type',
				'label' => 'Jenis Soal',
				'rules' => 'required|in_list[multiple_choice,essay]'
			],
			[
				'field' => 'option_type',
				'label' => 'Tipe Opsi',
				'rules' => 'required|in_list[text,image]'
			],
			[
				'field' => 'question_text',
				'label' => 'Teks Soal',
				'rules' => 'trim'
			],
			[
				'field' => 'option_a',
				'label' => 'Pilihan A',
				'rules' => 'trim'
			],
			[
				'field' => 'option_b',
				'label' => 'Pilihan B',
				'rules' => 'trim'
			],
			[
				'field' => 'option_c',
				'label' => 'Pilihan C',
				'rules' => 'trim'
			],
			[
				'field' => 'option_d',
				'label' => 'Pilihan D',
				'rules' => 'trim'
			],
			[
				'field' => 'option_e',
				'label' => 'Pilihan E',
				'rules' => 'trim'
			],
			[
				'field' => 'correct_option',
				'label' => 'Jawaban Benar',
				'rules' => 'required'
			],
			[
				'field' => 'expected_keywords',
				'label' => 'Kata Kunci Jawaban Esai',
				'rules' => 'trim'
			],
			[
				'field' => 'min_keyword_matches',
				'label' => 'Minimal Cocok Kata Kunci',
				'rules' => 'integer|greater_than_equal_to[0]'
			],
			[
				'field' => 'explanation',
				'label' => 'Pembahasan',
				'rules' => 'trim'
			],
			[
				'field' => 'video_explanation_url',
				'label' => 'URL Video Pembahasan',
				'rules' => 'trim|valid_url'
			],
		);
	}

	public function rulesEssayOnly()
	{
		return array(
			[
				'field' => 'subject_id',
				'label' => 'Mata Pelajaran',
				'rules' => 'required|integer'
			],
			[
				'field' => 'chapter_id',
				'label' => 'Bab',
				'rules' => 'integer'
			],
			[
				'field' => 'topic_id',
				'label' => 'Topik',
				'rules' => 'integer'
			],
			[
				'field' => 'group_id',
				'label' => 'Grup Soal',
				'rules' => 'trim'
			],
			[
				'field' => 'group_order',
				'label' => 'Urutan dalam Grup',
				'rules' => 'integer|greater_than_equal_to[1]'
			],
			[
				'field' => 'is_group_main',
				'label' => 'Soal Utama Grup',
				'rules' => 'integer|in_list[0,1]'
			],
			[
				'field' => 'difficulty',
				'label' => 'Tingkat Kesulitan',
				'rules' => 'required'
			],
			[
				'field' => 'curriculum',
				'label' => 'Kurikulum',
				'rules' => 'required'
			],
			[
				'field' => 'question_type',
				'label' => 'Jenis Soal',
				'rules' => 'required|in_list[multiple_choice,essay]'
			],
			[
				'field' => 'question_text',
				'label' => 'Teks Soal',
				'rules' => 'required'
			],
			[
				'field' => 'expected_keywords',
				'label' => 'Kata Kunci Jawaban Esai',
				'rules' => 'required'
			],
			[
				'field' => 'min_keyword_matches',
				'label' => 'Minimal Cocok Kata Kunci',
				'rules' => 'required|integer|greater_than_equal_to[1]'
			],
			[
				'field' => 'explanation',
				'label' => 'Pembahasan',
				'rules' => 'trim'
			],
			[
				'field' => 'video_explanation_url',
				'label' => 'URL Video Pembahasan',
				'rules' => 'trim|valid_url'
			],
		);
	}

	public function getAll($params = array(), $orderField = null, $orderBy = 'asc')
	{
		$this->db->where($params);
		if (!is_null($orderField)) {
			$this->db->order_by($orderField, $orderBy);
		};
		return $this->db->get($this->_table)->result();
	}

	public function getById($id)
	{
		$this->db->where('id', $id);
		return $this->db->get($this->_table)->row();
	}

	public function getDetail($params = array())
	{
		$this->db->where($params);
		return $this->db->get($this->_table)->row();
	}

	// Fungsi baru untuk mendapatkan detail soal dengan informasi terkait
	public function getQuestionWithDetails($id)
	{
		return $this->db->select("
			questions.*,
			subjects.name as subject_name,
			chapters.name as chapter_name,
			topics.name as topic_name
		")
			->from($this->_table)
			->join('subjects', 'subjects.id = questions.subject_id', 'left')
			->join('chapters', 'chapters.id = questions.chapter_id', 'left')
			->join('topics', 'topics.id = questions.topic_id', 'left')
			->where('questions.id', $id)
			->get()
			->row();
	}

	public function insert()
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->subject_id = $this->input->post('subject_id');
			$this->chapter_id = $this->input->post('chapter_id') ?: null;
			$this->topic_id = $this->input->post('topic_id') ?: null;
			$this->group_id = $this->input->post('group_id') ?: null;
			$this->group_order = $this->input->post('group_order') ?: 1;
			$this->is_group_main = $this->input->post('is_group_main') ? 1 : 0;
			$this->difficulty = $this->input->post('difficulty');
			$this->curriculum = $this->input->post('curriculum');
			$this->question_type = $this->input->post('question_type') ?: 'multiple_choice';
			$this->question_text = $this->input->post('question_text');

			$this->option_type = $this->input->post('option_type') ?: 'text'; // Tipe untuk semua opsi
			$this->question_text = $this->input->post('question_text');

			// Menyimpan path gambar jika ada
			$this->question_image = $this->input->post('question_image') ?: null;
			$type_option = $this->input->post('option_type') ?: 'text';
			// Untuk soal esai, pilihan jawaban opsional
			if ($this->question_type === 'multiple_choice') {
				if($type_option === 'text') {
					$this->option_a = $this->input->post('option_a');
					$this->option_b = $this->input->post('option_b');
					$this->option_c = $this->input->post('option_c');
					$this->option_d = $this->input->post('option_d');
					$this->option_e = $this->input->post('option_e');
				} else {
					
					// Jika tipe opsi adalah gambar, gunakan path dari hidden input
					$this->option_a = $this->input->post('option_a_image') ?: null;
					$this->option_b = $this->input->post('option_b_image') ?: null;
					$this->option_c = $this->input->post('option_c_image') ?: null;
					$this->option_d = $this->input->post('option_d_image') ?: null;
					$this->option_e = $this->input->post('option_e_image') ?: null;
				}

				// Set jawaban benar untuk soal pilihan ganda
				$this->correct_option = $this->input->post('correct_option');
			} else {
				// Untuk soal esai, set opsi menjadi kosong jika tidak disediakan
				$this->option_a = $this->input->post('option_a') ?: '';
				$this->option_b = $this->input->post('option_b') ?: '';
				$this->option_c = $this->input->post('option_c') ?: '';
				$this->option_d = $this->input->post('option_d') ?: '';
				$this->option_e = $this->input->post('option_e') ?: '';
				$this->correct_option = null; // Tidak ada jawaban benar untuk soal esai
			}

			$this->explanation = $this->input->post('explanation');
			$this->video_explanation_url = $this->input->post('video_explanation_url');
			$this->created_by = $this->session->userdata('user')['id']; // asumsikan session user ada


			// Untuk soal esai, simpan kata kunci yang diharapkan
			$expected_keywords = $this->input->post('expected_keywords');
			if ($expected_keywords) {
				// Konversi dari string JSON ke dalam format yang bisa diproses
				$keywords_array = json_decode($expected_keywords, true);
				if ($keywords_array !== null) {
					$this->expected_keywords = $expected_keywords;
				} else {
					// Jika bukan JSON, coba simpan sebagai string biasa
					$this->expected_keywords = $expected_keywords;
				}
			} else {
				$this->expected_keywords = null;
			}

			$this->min_keyword_matches = $this->input->post('min_keyword_matches') ?: 1;

			$this->db->insert($this->_table, $this);
			$response = array('status' => true, 'data' => 'Data soal berhasil disimpan.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menyimpan data: ' . $th->getMessage());
		}
		return $response;
	}

	public function update($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			// Ambil data soal sebelum diupdate untuk mendapatkan informasi gambar lama
			$current_data = $this->getById($id);
			
			$this->subject_id = $this->input->post('subject_id');
			$this->chapter_id = $this->input->post('chapter_id');
			$this->topic_id = $this->input->post('topic_id');
			$this->question_text = $this->input->post('question_text');
			$this->difficulty = $this->input->post('difficulty');
			$this->curriculum = $this->input->post('curriculum');
			$this->question_type = $this->input->post('question_type') ?: 'multiple_choice';
			$this->group_id = $this->input->post('group_id') ?: null;
			$this->group_order = $this->input->post('group_order') ?: null;
			$this->is_group_main = $this->input->post('is_group_main') ?: 0;
			
			// Tambahkan tipe opsi
			$this->option_type = $this->input->post('option_type') ?: 'text';

			$type_option = $this->input->post('option_type') ?: 'text';
			// Untuk soal esai, pilihan jawaban opsional
			if ($this->question_type === 'multiple_choice') {
				if($type_option === 'text') {
					// Jika tipe opsi berubah dari gambar ke teks, hapus file gambar yang lama
					if ($current_data && $current_data->option_type === 'image') {
						$this->deleteOldOptionImages($current_data);
					}
					
					$this->option_a = $this->input->post('option_a');
					$this->option_b = $this->input->post('option_b');
					$this->option_c = $this->input->post('option_c');
					$this->option_d = $this->input->post('option_d');
					$this->option_e = $this->input->post('option_e');
				} else {
					// Jika tipe opsi adalah gambar, gunakan path dari hidden input
					$this->option_a = $this->input->post('option_a_image') ?: null;
					$this->option_b = $this->input->post('option_b_image') ?: null;
					$this->option_c = $this->input->post('option_c_image') ?: null;
					$this->option_d = $this->input->post('option_d_image') ?: null;
					$this->option_e = $this->input->post('option_e_image') ?: null;
				}
				
				$this->correct_option = $this->input->post('correct_option');

			} else {
				// Jika jenis soal berubah dari multiple_choice ke essay, hapus semua gambar opsi
				if ($current_data && $current_data->question_type === 'multiple_choice') {
					$this->deleteOldOptionImages($current_data);
				}
				
				// Untuk soal esai, set opsi menjadi kosong jika tidak disediakan
				$this->option_a = $this->input->post('option_a') ?: '';
				$this->option_b = $this->input->post('option_b') ?: '';
				$this->option_c = $this->input->post('option_c') ?: '';
				$this->option_d = $this->input->post('option_d') ?: '';
				$this->option_e = $this->input->post('option_e') ?: '';
				$this->correct_option = null; // Tidak ada jawaban benar untuk soal esai

			}

			$this->explanation = $this->input->post('explanation');
			$this->video_explanation_url = $this->input->post('video_explanation_url');
			$this->updated_by = $this->session->userdata('user')['id'];
			$this->updated_date = date('Y-m-d H:i:s');

			// Menyimpan path gambar jika ada
			$this->question_image = $this->input->post('question_image') ?: null;

			// Untuk soal esai, simpan kata kunci yang diharapkan
			$expected_keywords = $this->input->post('expected_keywords');
			if ($expected_keywords) {
				// Konversi dari string JSON ke dalam format yang bisa diproses
				$keywords_array = json_decode($expected_keywords, true);
				if ($keywords_array !== null) {
					$this->expected_keywords = $expected_keywords;
				} else {
					// Jika bukan JSON, coba simpan sebagai string biasa
					$this->expected_keywords = $expected_keywords;
				}
			} else {
				$this->expected_keywords = null;
			}

			$this->min_keyword_matches = $this->input->post('min_keyword_matches') ?: 1;

			$this->db->update($this->_table, $this, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data soal berhasil diperbarui.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal memperbarui data: ' . $th->getMessage());
		}
		return $response;
	}

	// Method untuk menghapus gambar opsi lama
	private function deleteOldOptionImages($current_data) {
		$option_fields = [
			$current_data->option_a,
			$current_data->option_b, 
			$current_data->option_c,
			$current_data->option_d,
			$current_data->option_e
		];
		
		foreach ($option_fields as $image_path) {
			if ($image_path && file_exists(FCPATH . $image_path)) {
				// Cek apakah file ini benar-benar merupakan file gambar
				$pathInfo = pathinfo($image_path);
				$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
				
				if (in_array(strtolower($pathInfo['extension']), $allowedExtensions)) {
					unlink(FCPATH . $image_path);
					
					// Hapus juga thumbnail jika ada
					$thumbPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
					if (file_exists(FCPATH . $thumbPath)) {
						unlink(FCPATH . $thumbPath);
					}
				}
			}
		}
	}

	public function delete($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			// Hapus file gambar terkait jika ada
			$question = $this->getById($id);
			if ($question) {
				// Hapus gambar soal
				if ($question->question_image && file_exists(FCPATH . $question->question_image)) {
					unlink(FCPATH . $question->question_image);
				}

				// Hapus gambar pilihan
				if ($question->option_a && file_exists(FCPATH . $question->option_a)) {
					unlink(FCPATH . $question->option_a);
				}
				if ($question->option_b && file_exists(FCPATH . $question->option_b)) {
					unlink(FCPATH . $question->option_b);
				}
				if ($question->option_c && file_exists(FCPATH . $question->option_c)) {
					unlink(FCPATH . $question->option_c);
				}
				if ($question->option_d && file_exists(FCPATH . $question->option_d)) {
					unlink(FCPATH . $question->option_d);
				}
				if ($question->option_e && file_exists(FCPATH . $question->option_e)) {
					unlink(FCPATH . $question->option_e);
				}
			}

			$this->db->delete($this->_table, array('id' => $id));
			$response = array('status' => true, 'data' => 'Data soal berhasil dihapus.');
		} catch (\Throwable $th) {
			$response = array('status' => false, 'data' => 'Gagal menghapus data: ' . $th->getMessage());
		}
		return $response;
	}

	function clean_number($number)
	{
		return preg_replace('/[^0-9]/', '', $number);
	}

	public function countAll()
	{
		return $this->db->count_all('questions');
	}

	/**
	 * Handle upload file untuk soal tipe image
	 * @param string $fieldName Nama field input file
	 * @param string $key Nama key untuk menyimpan path
	 * @param string $uploadPath Subfolder di dalam directory/
	 * @return array Status upload
	 */
	public function handleImageUpload($fieldName, $key, $uploadPath = 'questions')
	{
		if (empty($_FILES[$fieldName]['name'])) {
			// Jika tidak ada file baru yang diupload, gunakan path yang sudah ada di hidden input
			$existingPath = $this->input->post($key);
			if ($existingPath) {
				return ['status' => true, 'data' => (object)['base_path' => $existingPath]];
			}
			return ['status' => false, 'data' => 'No file uploaded.'];
		}

		$this->load->library('CpUpload');
		$upload = $this->cpupload->run($fieldName, $uploadPath, true, true, 'jpg|jpeg|png|gif');

		if ($upload->status) {
			// Hapus file lama jika ada dan ingin diganti
			$oldFilePath = $this->input->post($key); // Get old file path from hidden input
			if ($oldFilePath && file_exists(FCPATH . $oldFilePath)) {
				// Cek apakah ini file gambar dan hapus thumbnail terkait juga
				$imagePathInfo = pathinfo($oldFilePath);
				$thumbPath = $imagePathInfo['dirname'] . '/' . $imagePathInfo['filename'] . '_thumb.' . $imagePathInfo['extension'];
				
				unlink(FCPATH . $oldFilePath);
				
				// Hapus thumbnail jika ada
				if (file_exists(FCPATH . $thumbPath)) {
					unlink(FCPATH . $thumbPath);
				}
			}

			return ['status' => true, 'data' => $upload->data];
		} else {
			return ['status' => false, 'data' => $upload->data];
		}
	}


	/**
	 * Mendapatkan soal-soal berdasarkan grup
	 */
	public function getByGroupId($group_id)
	{
		return $this->db->where('group_id', $group_id)
			->order_by('group_order', 'asc')
			->get($this->_table)
			->result();
	}

	/**
	 * Mendapatkan soal utama dari sebuah grup
	 */
	public function getMainQuestionByGroupId($group_id)
	{
		return $this->db->where('group_id', $group_id)
			->where('is_group_main', 1)
			->get($this->_table)
			->row();
	}

	/**
	 * Mendapatkan soal-soal yang bukan bagian dari grup
	 */
	public function getNonGroupQuestions()
	{
		return $this->db->where('group_id IS NULL')
			->get($this->_table)
			->result();
	}
	
	/**
	 * Mendapatkan atau membuat ID grup baru
	 */
	public function createNewGroupId()
	{
		// Gunakan timestamp dan ID pengguna untuk membuat ID grup unik
		$new_group_id = time() . rand(1000, 9999);
		
		// Pastikan tidak ada konflik ID dengan melakukan pengecekan
		$this->db->where('id', $new_group_id);
		$count = $this->db->count_all_results($this->_table);
		
		if ($count > 0) {
			// Jika ada konflik, tambahkan angka acak lagi
			$new_group_id = time() . rand(10000, 99999);
		}
		
		return $new_group_id;
	}
	
	/**
	 * Menetapkan soal sebagai soal utama grup
	 */
	public function setAsGroupMain($question_id, $group_id)
	{
		$data = array(
			'group_id' => $group_id,
			'is_group_main' => 1,
			'group_order' => 1
		);
		
		return $this->db->update($this->_table, $data, array('id' => $question_id));
	}
	
	/**
	 * Menambahkan soal ke dalam grup
	 */
	public function addToGroup($question_id, $group_id, $order)
	{
		$data = array(
			'group_id' => $group_id,
			'group_order' => $order
		);
		
		return $this->db->update($this->_table, $data, array('id' => $question_id));
	}
	
	/**
	 * Menghapus soal dari grup
	 */
	public function removeFromGroup($question_id)
	{
		$data = array(
			'group_id' => null,
			'group_order' => 1,
			'is_group_main' => 0
		);
		
		return $this->db->update($this->_table, $data, array('id' => $question_id));
	}
	
	/**
	 * Mendapatkan semua soal dalam grup tertentu
	 */
	
}
