<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Selection_process extends Admin_Controller {
	
	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->model([
			'm_majors', 
			'm_academic_years', 
			'm_admission_types',
			'm_subjects',
			'm_admission_selection_process',
			'm_admission_subject_scores'
		]);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'PROSES SELEKSI';
		$this->vars['admission'] = $this->vars['selection_process'] = true;
		$options = [];
		$options['unapproved'] = 'Tidak Diterima';
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$query = $this->m_majors->dropdown();
			foreach ($query as $key => $value) {
				$options[$key] = 'Diterima di '. $value;
			}
		} else {
			$options['approved'] = 'Diterima';
		}
		$this->vars['options'] = $options;
		$this->vars['ds_admission_years'] = $this->m_academic_years->dropdown(true);
		$this->vars['ds_admission_types'] = $this->m_admission_types->dropdown();
		$majors = $this->m_majors->dropdown();
		if ($this->session->userdata('school_level') >= 3) {
			$majors  = [0 => 'Unset'] + $majors;
		}
		$this->vars['ds_majors'] = $majors;
		$this->vars['content'] = 'admission/selection_process';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get Prospective Students
	 */
	public function get_prospective_students() {
		if ($this->input->is_ajax_request()) {
			$admission_year_id = (int) $this->input->post('admission_year_id', true);
			$admission_type_id = (int) $this->input->post('admission_type_id', true);
			$major_id = (int) $this->input->post('major_id', true);
			$page_number = (int) $this->input->post('page_number', true);
			$limit = (int) $this->input->post('per_page', true);
			$offset = ($page_number * $limit);
			// Generate Exam Subject Scores / Nilai Ujian Tes Tulis
			$this->m_admission_subject_scores->generate_subject_scores($admission_year_id, $admission_type_id, $major_id, 'exam_schedule');
			// Generate Semester Report Scores / Nilai Rapor Sekolah
			$this->m_admission_subject_scores->generate_subject_scores($admission_year_id, $admission_type_id, $major_id, 'semester_report');
			// Generate National Exam Score / Nilai Ujian Nasional
			$this->m_admission_subject_scores->generate_subject_scores($admission_year_id, $admission_type_id, $major_id, 'national_exam');
			// Get Prosvective Students
			$query = $this->m_admission_selection_process->get_prospective_students($admission_year_id, $admission_type_id, $major_id, $limit, $offset);
			$total_rows = $this->m_admission_selection_process->total_rows($admission_year_id, $admission_type_id, $major_id);
			$total_page = $limit > 0 ? ceil($total_rows / $limit) : 1;
			$response = [];
			$response['total_rows'] = (int) $total_rows;
			$response['total_page'] = (int) $total_page;
			$response['students'] = [];
			if (in_array($this->session->userdata('school_level'), have_majors())) {
				foreach($query->result() as $row) {
					$response['students'][] = [
						'id' => $row->id,
						'first_choice' => $row->first_choice,
						'second_choice' => $row->second_choice,
						'registration_number' => $row->registration_number,
						'full_name' => $row->full_name
					];
				}
			} else {
				foreach($query->result() as $row) {
					$response['students'][] = [
						'id' => $row->id,
						'registration_number' => $row->registration_number,
						'full_name' => $row->full_name
					];
				}
			}

			// Get Subjects
			$response['subjects'] = [];
			foreach($this->m_subjects->dropdown() as $key => $value) {
				$response['subjects'][$key] = $value;
			}

			// Get Admission Exam Scores
			$exam_scores = $this->m_admission_subject_scores->get_subject_scores($admission_year_id, $admission_type_id, $major_id, 'exam_schedule');
			$response['admission_exam_scores'] = [];
			if (is_object($exam_scores)) {
				foreach ($exam_scores->result() as $row) {
					$response['admission_exam_scores'][] = [
						'student_id' => $row->student_id,
						'subject_id' => $row->subject_id,
						'score' => $row->score
					];
				}
			}
				
			// Get Semester Report Scores
			$semester_scores = $this->m_admission_subject_scores->get_subject_scores($admission_year_id, $admission_type_id, $major_id, 'semester_report');
			$response['semester_report_scores'] = [];
			if (is_object($semester_scores)) {
				foreach ($semester_scores->result() as $row) {
					$response['semester_report_scores'][] = [
						'student_id' => $row->student_id,
						'subject_id' => $row->subject_id,
						'score' => $row->score
					];	
				}
			}

			// Get National Exam Scores
			$national_exam_scores = $this->m_admission_subject_scores->get_subject_scores($admission_year_id, $admission_type_id, $major_id, 'national_exam');
			$response['national_exam_scores'] = [];
			if (is_object($national_exam_scores)) {
				foreach ($national_exam_scores->result() as $row) {
					$response['national_exam_scores'][] = [
						'student_id' => $row->student_id,
						'subject_id' => $row->subject_id,
						'score' => $row->score
					];	
				}
			}
			
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * save
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$admission_year_id = (int) $this->input->post('admission_year_id', true);
			$admission_type_id = (int) $this->input->post('admission_type_id', true);
			$selection_result = $this->input->post('selection_result', true);
			$student_ids = explode(',', $this->input->post('student_ids'));
			$query = $this->m_admission_selection_process->selection_process($admission_year_id, $admission_type_id, $selection_result, $student_ids);
			$response['type'] = $query['type'];
			$response['message'] = $query['message'];
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}