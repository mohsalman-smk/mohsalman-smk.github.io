<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_admission_selection_process extends CI_Model {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Prospective Students
	 * @param 	Int
	 * @param 	Int
	 * @access 	Public
	 * @return 	Resource
	 */
	public function get_prospective_students($admission_year_id = 0, $admission_type_id = 0, $major_id = 0, $limit = 0, $offset = 0) {
		// Define Admission Year
		$admission_year = $this->model->admission_year($admission_year_id);
		$fields = ['x1.id', 'x1.registration_number', 'x1.full_name'];
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			array_push($fields, "COALESCE(x2.major_name, '') AS first_choice", "COALESCE(x3.major_name, '') AS second_choice");
		}
		$this->db->select(implode(', ', $fields));
		// If SMK or PT
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
			$this->db->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.is_prospective_student', 'true');
		$this->db->where('x1.re_registration', 'true');
		$this->db->where('x1.selection_result IS NULL');
		$this->db->where('x1.admission_type_id', $admission_type_id);
		$this->db->where('LEFT(x1.registration_number, 4) = ', $admission_year);
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->where('x1.first_choice_id', $major_id);
		}
		// Chek if min birth Date and max birth da isset
		if (NULL !== $this->session->userdata('min_birth_date') && NULL !== $this->session->userdata('max_birth_date')) {
			$birth_dates = array_date($this->session->userdata('min_birth_date'), $this->session->userdata('max_birth_date'));
			$this->db->where_in('x1.birth_date', $birth_dates);
		}
		return $this->db->get('students x1', $limit, $offset);
	}

	/**
	 * Get Prospective Students
	 * @param 	Int
	 * @param 	Int
	 * @access 	Public
	 * @return 	Resource
	 */
	public function total_rows($admission_year_id = 0, $admission_type_id = 0, $major_id = 0) {
		// Define Admission Year
		$admission_year = $this->model->admission_year($admission_year_id);
		// If SMK or PT
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
			$this->db->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.is_prospective_student', 'true');
		$this->db->where('x1.re_registration', 'true');
		$this->db->where('x1.selection_result IS NULL');
		$this->db->where('x1.admission_type_id', $admission_type_id);
		$this->db->where('LEFT(x1.registration_number, 4) = ', $admission_year);
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->where('x1.first_choice_id', $major_id);
		}
		// Chek if min birth Date and max birth date isset
		$min_birth_date = $this->session->userdata('min_birth_date');
		$max_birth_date = $this->session->userdata('max_birth_date'); 
		if (NULL !== $min_birth_date && NULL !== $max_birth_date) {
			$birth_dates = array_date($min_birth_date, $max_birth_date);
			$this->db->where_in('x1.birth_date', $birth_dates);
		}
		return $this->db->count_all_results('students x1');
	}

	/**
	 * Selection Process
	 * @access 	Public
	 * @param 	Int
	 * @param 	Array
	 * @return 	Bool
	 */
	public function selection_process($admission_year_id = 0, $admission_type_id = 0, $selection_result, array $student_ids) {
		// Define Admission Year
		$admission_year = $this->model->admission_year($admission_year_id);
		// Default Quota
		$admission_quota = 0;
		// Check Quota
		$query = $this->db
			->select('quota')
			->where('academic_year_id', $admission_year_id)
			->where('admission_type_id', $admission_type_id)
			->where('major_id', (int) $selection_result)
			->get('admission_quotas');
		if ($query->num_rows() === 1) {
			$res = $query->row();
			$admission_quota = $res->quota;
		}

		if ($selection_result != 'unapproved') {
			// Check Selection Result
			$approved = $this->db
				->where('LEFT(registration_number, 4)=', $admission_year)
				->where('admission_type_id', $admission_type_id)
				->where('is_prospective_student', 'true')
				->group_start()
				->where('selection_result', $selection_result)
				->or_where('selection_result', 'approved')
				->group_end()
				->count_all_results('students');
			if (($admission_quota - $approved) < count($student_ids)) {
				return [
					'type' => 'warning',
					'message' => 'Kuota pendaftaran tidak mencukupi. Silahkan periksa kembali pengaturan kuota pendaftaran.'
				];		
			}
		}
		
		$fill_data = [
			'selection_result' => $selection_result,
			'updated_by' => $this->session->userdata('id')
		];

		// If Approved / Diterima PPDB/PMB nya
		if ($selection_result != 'unapproved') {
			$fill_data['is_student'] = 'true';
			$this->load->model('m_student_status');
			$student_status_id = (int) $this->m_student_status->find_student_status_id('aktif');
			$fill_data['student_status_id'] = $student_status_id;
		// Unapproved / Tidak Diterima
		} else {
			$fill_data['is_student'] = 'false';
			$fill_data['student_status_id'] = NULL;
		}

		// update major_id
		if ((int) $selection_result > 0) {
			$fill_data['major_id'] = (int) $selection_result;
		}
		$query = $this->db
			->where_in('id', $student_ids)
			->update('students', $fill_data);
		return [
			'type' => $query ? 'success' : 'error',
			'message' => $query ? 'Proses seleksi sudah tersimpan' : 'Proses seleksi tidak tersimpan'
		];
	}
}