<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_admission_subject_scores extends CI_Model {

	/**
	 * Get Subject Scores
	 * @param 	Int
	 * @param 	Int
	 * @param 	Int
	 * @param 	String
	 * @param 	Int
	 * @param 	Int
	 * @access 	Public
	 * @return 	Resource
	 */
	public function get_subject_scores($admission_year_id = 0, $admission_type_id = 0, $major_id = 0, $subject_type = 'semester_report', $limit = 0, $offset = 0) {
		$this->db->select('
			x1.id
			, x2.id AS student_id
			, x2.registration_number
			, x2.full_name
			, x4.id AS subject_id
			, x4.subject_name
			, x1.score
		');
		$this->db->join('students x2', 'x1.student_id = x2.id', 'LEFT');
		$this->db->join('admission_subject_setting_details x3', 'x1.subject_setting_detail_id = x3.id', 'LEFT');
		$this->db->join('subjects x4', 'x3.subject_id = x4.id', 'LEFT');
		$this->db->join('admission_subject_settings x5', 'x3.subject_setting_id = x5.id', 'LEFT');
		$this->db->where('x5.academic_year_id', $admission_year_id);
		$this->db->where('x5.admission_type_id', $admission_type_id);
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->where('x2.first_choice_id', $major_id);
		}
		$this->db->where('x5.subject_type', $subject_type);
		$this->db->where('x2.is_prospective_student', 'true');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x2.is_deleted', 'false');
		$this->db->where('x3.is_deleted', 'false');
		$this->db->where('x4.is_deleted', 'false');
		$this->db->where('x5.is_deleted', 'false');
		$this->db->order_by('x2.registration_number', 'ASC');
		$this->db->order_by('x4.subject_name', 'ASC');
		return $this->db->get('admission_subject_scores x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	Int
	 * @param 	Int
	 * @param 	Int
	 * @param 	String
	 * @access 	Public
	 * @return 	Resource
	 */
	public function total_rows($admission_year_id = 0, $admission_type_id = 0, $major_id = 0, $subject_type = 'semester_report') {
		$this->db->join('students x2', 'x1.student_id = x2.id', 'LEFT');
		$this->db->join('admission_subject_setting_details x3', 'x1.subject_setting_detail_id = x3.id', 'LEFT');
		$this->db->join('subjects x4', 'x3.subject_id = x4.id', 'LEFT');
		$this->db->join('admission_subject_settings x5', 'x3.subject_setting_id = x5.id', 'LEFT');
		$this->db->where('x5.academic_year_id', $admission_year_id);
		$this->db->where('x5.admission_type_id', $admission_type_id);
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->where('x2.first_choice_id', $major_id);
		}
		$this->db->where('x5.subject_type', $subject_type);
		$this->db->where('x2.is_prospective_student', 'true');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x2.is_deleted', 'false');
		$this->db->where('x3.is_deleted', 'false');
		$this->db->where('x4.is_deleted', 'false');
		$this->db->where('x5.is_deleted', 'false');
		$this->db->order_by('x2.registration_number', 'ASC');
		$this->db->order_by('x4.subject_name', 'ASC');
		return $this->db->count_all_results('admission_subject_scores x1');
	}

	/**
	 * Save Admission Exam Scores
	 * @param 	Array
	 * @access 	Public
	 * @return 	Bool
	 */
	public function save($params) {
		$counter = 0;
		foreach ($params as $row) {
			$score = str_replace(' ', '', trim($row['score']));
			$score = str_replace(',', '.', $score);
			$score = floatval($score);
			$score = number_format($score, 2, '.', '');
			if ($score > 100) {
				$score = 0.00;
			}
			$fill_data = [
				'updated_by' => $this->session->userdata('id'),
				'score' => $score
			];
			$query = $this->db
				->where('id', $row['id'])
				->update('admission_subject_scores', $fill_data);
			if ($query) {
				$counter++;
			}
		}

		return $counter > 0;
	}

	/**
	 * Generate Subject Scores
	 * @param 	Int
	 * @param 	Int
	 * @param 	Int
	 * @param 	String
	 * @access 	Public
	 * @return 	Void
	 */
	public function generate_subject_scores($admission_year_id = 0, $admission_type_id = 0, $major_id = 0, $subject_type = 'semester_report') {
		// Define Admission Year
		$admission_year = $this->model->admission_year($admission_year_id);
		// Get Prospective Student ID
		$this->db->select('id');
		$this->db->where('is_prospective_student', 'true');
		$this->db->where('admission_type_id', $admission_type_id);
		$this->db->where('LEFT(registration_number, 4) =', $admission_year);
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->where('first_choice_id', $major_id);
		}
		if ($subject_type == 'exam_schedule') {
			$this->db->where('re_registration', 'true'); // Daftar Ulang
		}
		$this->db->where('is_deleted', 'false');
		$students = $this->db->get('students');
		if ($students->num_rows() > 0) {
			// Get ID From admission_subject_settings
			$this->db->select('id');
			$this->db->where('academic_year_id', $admission_year_id);
			$this->db->where('admission_type_id', $admission_type_id);
			$this->db->where('major_id', $major_id);
			$this->db->where('subject_type', $subject_type);
			$this->db->where('is_deleted', 'false');
			$admission_subject_settings = $this->db->get('admission_subject_settings');
			if ($admission_subject_settings->num_rows() === 1) {
				$admission_subject_setting = $admission_subject_settings->row();
				// Get ID From admission_subject_setting_details
				$admission_subject_setting_details = $this->db
					->select('id')
					->where('subject_setting_id', $admission_subject_setting->id)
					->where('is_deleted', 'false')
					->get('admission_subject_setting_details');
				if ($admission_subject_setting_details->num_rows() > 0) {
					foreach ($students->result() as $student) {
						foreach($admission_subject_setting_details->result() as $detail) {
							// Chek If Exist
							$count = $this->db
								->where('subject_setting_detail_id', $detail->id)
								->where('student_id', $student->id)
								->count_all_results('admission_subject_scores');
							if ($count === 0) {
								// Insert Subjects to admission_subject_scores
								$fill_data = [
									'subject_setting_detail_id' => $detail->id,
									'student_id' => $student->id,
									'created_by' => $this->session->userdata('id')
								];
								$this->db->insert('admission_subject_scores', $fill_data);
							}
						}
					}
				}
			}
		}
	}
}