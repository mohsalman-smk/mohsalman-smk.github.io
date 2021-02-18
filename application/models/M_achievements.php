<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_achievements extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'achievements';

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
		$this->db->select('id, achievement_description, achievement_type, achievement_level, achievement_year, achievement_organizer, is_deleted');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('achievement_description', $keyword);
			$this->db->or_like('achievement_type', $keyword);
			$this->db->or_like('achievement_level', $keyword);
			$this->db->or_like('achievement_year', $keyword);
			$this->db->or_like('achievement_organizer', $keyword);
			$this->db->group_end();
		}
		if ($user_type === 'student') {
			$this->db->where('student_id', $this->session->userdata('user_profile_id'));
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
			$this->db->group_start();
			$this->db->like('achievement_description', $keyword);
			$this->db->or_like('achievement_type', $keyword);
			$this->db->or_like('achievement_level', $keyword);
			$this->db->or_like('achievement_year', $keyword);
			$this->db->or_like('achievement_organizer', $keyword);
			$this->db->group_end();
		}
		if (NULL !== $this->session->userdata('user_type') && $this->session->userdata('user_type') === 'student') {
			$this->db->where('student_id', $this->session->userdata('user_profile_id'));
		}
		return $this->db->count_all_results(self::$table);;
	}

	/**
	 * Get By Student ID
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_by_student_id($student_id = 0) {
		$this->db->select('id, achievement_description, achievement_type, achievement_level, achievement_year, achievement_organizer, is_deleted');
		$this->db->where('student_id', $student_id);
		$this->db->where('is_deleted', 'false');
		return $this->db->get(self::$table);
	}
}
