<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index()
	{
		$data = array();
		$this->load->library('charts');
		$data['filterData'] = $this->charts->getFilterData();
		$data['filterData']['jsonGroup'] = json_encode($data['filterData']['groupings']);
		$this->load->view("layout/header");
		$this->load->view("filters", $data);
		$this->load->view("main");
		$this->load->view("layout/footer");
	}

	public function import()
	{
		$this->load->library('importer');
		$this->importer->import();
	}

}

/* End of file main.php */
/* Location: ./application/controllers/main.php */