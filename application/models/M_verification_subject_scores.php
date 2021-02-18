<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_verification_subject_scores extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'admission_subject_scores';

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
		$this->db->select('x1.id, x5.registration_number, LEFT(x5.created_at, 10) AS created_at, x5.full_name, x3.subject_name, x4.subject_type, x1.score, x1.is_deleted');
		$this->db->join('admission_subject_setting_details x2', 'x1.subject_setting_detail_id = x2.id', 'LEFT');
		$this->db->join('subjects x3', 'x2.subject_id = x3.id', 'LEFT');
		$this->db->join('admission_subject_settings x4', 'x2.subject_setting_id = x4.id', 'LEFT');
		$this->db->join('students x5', 'x1.student_id = x5.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x5.registration_number', $keyword);
			$this->db->or_like('x5.created_at', $keyword);
			$this->db->or_like('x5.full_name', $keyword);
			$this->db->or_like('x3.subject_name', $keyword);
			$this->db->or_like('x4.subject_type', $keyword);
			$this->db->or_like('x1.score', $keyword);
		}
		$this->db->order_by('x5.registration_number', 'ASC');
		return $this->db->get(self::$table.' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('admission_subject_setting_details x2', 'x1.subject_setting_detail_id = x2.id', 'LEFT');
		$this->db->join('subjects x3', 'x2.subject_id = x3.id', 'LEFT');
		$this->db->join('admission_subject_settings x4', 'x2.subject_setting_id = x4.id', 'LEFT');
		$this->db->join('students x5', 'x1.student_id = x5.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x5.registration_number', $keyword);
			$this->db->or_like('x5.created_at', $keyword);
			$this->db->or_like('x5.full_name', $keyword);
			$this->db->or_like('x3.subject_name', $keyword);
			$this->db->or_like('x4.subject_type', $keyword);
			$this->db->or_like('x1.score', $keyword);
		}
		return $this->db->count_all_results(self::$table.' x1');
	}

	/**
	 * Find Subject Scores
	 * @param 	String
	 * @param 	String
	 * @return 	Array
	 */
	public function find_subject_scores($registration_number, $birth_date) {
		$this->db->select('x3.subject_name, x4.subject_type, x1.score');
		$this->db->join('admission_subject_setting_details x2', 'x1.subject_setting_detail_id = x2.id', 'LEFT');
		$this->db->join('subjects x3', 'x2.subject_id = x3.id', 'LEFT');
		$this->db->join('admission_subject_settings x4', 'x2.subject_setting_id = x4.id', 'LEFT');
		$this->db->join('students x5', 'x1.student_id = x5.id', 'LEFT');
		$this->db->where('x5.registration_number', $registration_number);
		$this->db->where('x5.birth_date', $birth_date);
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x2.is_deleted', 'false');
		$this->db->where('x3.is_deleted', 'false');
		$this->db->where('x4.is_deleted', 'false');
		$this->db->where('x5.is_deleted', 'false');
		$query = $this->db->get(self::$table.' x1');
		$data = [];
		foreach($query->result() as $row) {
			$data[] = [
				'subject_name' => $row->subject_name,
				'subject_type' => $row->subject_type,
				'score' => $row->score
			];
		}
		return $data;
	}

	/**
	 * Get Subject Scores by Student ID
	 * @param 	Int
	 * @return 	Resource
	 */
	public function subject_scores_by_student_id($student_id = 0) {
		$this->db->select('x1.id, x5.registration_number, LEFT(x5.created_at, 10) AS created_at, x5.full_name, x3.subject_name, x4.subject_type, x1.score, x1.is_deleted');
		$this->db->join('admission_subject_setting_details x2', 'x1.subject_setting_detail_id = x2.id', 'LEFT');
		$this->db->join('subjects x3', 'x2.subject_id = x3.id', 'LEFT');
		$this->db->join('admission_subject_settings x4', 'x2.subject_setting_id = x4.id', 'LEFT');
		$this->db->join('students x5', 'x1.student_id = x5.id', 'LEFT');
		$this->db->where('x5.id', $student_id);
		$this->db->order_by('x4.subject_type', 'DESC');
		return $this->db->get(self::$table.' x1');
	}
}
