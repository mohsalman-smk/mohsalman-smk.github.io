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
	 * Get Data
	 * @param 	String
	 * @param 	Int
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_where($keyword = '', $limit = 10, $offset = 0) {
		$this->db->select("
			x1.id
			, x1.nik
			, x1.full_name
			, x2.option_name AS employment_type
			, x1.gender
			, COALESCE(x1.birth_place, '') birth_place
			, x1.birth_date
			, x1.photo, x1.is_deleted
		");
		$this->db->join('options x2', 'x1.employment_type_id = x2.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x1.nik', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_place', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->or_like('x2.option_name', $keyword);
		}
		return $this->db->get(self::$table. ' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('options x2', 'x1.employment_type_id = x2.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x1.nik', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_place', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->or_like('x2.option_name', $keyword);
		}
		return $this->db->count_all_results(self::$table.' x1');
	}

	/**
	 * Dropdown
	 * @access Public
	 * @return Array
	 */
	public function dropdown() {
		$query = $this->db
			->select('id, nik, full_name')
			->where('is_deleted', 'false')
			->order_by('full_name', 'ASC')
			->get(self::$table);
		$data = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->id] = $row->nik .' - '. $row->full_name;
			}
		}
		return $data;
	}

	/**
	 * Get Employment Type
	 * @param Integer
	 * @return String
	 */
	public function get_employment_type($id) {
		$query = $this->model->RowObject(self::$table, self::$pk, $id);
		if (is_object($query)) {
			$employment_type = $this->model->RowObject('options', 'id', $query->employment_type_id);
			if (is_object($employment_type)) {
				return $employment_type->option_name;
			}
			return NULL;
		}
		return NULL;
	}

	/**
	 * get_active_employees
	 * @return Resource
	 */
	public function get_active_employees() {
		return $this->db
			->select('id, nik, full_name, email')
			->where('is_deleted', 'false')
			->get(self::$table);
	}

	/**
	 * Profile
	 * @param 	Int
	 * @return 	Resource
	 */
	public function profile($id) {
		return $this->db->query("
			SELECT x1.id
				, x1.assignment_letter_number
				, x1.assignment_letter_date
				, x1.assignment_start_date
				, x1.parent_school_status
				, x1.full_name
				, x1.gender
				, x1.nik
				, x1.birth_place
				, x1.birth_date
				, x1.mother_name
				, x1.street_address
				, x1.rt
				, x1.rw
				, x1.sub_village
				, x1.village
				, x1.sub_district
				, x1.district
				, x1.postal_code
				, x2.option_name AS religion
				, x3.option_name AS marriage_status
				, x1.spouse_name
				, x4.option_name AS spouse_employment
				, x1.citizenship
				, x1.country
				, x1.npwp
				, x5.option_name AS employment_status
				, x1.nip
				, x1.niy
				, x1.nuptk
				, x6.option_name AS employment_type
				, x1.decree_appointment
				, x1.appointment_start_date
				, x7.option_name AS institutions_lifter
				, x1.decree_cpns
				, x1.pns_start_date
				, x11.option_name AS rank
				, x8.option_name AS salary_sources
				, x1.headmaster_license
				, x9.option_name AS laboratory_skills
				, x10.option_name AS special_needs
				, x1.braille_skills
				, x1.sign_language_skills
				, x1.phone
				, x1.mobile_phone
				, x1.email
				, x1.photo
			FROM employees x1
			LEFT JOIN options x2
				ON x1.religion_id = x2.id
			LEFT JOIN options x3
				ON x1.marriage_status_id = x3.id
			LEFT JOIN options x4
				ON x1.spouse_employment_id = x4.id
			LEFT JOIN options x5
				ON x1.employment_status_id = x5.id
			LEFT JOIN options x6
				ON x1.employment_type_id = x6.id
			LEFT JOIN options x7
				ON x1.institution_lifter_id = x7.id
			LEFT JOIN options x8
				ON x1.salary_source_id = x8.id
			LEFT JOIN options x9
				ON x1.laboratory_skill_id = x9.id
			LEFT JOIN options x10
				ON x1.special_need_id = x10.id
			LEFT JOIN options x11
				ON x1.rank_id = x11.id
			WHERE x1.id = ?
		", [(int) $id])->row();
	}
}
