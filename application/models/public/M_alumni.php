<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_alumni extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'students';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Alumni
	 * @param Int
	 * @return Resource
	 */
	public function get_alumni($offset = 0) {
		return $this->db->select("
				id
				, identity_number
				, full_name
				, IF(gender = 'M', 'L', 'P') AS gender
				, birth_place
				, LEFT(start_date, 4) AS start_date
				, LEFT(end_date, 4) AS end_date
				, birth_date
				, photo
			")
			->where('is_deleted', 'false')
			->where('is_student', 'false')
			->where('is_prospective_student', 'false')
			->where('is_alumni', 'true')
			->order_by('end_date', 'ASC')
			->order_by('full_name', 'ASC')
			->get(self::$table, 20, $offset);
	}

	/**
	 * Get Total Rows
	 * @return Int
	 */
	public function total_rows() {
		return $this->db
			->where('is_deleted', 'false')
			->where('is_student', 'false')
			->where('is_prospective_student', 'false')
			->where('is_alumni', 'true')
			->count_all_results(self::$table);
	}
}
