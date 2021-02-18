<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Admission_exam_scores extends Admin_Controller {

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
			'm_admission_subject_scores'
		]);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'INPUT NILAI UJIAN TES TULIS';
		$this->vars['admission'] = $this->vars['admission_exam_scores'] = true;
		$this->vars['ds_admission_years'] = $this->m_academic_years->dropdown(true);
		$this->vars['ds_admission_types'] = $this->m_admission_types->dropdown();
		$majors = $this->m_majors->dropdown();
		if ($this->session->userdata('school_level') >= 3) {
			$majors  = [0 => 'Unset'] + $majors;
		}
		$this->vars['ds_majors'] = $majors;
		$this->vars['content'] = 'admission/admission_exam_scores';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get Prospective Students
	 */
	public function get_prospective_students() {
		if ($this->input->is_ajax_request()) {
			$admission_year_id = (int) $this->input->post('admission_year_id', true);
			$admission_type_id = (int) $this->input->post('admission_type_id', true);
			$major_id = $this->input->post('major_id', true);
			$page_number = (int) $this->input->post('page_number', true);
			$limit = (int) $this->input->post('per_page', true);
			$offset = ($page_number * $limit);
			// Generate Nilai Ujian Tes Tulis
			$this->m_admission_subject_scores->generate_subject_scores($admission_year_id, $admission_type_id, $major_id, 'exam_schedule');
			// Get Prospective Students
			$query = $this->m_admission_subject_scores->get_subject_scores($admission_year_id, $admission_type_id, $major_id, 'exam_schedule', $limit, $offset);
			$total_rows = $this->m_admission_subject_scores->total_rows($admission_year_id, $admission_type_id, $major_id, 'exam_schedule');
			$total_page = $limit > 0 ? ceil($total_rows / $limit) : 1;
			$response = [];
			$response['students'] = [];
			$response['total_rows'] = (int) $total_rows;
			$response['total_page'] = (int) $total_page;
			if (is_object($query)) {
				foreach($query->result() as $row) {
					$response['students'][] = [
						'id' => $row->id,
						'registration_number' => $row->registration_number,
						'full_name' => $row->full_name,
						'subject_name' => $row->subject_name,
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
	 * Save Scores
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$scores = json_decode($this->input->post('scores'), true);
			$response['message'] = $this->m_admission_subject_scores->save($scores) ? 'updated':'not_updated';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}