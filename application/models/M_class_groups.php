<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_class_groups extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'class_groups';

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
		$this->db->select("
			x1.id
			, CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),'')) AS class_name
			, x1.is_deleted
		");
		$this->db->join('majors x2', 'x1.major_id = x2.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like("CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),''))", $keyword);
		}
		$this->db->order_by('x1.class_group', 'ASC');
		$this->db->order_by('x1.major_id', 'ASC');
		$this->db->order_by('x1.sub_class_group', 'ASC');
		return $this->db->get(self::$table. ' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('majors x2', 'x1.major_id = x2.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like("CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),''))", $keyword);
		}
		return $this->db->count_all_results(self::$table. ' x1');
	}

	/**
	 * Dropdown
	 * @access Public 
	 * @return Array
	 */
	public function dropdown() {
		$query = $this->db
			->select("x1.id, CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),'')) AS class_name")
			->join('majors x2', 'x1.major_id = x2.id', 'LEFT')
			->where('x1.is_deleted', 'false')
			->order_by('x1.class_group', 'ASC')
			->order_by('x1.major_id', 'ASC')
			->order_by('x1.sub_class_group', 'ASC')
			->get(self::$table. ' x1');
		$data = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->id] = $row->class_name;
			}
		}
		return $data;
	}
}