<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Semester_attendance_report extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_meeting_attendences'
			, 'm_academic_years'
			, 'm_class_groups'
		]);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->load->helper('form');
		$this->vars['title'] = 'REKAP PRESENSI PER SEMESTER';
		$this->vars['academic'] = $this->vars['student_attendance_report'] = $this->vars['semester_attendance_report'] = true;
		$this->vars['academic_year_dropdown'] = $this->m_academic_years->dropdown();
		$this->vars['class_group_dropdown'] = $this->m_class_groups->dropdown();
		$this->vars['content'] = 'class_meetings/semester_attendance_report';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get Attendance semester Report
	 */
	public function get_semester_attendance_report() {
		if ($this->input->is_ajax_request()) {
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$semester = $this->input->post('semester', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$response = [];
			$response['query'] = $this->m_meeting_attendences->get_semester_attendance_report($academic_year_id, $semester, $class_group_id);
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}