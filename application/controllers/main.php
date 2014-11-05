<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
	{
		$data = array();
		$this->load->library('charts');
		$data['filterData'] = $this->charts->getFilterData();
		$this->load->view("layout/header");
		$this->load->view("filters", $data);
		$this->load->view("main");
		$this->load->view("layout/footer");
	}

}

/* End of file main.php */
/* Location: ./application/controllers/main.php */