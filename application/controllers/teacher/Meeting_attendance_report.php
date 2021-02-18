<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Meeting_attendance_report extends Admin_Controller {

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
			, 'm_course_classes'
			, 'm_subjects'
		]);
		// Jika bukan Guru, redirect ke dashboard
		$employment_type = $this->session->userdata('employment_type'); 
		if (NULL !== $employment_type && strpos(strtolower($employment_type), 'guru') === FALSE) {
			redirect('dashboard', 'refresh');
		}
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->load->helper('form');
		$this->vars['title'] = 'REKAP PRESENSI';
		$this->vars['meeting_attendance_report'] = true;
		$this->vars['academic_year_dropdown'] = $this->m_academic_years->dropdown();
		$this->vars['class_group_dropdown'] = $this->m_class_groups->dropdown();
		$this->vars['subject_dropdown'] = $this->m_subjects->dropdown();
		$this->vars['content'] = 'teacher/meeting_attendance_report';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Summary Report
	 */
	public function summary_report() {
		if ($this->input->is_ajax_request()) {
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$semester = $this->input->post('semester', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$subject_id = (int) $this->input->post('subject_id', true);
			$start_date = substr($this->input->post('start_date', true), 0, 10);
			$end_date = substr($this->input->post('end_date', true), 0, 10);
			if (!is_valid_date($start_date)) {
				$start_date = date('Y-m-d');
			}
			if (!is_valid_date($end_date)) {
				$end_date = date('Y-m-d');
			}
			$employee_id = (int) $this->session->userdata('user_profile_id');
			$course_class_id = $this->m_course_classes->find_id($academic_year_id, $semester, $class_group_id, $subject_id, $employee_id);
			$query = [];
			if ($course_class_id !== 0) {
				$query = $this->m_meeting_attendences->get_meeting_attendance_summary_report($course_class_id, $start_date, $end_date);
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Detail Report
	 */
	public function detail_report() {
		if ($this->input->is_ajax_request()) {
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$semester = $this->input->post('semester', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$subject_id = (int) $this->input->post('subject_id', true);
			$start_date = substr($this->input->post('start_date', true), 0, 10);
			$end_date = substr($this->input->post('end_date', true), 0, 10);
			if (!is_valid_date($start_date)) {
				$start_date = date('Y-m-d');
			}
			if (!is_valid_date($end_date)) {
				$end_date = date('Y-m-d');
			}
			$employee_id = (int) $this->session->userdata('user_profile_id');
			$course_class_id = $this->m_course_classes->find_id($academic_year_id, $semester, $class_group_id, $subject_id, $employee_id);
			$response = [];
			$response['dates'] = array_date($start_date, $end_date);
			$response['query'] = [];
			if ($course_class_id !== 0) {
				$response['query'] = $this->m_meeting_attendences->get_meeting_attendance_detail_report($course_class_id, $start_date, $end_date);
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}