<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importer
{
	protected	$ci;

	public function __construct()
	{
		$this->ci =& get_instance();
		$this->db =& $this->ci->db;
	}

	/**
	 * import  				Get data from file and import into database
	 * @return boolean		Returns true if file found and data imported else returns false
	 */
	public function import()
	{
		$data = $this->_getData();
		if(is_array($data)){
			$this->_importData($data);
			return true;
		}
		return false;
	}

	/**
	 * _getData 				Retrieves data from csv file
	 * @return array|boolean	If file is found returns data as array else returns false
	 */
	private function _getData(){
		$filepath = 'assets/data/data.csv';
		$data = array();
		if(!file_exists($filepath)){
			return false;
		}else{
			$file = fopen($filepath, 'r');
			while($line = fgetcsv($file, 255)){
				$data[] = $line;
			}
			// Remove column headers
			array_shift($data);
		}

		return $data;
	}

	/**
	 * _importData  		Import data into database
	 * @param  array $data	Data array returned from _getData function
	 * @return boolean		Returns true on success false if data is not array
	 */
	private function _importData($data){
		if(is_array($data)){
			$landlords = array();
			$landlordgroups = array();
			$relations = array();
			$costs = array();

			// Parse data into arrays for importing
			foreach($data as $key => $row){
				// Create list of landlords
				if(!in_array(array('LandlordName' => $row[0]), $landlords)){
					$landlords[] = array(
						'LandlordName' => $row[0]
					);
				}
				// Create list of landlord groups
				if(!in_array(array('LandlordGroupName' => $row[1]), $landlordgroups)){
					$landlordgroups[] = array(
						'LandlordGroupName' => $row[1]
					);
				}

				// Create list of group relations
				if(!isset($relations[$row[1]])){
					$relations[$row[1]] = array();
				}
				if(!in_array($row[0], $relations[$row[1]])){
					$relations[$row[1]][] = $row[0];
				}

				// Create list of costs
				$costs[] = array(
					'Landlord' => $row[0],
					'CostsQuarter' => $row[2],
					'CostsYear' => $row[3],
					'CostsEstimated' => $row[4],
					'CostsActual' => $row[5]
				);
			}

			// Import landlords (first check if exists)
			$results = $this->db->get('tblLandlord');
			// If table is empty insert all
			if($results->num_rows === 0){
				$this->db->insert_batch('tblLandlord', $landlords);
			// Else check for existing entities
			}else{
				$results = $results->result_array();
				foreach($results as $result){
					if(array_key_exists('LandlordID', $result)){
						unset($result['LandlordID']);
					}
					// If entity is already in database then remove from landlords array
					if(false !== $key = array_search($result, $landlords)){
						unset($landlords[$key]);
					}
				}
				// Insert remaining values
				if(!empty($landlords)){
					$this->db->insert_batch('tblLandlord', $landlords);
				}
			}

			// Import landlord groups (first check if exists)
			$results = $this->db->get('tblLandlordGroup');
			// If table is empty insert all
			if($results->num_rows === 0){
				$this->db->insert_batch('tblLandlordGroup', $landlordgroups);
			// Else check for existing entities
			}else{
				$results = $results->result_array();
				foreach($results as $result){
					if(array_key_exists('LandlordGroupID', $result)){
						unset($result['LandlordGroupID']);
					}
					// If entity is already in database then remove from landlordgroups array
					if(false !== $key = array_search($result, $landlordgroups)){
						unset($landlordgroups[$key]);
					}
				}
				// Insert remaining values
				if(!empty($landlordgroups)){
					$this->db->insert_batch('tblLandlordGroup', $landlordgroups);
				}
			}

			// Get Landlord IDs
			$results = $this->db->get('tblLandlord')->result_array();
			$landlordids = array();
			foreach($results as $result){
				$landlordids[$result['LandlordName']] = $result['LandlordID'];
			}

			// Get Landlord Group IDs
			$results = $this->db->get('tblLandlordGroup')->result_array();
			$landlordgroupids = array();
			foreach($results as $result){
				$landlordgroupids[$result['LandlordGroupName']] = $result['LandlordGroupID'];
			}

			// Import landlord group relations
			foreach($relations as $group => $landlords){
				$groupid = $landlordgroupids[$group];
				foreach($landlords as $landlord){
					$landlordid = $landlordids[$landlord];
					// Check if relation already exists
					$record = $this->db->get_where('tblLandlordGroupRel', array('LandlordID' => $landlordid, 'LandlordGroupID' => $groupid), 1);
					if($record->num_rows() === 0){
						$this->db->insert('tblLandlordGroupRel', array('LandlordID' => $landlordid, 'LandlordGroupID' => $groupid));
					}
					unset($record);
				}
			}

			// Import/update costs
			foreach($costs as $cost){
				$cost['LandlordID'] = $landlordids[$cost['Landlord']];
				unset($cost['Landlord']);
				$where = array('LandLordID' => $cost['LandlordID'], 'CostsQuarter' => $cost['CostsQuarter'], 'CostsYear' => $cost['CostsYear']);
				$record = $this->db->get_where('tblCosts', $where, 1);
				// If doesn't exist insert row
				if($record->num_rows() === 0){
					$this->db->insert('tblCosts', $cost);
				// Else update existing row
				}else{
					$this->db->where($where);
					$this->db->update('tblCosts', $cost);
				}
			}

			return true;
		}else{
			return false;
		}
	}

}

/* End of file importer.php */
/* Location: ./application/libraries/importer.php */
