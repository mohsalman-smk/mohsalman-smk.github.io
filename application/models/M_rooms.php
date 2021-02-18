<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_rooms extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'rooms';

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
		$this->db->select('x1.id, x2.building_name, x1.room_name, x1.room_capacity, x1.is_class_room, x1.is_deleted');
		$this->db->join('buildings x2', 'x1.building_id = x2.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.building_name', $keyword);
			$this->db->or_like('x1.room_name', $keyword);
			$this->db->or_like('x1.room_capacity', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table.' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('buildings x2', 'x1.building_id = x2.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.building_name', $keyword);
			$this->db->or_like('x1.room_name', $keyword);
			$this->db->or_like('x1.room_capacity', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table.' x1');
	}

	/**
	 * Dropdown
	 * @access Public 
	 * @return Array
	 */
	public function dropdown() {
		$query = $this->db
			->select('id, room_name')
			->where('is_deleted', 'false')
			->get(self::$table);
		$data = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->id] = $row->room_name;
			}
		}
		return $data;
	}
}