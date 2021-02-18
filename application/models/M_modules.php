<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_modules extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'modules';

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
		$this->db->select('id, module_name, module_description, module_url, is_deleted');
		if (!empty($keyword)) {
			$this->db->like('module_name', $keyword);
			$this->db->or_like('module_description', $keyword);
			$this->db->or_like('module_url', $keyword);
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
			$this->db->like('module_name', $keyword);
			$this->db->or_like('module_description', $keyword);
			$this->db->or_like('module_url', $keyword);
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
			->select('id, module_name')
			->where('is_deleted', 'false')
			->get(self::$table);
		$data = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->id] = $row->module_name;
			}
		}
		return $data;
	}
}