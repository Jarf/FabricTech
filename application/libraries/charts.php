<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charts
{
	protected 	$ci;

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->db =& $this->ci->db;
	}

	public function getFilterData()
	{
		// Return array
		$return = array(
			'landlords' => array(),
			'landlordgroups' => array(),
			'groupings' => array(),
			'years' => array()
		);

		// Get landlords and groups
		// Select
		$this->db->select('tblLandlord.LandlordID, tblLandlordGroup.LandlordGroupID, tblLandlord.LandlordName, tblLandlordGroup.LandlordGroupName');
		// Join
		$this->db->join('tblLandlordGroupRel', 'tblLandlordGroupRel.LandlordID = tblLandlord.LandlordID');
		$this->db->join('tblLandlordGroup', 'tblLandlordGroup.LandlordGroupID = tblLandlordGroupRel.LandlordGroupID');
		// Get
		$results = $this->db->get('tblLandlord')->result_array();
		// Format results
		foreach($results as $result){
			$return['landlords'][$result['LandlordID']] = $result['LandlordName'];
			$return['landlordgroups'][$result['LandlordGroupID']] = $result['LandlordGroupName'];
			if(!isset($return['groupings'][$result['LandlordGroupID']])){
				$return['groupings'][$result['LandlordGroupID']] = array();
			}
			if(!in_array($result['LandlordID'], $return['groupings'][$result['LandlordGroupID']])){
				$return['groupings'][$result['LandlordGroupID']][] = $result['LandlordID'];
			}
		}

		// Get year range
		$this->db->select('MIN(tblCosts.CostsYear) AS MinDate, MAX(tblCosts.CostsYear) AS MaxDate');
		$results = $this->db->get('tblCosts')->result_array();
		$return['yearRange'] = $results[0];

		return $return;
	}

	public function getChartData($landlordId = false, $landlordGroupId = false, $year = false)
	{
		// Select
		$this->db->select('tblCosts.LandlordID, tblLandlordGroup.LandlordGroupID, tblCosts.CostsQuarter, tblCosts.CostsYear, tblCosts.CostsEstimated, tblCosts.CostsActual, tblLandlord.LandlordName, tblLandlordGroup.LandlordGroupName');

		// Validate landlord ID and set where
		if(is_array($landlordId)){
			$landlordId = array_filter($landlordId, 'is_numeric');
			if(empty($landlordId)){
				$landlordId = false;
			}else{
				$this->db->where_in('tblCosts.LandlordID', $landlordId);
			}
		}elseif(is_numeric($landlordId)){
			$this->db->where('tblCosts.LandlordID', $landlordId);
		}elseif($landlordId !== false){
			$landlordId = false;
		}

		// Validate landlord Group ID and set where
		if(is_array($landlordGroupId)){
			$landlordGroupId = array_filter($landlordGroupId, 'is_numeric');
			if(empty($landlordGroupId)){
				$landlordGroupId = false;
			}else{
				$this->db->where_in('tblLandlordGroup.landlordGroupId', $landlordGroupId);
			}
		}elseif(is_numeric($landlordGroupId)){
			$this->db->where('tblLandlordGroup.LandlordGroupID', $landlordId);
		}elseif($landlordGroupId !== false){
			$landlordGroupId = false;
		}

		// Validate year and set where
		if(is_array($year) && count($year) === 2){
			sort($year);
			$this->db->where('tblCosts.CostsYear >=', $year[0]);
			$this->db->where('tblCosts.CostsYear <=', $year[1]);
		}elseif(is_numeric($year)){
			$this->db->where('tblCosts.CostsYear', $year);
		}else{
			$year = false;
		}

		// From
		$this->db->from('tblCosts');

		// Joins
		$this->db->join('tblLandlord', 'tblLandlord.LandlordID = tblCosts.LandLordID');
		$this->db->join('tblLandlordGroupRel', 'tblLandlord.LandlordID = tblLandlordGroupRel.LandLordID');
		$this->db->join('tblLandlordGroup', 'tblLandlordGroup.LandlordGroupID = tblLandlordGroupRel.LandlordGroupID');

		// Order by
		$this->db->order_by('tblCosts.LandLordID asc, tblCosts.CostsYear desc, tblCosts.CostsQuarter asc');

		$results = $this->db->get();
		if($results->num_rows() > 0){
			$results = $results->result_array();

			// Format data for return
			$data = array();
			foreach($results as $result){
				// Landlord ID
				if(!isset($data[$result['LandlordID']])){
					$data[$result['LandlordID']] = array(
						'Name' => $result['LandlordName'],
						'GroupName' => $result['LandlordGroupName'],
						'data' => array()
					);
				}
				// Costs Year
				if(!isset($data[$result['LandlordID']]['data'][$result['CostsYear']])){
					$data[$result['LandlordID']]['data'][$result['CostsYear']] = array();
				}
				// Quarter and Costs
				$data[$result['LandlordID']]['data'][$result['CostsYear']][$result['CostsQuarter']] = array(
					'CostsEstimated' => $result['CostsEstimated'],
					'CostsActual' => $result['CostsActual']
				);
			}

			// Return formatted data
			return $data;

		}else{

			return false;

		}

	}

}

/* End of file charts.php */
/* Location: ./application/libraries/charts.php */
