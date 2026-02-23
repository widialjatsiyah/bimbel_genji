<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(FCPATH . 'vendor/autoload.php');

class Home extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			'SlideModel',
			'FeatureModel',
			'PackageModel',
			'FaqModel',
			'TestimonialModel',
			'TeamModel',
			'GalleryModel',
			'SettingHomeModel',
			'UserModel', 'TryoutModel', 'SchoolModel', 'QuestionModel', 'UserTryoutModel'
		]);
	}

	public function index()
	{
		$data['slides'] = $this->SlideModel->getAll(['is_active' => 1]);
		$data['features'] = $this->FeatureModel->getAll(['is_active' => 1]);
		$data['packages'] = $this->PackageModel->getAll(['is_active' => 1]);
		$data['faqs'] = $this->FaqModel->getAll(['is_active' => 1], 'order_num ASC');
		$data['testimonials'] = $this->TestimonialModel->getAll(['is_active' => 1, 'is_approved' => 1]);
		$data['teams'] = $this->TeamModel->getAll(['is_active' => 1]);
		$data['galleries'] = $this->GalleryModel->getAll(['is_active' => 1]);
		$data['settings'] = $this->SettingHomeModel->getAllSettings();

		$data['total_students'] = $this->UserModel->countByRole('student');
		$data['total_tryouts'] = $this->TryoutModel->countPublished();
		$data['total_schools'] = $this->SchoolModel->countAll();
		$data['total_questions'] = $this->QuestionModel->countAll();

		$latest_tryout = $this->TryoutModel->getLatestPublished();
		if ($latest_tryout) {
			$data['leaderboard'] = $this->UserTryoutModel->getLeaderboard($latest_tryout->id, 5);
			$data['latest_tryout_title'] = $latest_tryout->title;
		} else {
			$data['leaderboard'] = [];
		}

		$this->load->view('home/header', $data);
		$this->load->view('home/home', $data);
		$this->load->view('home/footer');
	}
}
