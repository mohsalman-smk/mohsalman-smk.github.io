<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_admission_exam_subject_settings extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'admission_subject_setting_details';

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
	 * @param Int
	 * @param String
	 * @param Int
	 * @param Int
	 * @return Resource
	 */
	public function get_where($subject_setting_id = 0, $keyword = '', $limit = 0, $offset = 0) {
		$this->db->select('x1.id, x2.subject_name, x1.is_deleted');
		$this->db->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT');
		$this->db->where('x1.subject_setting_id', $subject_setting_id);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.subject_name', $keyword);
			$this->db->group_end();
		}
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		return $this->db->get(self::$table.' x1');
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($subject_setting_id = 0, $keyword) {
		$this->db->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT');
		$this->db->where('x1.subject_setting_id', $subject_setting_id);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.subject_name', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table.' x1');
	}
}