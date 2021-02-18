<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_student_status extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'options';

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
		$this->db->select('id, option_group, option_name, is_deleted');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('option_name', $keyword);
			$this->db->group_end();
		}
		$this->db->where('option_group', 'student_status');
		return $this->db->get(self::$table, $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->where('option_group', 'student_status');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('option_name', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table);
	}

	/**
	 * Find Student Status ID
	 * @param 	String
	 * @return 	Int
	 */
	public function find_student_status_id($student_status = 'lulus') {
		$query = $this->db
			->select('id')
			->where('option_group', 'student_status')
			->where('LOWER(option_name)', strtolower($student_status))
			->get(self::$table);
		if ($query->num_rows() === 1) {
			$res = $query->row();
			return $res->id;
		}
		return 0;
	}
}
