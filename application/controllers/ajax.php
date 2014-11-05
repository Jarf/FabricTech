<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function search(){
		$terms = $this->input->get('term');
		$terms = explode(' ', $terms);
		$terms = array_map('trim', $terms);
		$terms = array_filter($terms);

		$this->load->library('charts');
		$search = $this->charts->searchData($terms);
		$landlords = array();
		$landlordgroups = array();
		foreach($search as $result){
			foreach($terms as $term){
				if(strpos($result['LandlordName'], $term) !== false){
					$landlords[$result['LandlordID']] = $result['LandlordName'];
				}
				if(strpos($result['LandlordGroupName'], $term) !== false){
					$landlordgroups[$result['LandlordGroupID']] = $result['LandlordGroupName'];
				}
			}
		}
		$landlords = array_unique($landlords);
		$landlordgroups = array_unique($landlordgroups);

		$results = array();
		foreach($landlords as $landlordid => $landlordname){
			$results[] = array('id' => $landlordid, 'value' => $landlordname, 'label' => $landlordname, 'type' => 'landlord');
		}
		foreach($landlordgroups as $landlordgroupid => $landlordgroupname){
			$results[] = array('id' => $landlordgroupid, 'value' => $landlordgroupname, 'label' => $landlordgroupname, 'type' => 'group');
		}

		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($results));
	}

	/**
	 * getData 				Retrieve chart data
	 * @return array 		Json array containing chart data
	 */
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

	/**
	 * importData 		Import data from CSV file
	 * @return boolean	Returns true on success
	 */
	public function importData()
	{
		$this->load->library('importer');
		$return = $this->importer->import();
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($return));
	}

}

/* End of file ajax.php */
/* Location: ./application/libraries/ajax.php */