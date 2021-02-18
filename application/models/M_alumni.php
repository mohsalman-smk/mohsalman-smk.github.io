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
	 * Get Data
	 * @param 	String
	 * @param 	Int
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_where($keyword = '', $limit = 10, $offset = 0) {
		$this->db->select("
			id
			, identity_number
			, full_name
			, gender
			, street_address
			, photo
			, is_alumni
			, COALESCE(start_date, '') start_date
			, end_date
			, is_deleted
		");
		$this->db->where('is_student', 'false');
		$this->db->where('is_prospective_student', 'false');
		$this->db->where_in('is_alumni', ['true', 'false', 'unverified']);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('identity_number', $keyword);
			$this->db->or_like('full_name', $keyword);
			$this->db->or_like('gender', $keyword);
			$this->db->or_like('street_address', $keyword);
			$this->db->or_like('start_date', $keyword);
			$this->db->or_like('end_date', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table, $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->where('is_student', 'false');
		$this->db->where('is_prospective_student', 'false');
		$this->db->where_in('is_alumni', ['true', 'false', 'unverified']);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('identity_number', $keyword);
			$this->db->or_like('full_name', $keyword);
			$this->db->or_like('gender', $keyword);
			$this->db->or_like('street_address', $keyword);
			$this->db->or_like('start_date', $keyword);
			$this->db->or_like('end_date', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table);
	}

	/**
	 * Alumni Reports
	 * @access 	public
	 */
	public function alumni_reports() {
		$this->load->model('m_students');
		$query = $this->m_students->student_query();
		$query .= "
		AND x1.is_student = 'false'
		AND x1.is_prospective_student = 'false'
		AND x1.is_alumni = 'true'
		ORDER BY x1.full_name ASC
		";
		return $this->db->query($query);
	}
}
