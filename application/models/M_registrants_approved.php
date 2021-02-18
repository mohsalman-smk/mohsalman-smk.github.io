<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_registrants_approved extends CI_Model {

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
	 * Admission Year
	 * @var Integer
	 */
	public $admission_year;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$year = $this->session->userdata('admission_year');
		$this->admission_year = (NULL !== $year && $year > 0) ? $year : date('Y');
	}

	/**
	 * Get Data
	 * @param 	String 
	 * @param 	Int
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_where($keyword = '', $limit = 10, $offset = 0) {
		$fields = [
			'x1.id'
			, 'x1.registration_number'
			, 'x1.re_registration'
			, 'x1.created_at'
			, 'x1.full_name'
			, 'x1.birth_date'
			, 'x1.gender'
		];
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			array_push($fields, 'x2.major_name AS selection_result');
		} else {
			array_push($fields, "IF(x1.selection_result = 'approved','Diterima','Tidak Diterima') AS selection_result");
		}
		$this->db->select(implode(', ', $fields));
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->join('majors x2', 'x1.selection_result = x2.id', 'LEFT');
		}
		$this->db->where('x1.is_prospective_student', 'true');
		$this->db->where('x1.selection_result IS NOT NULL');
		$this->db->where('x1.selection_result !=', 'unapproved');
		$this->db->where('LEFT(x1.registration_number, 4) = ', $this->admission_year);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.registration_number', $keyword);
			if (in_array($this->session->userdata('school_level'), have_majors())) {
				$this->db->or_like('x2.major_name', $keyword);
			} else {
				$this->db->or_like('x1.selection_result', $keyword);
			}
			$this->db->or_like('x1.re_registration', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
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
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->join('majors x2', 'x1.selection_result = x2.id', 'LEFT');
		}
		$this->db->where('x1.is_prospective_student', 'true');
		$this->db->where('x1.selection_result IS NOT NULL');
		$this->db->where('x1.selection_result !=', 'unapproved');
		$this->db->where('LEFT(x1.registration_number, 4) = ', $this->admission_year);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.registration_number', $keyword);
			if (in_array($this->session->userdata('school_level'), have_majors())) {
				$this->db->or_like('x2.major_name', $keyword);
			} else {
				$this->db->or_like('x1.selection_result', $keyword);
			}
			$this->db->or_like('x1.re_registration', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_place', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->or_like('x1.street_address', $keyword);
			$this->db->group_end();
		}
		$this->db->order_by('x1.registration_number', 'ASC');
		return $this->db->count_all_results('students x1');
	}
}