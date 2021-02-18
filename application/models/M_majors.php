<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_majors extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'majors';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Data
	 * @param 	String 
	 * @param 	Int
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_where($keyword = '', $limit = 10, $offset = 0) {
		$this->db->select('id, major_name, major_short_name, is_active, is_deleted');
		if (!empty($keyword)) {
			$this->db->like('major_name', $keyword);
			$this->db->or_like('major_short_name', $keyword);
		}
		return $this->db->get(self::$table, $limit, $offset);
	}
	
	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		if (!empty($keyword)) {
			$this->db->like('major_name', $keyword);
			$this->db->or_like('major_short_name', $keyword);
		}
		return $this->db->count_all_results(self::$table);
	}

	/**
	 * Dropdown
	 * @access Public 
	 * @return Array
	 */
	public function dropdown() {
		$query = $this->db
			->select('id, major_name')
			->where('is_deleted', 'false')
			->where('is_active', 'true')
			->get(self::$table);
		$data = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->id] = $row->major_name;
			}
		}
		return $data;
	}
}