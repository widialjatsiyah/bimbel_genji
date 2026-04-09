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
				'rules' => 'required_if[question_type,multiple_choice]|max_length[1]'
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
				'rules' => 'required_if[question_type,multiple_choice]|max_length[1]'
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

	public function insert()
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
			$this->subject_id = $this->input->post('subject_id');
			$this->chapter_id = $this->input->post('chapter_id') ?: null;
			$this->topic_id = $this->input->post('topic_id') ?: null;
			$this->difficulty = $this->input->post('difficulty');
			$this->curriculum = $this->input->post('curriculum');
			$this->question_type = $this->input->post('question_type') ?: 'multiple_choice';
			$this->question_text = $this->input->post('question_text');
			
			// Untuk soal esai, pilihan jawaban opsional
			if ($this->question_type === 'multiple_choice') {
				$this->option_a = $this->input->post('option_a');
				$this->option_b = $this->input->post('option_b');
				$this->option_c = $this->input->post('option_c');
				$this->option_d = $this->input->post('option_d');
				$this->option_e = $this->input->post('option_e');
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
			
			// Menyimpan path gambar jika ada
			$this->question_image = $this->input->post('question_image') ?: null;
			$this->option_a_image = $this->input->post('option_a_image') ?: null;
			$this->option_b_image = $this->input->post('option_b_image') ?: null;
			$this->option_c_image = $this->input->post('option_c_image') ?: null;
			$this->option_d_image = $this->input->post('option_d_image') ?: null;
			$this->option_e_image = $this->input->post('option_e_image') ?: null;
			
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
			$this->subject_id = $this->input->post('subject_id');
			$this->chapter_id = $this->input->post('chapter_id') ?: null;
			$this->topic_id = $this->input->post('topic_id') ?: null;
			$this->difficulty = $this->input->post('difficulty');
			$this->curriculum = $this->input->post('curriculum');
			$this->question_type = $this->input->post('question_type') ?: 'multiple_choice';
			$this->question_text = $this->input->post('question_text');
			
			// Untuk soal esai, pilihan jawaban opsional
			if ($this->question_type === 'multiple_choice') {
				$this->option_a = $this->input->post('option_a');
				$this->option_b = $this->input->post('option_b');
				$this->option_c = $this->input->post('option_c');
				$this->option_d = $this->input->post('option_d');
				$this->option_e = $this->input->post('option_e');
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
			$this->updated_by = $this->session->userdata('user')['id'];
			$this->updated_date = date('Y-m-d H:i:s');
			
			// Menyimpan path gambar jika ada
			$this->question_image = $this->input->post('question_image') ?: null;
			$this->option_a_image = $this->input->post('option_a_image') ?: null;
			$this->option_b_image = $this->input->post('option_b_image') ?: null;
			$this->option_c_image = $this->input->post('option_c_image') ?: null;
			$this->option_d_image = $this->input->post('option_d_image') ?: null;
			$this->option_e_image = $this->input->post('option_e_image') ?: null;
			
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

	public function delete($id)
	{
		$response = array('status' => false, 'data' => 'No operation.');
		try {
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
}