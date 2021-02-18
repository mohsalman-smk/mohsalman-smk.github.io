<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_scholarships extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'scholarships';

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
		$user_type = $this->session->userdata('user_type');
		$this->db->select('id, scholarship_type, scholarship_description, scholarship_start_year, scholarship_end_year, is_deleted');
		if ($user_type === 'student') {
			$this->db->where('student_id', $this->session->userdata('user_profile_id'));
		}
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->or_like('scholarship_type', $keyword);
			$this->db->like('scholarship_description', $keyword);
			$this->db->or_like('scholarship_start_year', $keyword);
			$this->db->or_like('scholarship_end_year', $keyword);
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
		$user_type = $this->session->userdata('user_type');
		if ($user_type === 'student') {
			$this->db->where('student_id', $this->session->userdata('user_profile_id'));
		}
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->or_like('scholarship_type', $keyword);
			$this->db->like('scholarship_description', $keyword);
			$this->db->or_like('scholarship_start_year', $keyword);
			$this->db->or_like('scholarship_end_year', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table);;
	}

	/**
	 * Get By Student ID
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_by_student_id($student_id = 0) {
		$this->db->select('id, scholarship_type, scholarship_description, scholarship_start_year, scholarship_end_year, is_deleted');
		$this->db->where('student_id', $student_id);
		$this->db->where('is_deleted', 'false');
		return $this->db->get(self::$table);
	}
}
