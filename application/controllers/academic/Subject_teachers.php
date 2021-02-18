<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Subject_teachers extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_subject_teachers'
			, 'm_academic_years'
			, 'm_class_groups'
			, 'm_employees'
		]);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->load->helper('form');
		$this->vars['title'] = 'PENGATURAN ' . ($this->session->userdata('school_level') >= 5 ? 'DOSEN MATA KULIAH' : 'GURU MATA PELAJARAN');
		$this->vars['academic'] = $this->vars['academic_settings'] = $this->vars['subject_teachers'] = true;
		$this->vars['academic_year_dropdown'] = $this->m_academic_years->dropdown();
		$this->vars['class_group_dropdown'] = $this->m_class_groups->dropdown();
		$this->vars['employee_dropdown'] = json_encode([0 => 'Unset'] + $this->m_employees->dropdown());
		$this->vars['content'] = 'subject_teachers/create';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get Subjects
	 */
	public function get_subjects() {
		if ($this->input->is_ajax_request()) {
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$semester = $this->input->post('semester', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$query = $this->m_subject_teachers->get_subjects($academic_year_id, $semester, $class_group_id);
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Save Subject Teachers
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$course_classes = json_decode($this->input->post('course_classes'), true);
			$response['message'] = $this->m_subject_teachers->save($course_classes) ? 'updated':'not_updated';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
