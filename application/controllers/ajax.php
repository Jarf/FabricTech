<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function getData()
	{
		// Get filters via POST data
		$landlordid = false;
		$landlordgroupid = false;
		$year = array();
		if($this->input->post('landlord')){
			$landlordid = $this->input->post('landlord');
			$landlordid = array_filter($landlordid, 'is_numeric');
			if(empty($landlordid)){
				$landlordid = false;
			}
		}
		if($this->input->post('landlordGroup')){
			$landlordgroupid = $this->input->post('landlordGroup');
			$landlordgroupid = array_filter($landlordgroupid, 'is_numeric');
			if(empty($landlordid)){
				$landlordid = false;
			}
		}
		if($this->input->post('startYear')){
			$year[] = $this->input->post('startYear');
		}
		if($this->input->post('endYear')){
			$year[] = $this->input->post('startYear');
		}
		if(count($year)){
			$year = array_unique($year);
			if(count($year) === 1){
				$year = array_shift($year);
			}
		}else{
			$year = false;
		}

		// Retrieve chart data
		$this->load->library('charts');
		$data = $this->charts->getChartData($landlordid, $landlordgroupid, $year);
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($data));
	}

}

/* End of file ajax.php */
/* Location: ./application/libraries/ajax.php */