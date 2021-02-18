<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_registrants extends CI_Model {

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
	 * Save Registration Form
	 * @access 	public
	 * @param Array
	 * @param Array
	 * @return Bool
	 */
	public function save_registration_form(array $fill_data, array $subject_scores) {
		$this->db->trans_start();
		// Insert to Students
		$this->db->insert(self::$table, $fill_data);
		// Get last ID
		$student_id = $this->db->insert_id();
		// Update created_by
		$this->db->where(self::$pk, $student_id)->update(self::$table, ['created_by' => $student_id]);
		// Insert Student Subject Values
		if (count($subject_scores) > 0) {
			foreach($subject_scores as $subject_setting_detail_id => $score) {
				// convert to float number
				$score = str_replace(' ', '', trim($score));
				$score = str_replace(',', '.', $score);
				$score = floatval($score);
				$score = number_format($score, 2, '.', '');
				if ($score > 100) {
					$score = 0.00;
				}
				$fill_data = [
					'subject_setting_detail_id' => $subject_setting_detail_id,
					'score' => $score,
					'student_id' => $student_id,
					'created_by' => $student_id
				];
				$this->db->insert('admission_subject_scores', $fill_data);
			}
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * Get Data
	 * @access 	public
	 * @param String
	 * @param Int
	 * @param Int
	 * @return Resource
	 */
	public function get_where($keyword = '', $limit = 10, $offset = 0) {
		$fields = [
			'x1.id'
			, 'x1.registration_number'
			, 'x1.re_registration'
			, 'LEFT(x1.created_at, 10) AS created_at'
			, 'x1.full_name'
			, 'x1.birth_date'
			, 'x1.gender'
			// , 'x1.photo'
			// , 'x4.admission_type'
			// , 'x5.phase_name'
			, 'x1.is_deleted'
		];
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			array_push($fields, "COALESCE(x2.major_name, '-') AS first_choice");
			array_push($fields, "COALESCE(x3.major_name, '-') AS second_choice");
		}
		$this->db->select(implode(', ', $fields));
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
			$this->db->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
		}
		// $this->db->join('admission_types x4', 'x1.admission_type_id = x4.id', 'LEFT');
		// $this->db->join('admission_phases x5', 'x1.admission_phase_id = x5.id', 'LEFT');
		$this->db->where('x1.is_prospective_student', 'true');
		$this->db->where('LEFT(x1.registration_number, 4) = ', $this->admission_year);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.registration_number', $keyword);
			$this->db->or_like('x1.re_registration', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
			if (in_array($this->session->userdata('school_level'), have_majors())) {
				$this->db->or_like('x2.major_name', $keyword);
				$this->db->or_like('x3.major_name', $keyword);
			}		
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_place', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->or_like('x1.street_address', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table.' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @access 	public
	 * @param String
	 * @return Int
	 */
	public function total_rows($keyword = '') {
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
			$this->db->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
		}
		// $this->db->join('admission_types x4', 'x1.admission_type_id = x4.id', 'LEFT');
		// $this->db->join('admission_phases x5', 'x1.admission_phase_id = x5.id', 'LEFT');
		$this->db->where('x1.is_prospective_student', 'true');
		$this->db->where('LEFT(x1.registration_number, 4) = ', $this->admission_year);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.registration_number', $keyword);
			$this->db->or_like('x1.re_registration', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			if (in_array($this->session->userdata('school_level'), have_majors())) {
				$this->db->or_like('x2.major_name', $keyword);
				$this->db->or_like('x3.major_name', $keyword);
			}		
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_place', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->or_like('x1.street_address', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results('students x1');
	}

	/**
	 * Generate Registration Number
	 * @access 	public
	 * @return 	Bool
	 */
	public function registration_number() {
		$admission_year = $this->admission_year;
		$query = $this->db->query("
			SELECT MAX(RIGHT(registration_number, 6)) AS max_number
			FROM students
			WHERE is_prospective_student='true'
			AND LEFT(registration_number, 4) = ?
		", [$admission_year]);

		$registration_number = "000001";
		if ($query->num_rows() === 1) {
			$data = $query->row();
			$number = ((int) $data->max_number) + 1;
			$registration_number = sprintf("%06s", $number);
		}
		return $admission_year . $registration_number;
	}

	/**
	 * Selection Result
	 * @access 	public
	 * @param 	String
	 * @param 	String
	 * @return 	array
	 */
	public function selection_result($registration_number, $birth_date) {
		$query = $this->db
			->select('full_name, selection_result')
			->where('registration_number', $registration_number)
			->where('birth_date', $birth_date)
			->get(self::$table);
		if ($query->num_rows() === 1) {
			$result = $query->row();
			if (is_null($result->selection_result)) {
				return [
					'type' => 'info',
					'message' => 'Proses seleksi belum selesai.'
				];
			} else {
				if (in_array($this->session->userdata('school_level'), have_majors())) {
					if ($result->selection_result === 'unapproved') {
						return [
							'type' => 'info',
							'message' => 'Mohon Maaf '. $result->full_name . '<br>Anda Tidak Lolos Seleksi Penerimaan '.strtoupper($this->session->userdata('_student')).' Baru '.$this->session->userdata('school_name').' Tahun '. $this->admission_year
						];
					} else {
						$majors = $this->model->RowObject('majors', 'id', $result->selection_result);
						return [
							'type' => 'success',
							'message' => 'Selamat '. $result->full_name.'!<br>Anda diterima di ' . $majors->major_name . ' ' . $this->session->userdata('school_name').' Tahun '. $this->admission_year
						];
					}
				} else {
					if ($result->selection_result === 'unapproved') {
						return [
							'title' => 'info',
							'message' => 'Mohon Maaf '. $result->full_name . '<br>Anda Tidak Lolos Seleksi Penerimaan '.strtoupper($this->session->userdata('_student')).' Baru '.$this->session->userdata('school_name').' Tahun '. $this->admission_year
						];
					} else {
						return [
							'type' => 'success',
							'message' => 'Selamat '. $result->full_name.'!<br>Anda Lolos Seleksi Penerimaan '.strtoupper($this->session->userdata('_student')).' Baru '.$this->session->userdata('school_name').' Tahun '. $this->admission_year
						];
					}
				}
			}
		}

		return [
			'type' => 'warning',
			'message' => 'Data dengan tanggal lahir '.indo_date($birth_date).' dan nomor pendaftaran '.$registration_number.' tidak ditemukan.'
		];
	}

	/**
	 * Find Registrant
	 * @access 	public
	 * @param 	String
	 * @return 	array
	 */
	public function find_registrant($birth_date, $registration_number) {
		$this->db->select("
			x1.id
		  , IF(x1.is_transfer='true', 'Pindahan', 'Baru') AS is_transfer
		  , x6.admission_type
		  , x1.registration_number
		  , x1.prev_school_name
		  , x1.prev_school_address
		  , x1.created_at
		  , x2.major_name AS first_choice
		  , x3.major_name AS second_choice
		  , x1.full_name
		  , IF(x1.gender = 'M', 'Laki-laki', 'Perempuan') AS gender
		  , x1.nisn
		  , x1.nik
		  , x1.birth_place
		  , x1.birth_date
		  , x4.option_name AS religion
		  , x5.option_name AS special_needs
		  , x1.street_address
		  , x1.rt
		  , x1.rw
		  , x1.sub_district
		  , x1.district
		  , x1.sub_village
		  , x1.village
		  , x1.postal_code
		  , x1.email
		");
		$this->db->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
		$this->db->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
		$this->db->join('options x4', 'x1.religion_id = x4.id', 'LEFT');
		$this->db->join('options x5', 'x1.special_need_id = x5.id', 'LEFT');
		$this->db->join('admission_types x6', 'x1.admission_type_id = x6.id', 'LEFT');
		$this->db->where('x1.birth_date', $birth_date);
		$this->db->where('x1.registration_number', $registration_number);
		return $this->db
			->get('students x1')
			->row_array();
	}

	/**
	 * Is Valid Registrant
	 * @access 	public
	 * @param 	String
	 * @param 	String
	 * @return 	bool
	 */
	public function is_valid_registrant($registration_number, $birth_date) {
		$this->db->where('birth_date', $birth_date);
		$this->db->where('registration_number', $registration_number);
		$count = $this->db->count_all_results(self::$table);
		return $count > 0;
	}

	/**
	 * Admission Reports
	 * @access 	public
	 * @return 	Resource
	 */
	public function admission_reports() {
		$this->load->model('m_students');
		$query = $this->m_students->student_query();
		$query .= "		
			AND x1.is_prospective_student='true'
			ORDER BY x1.registration_number ASC
		";
		return $this->db->query($query);
	}
}