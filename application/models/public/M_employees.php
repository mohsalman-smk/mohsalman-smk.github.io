<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_employees extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'employees';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * More Employees
	 * @param Int
	 * @return Resource
	 */
	public function get_employees($offset = 0) {
		$this->db->select("
			x1.id
		  , x1.nik
		  , x1.full_name
		  , IF(x1.gender = 'M', 'L', 'P') as gender
		  , x1.birth_place
		  , x1.birth_date
		  , x1.photo
		  , x2.option_name AS employment_type
		");
		$this->db->join('options x2', 'x1.employment_type_id = x2.id', 'LEFT');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->order_by('x1.full_name', 'ASC');
		return $this->db->get(self::$table.' x1', 20, $offset);
	}

	/**
	 * Get Total Rows
	 * @return Int
	 */
	public function total_rows() {
		return $this->db
			->where('is_deleted', 'false')
			->count_all_results(self::$table);
	}
}
