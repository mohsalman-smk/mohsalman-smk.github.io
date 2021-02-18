<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Class_meetings extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_course_classes', 
			'm_class_meetings', 
			'm_meeting_attendences'
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
		$id = (int) $this->uri->segment(4);
		$query = $this->model->RowObject('course_classes', 'id', $id);
		if (is_object($query) && 
			// If isset current_semester_id
			NULL !== $this->session->userdata('current_academic_year_id') && 
			// If academic_year_id is equal to current_semester_id
			(int) $query->academic_year_id === (int) $this->session->userdata('current_academic_year_id') &&
			// If isset user_profile_id
			NULL !== $this->session->userdata('user_profile_id') && 
			// If user_profile_id is equal to $query->employee_id
			(int) $this->session->userdata('user_profile_id') === (int) $query->employee_id
		) {
			$this->load->helper('form');
			$this->vars['title'] = 'MASUK KELAS';
			$this->vars['academic_schedules'] = true;
			$this->vars['query'] = $this->m_course_classes->get_course_classes_by_id($id);
			$this->vars['content'] = 'teacher/class_meetings';
			$this->load->view('backend/index', $this->vars);
		} else {
			show_404();
		}
	}

	/**
	 * Check Class Meetings
	 */
	public function is_exist() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$response['type'] = 'error';
			$response['message'] = 'ID bukan tipe angka dan/atau tanggal bukan format yang benar';
			$course_class_id = (int) $this->input->post('course_class_id', true);
			$date = $this->input->post('date', true);
			if ($course_class_id !== 0 && ctype_digit((string) $course_class_id) && is_valid_date($date)) {
				$response['is_exist'] = $this->m_class_meetings->is_exist($course_class_id, $date);
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Insert Class Meetings
	 */
	public function insert() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$response['type'] = 'error';
			$response['message'] = 'ID bukan tipe angka dan/atau tanggal bukan format yang benar';
			$course_class_id = (int) $this->input->post('course_class_id', true);
			$date = $this->input->post('date', true);
			if ($course_class_id && $course_class_id > 0 && ctype_digit((string) $course_class_id) && is_valid_date($date)) {
				$response['status'] = $this->m_class_meetings->insert($course_class_id, $date) ? 'success' : 'error';
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Update Class Meetings
	 */
	public function update() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$response['type'] = 'error';
			$course_class_id = (int) $this->input->post('course_class_id', true);
			$date = $this->input->post('date', true);
			if ($course_class_id && $course_class_id > 0 && ctype_digit((string) $course_class_id) && is_valid_date($date)) {
				$query = $this->m_class_meetings->update($this->fill_data(), $course_class_id, $date);
				$response['type'] = $query ? 'success' : 'error';
				$response['method'] = 'update';
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Fill Data
	 * @return Array
	 */
	private function fill_data() {
		return [
			'start_time' => $this->input->post('start_time') ? $this->input->post('start_time', true) : date('H:i:s'),
			'end_time' => $this->input->post('end_time') ? $this->input->post('end_time', true) : date('H:i:s'),
			'discussion' => $this->input->post('discussion') ? $this->input->post('discussion', true) : NULL
		];
	}

	/**
	 * Get Class Meetings
	 */
	public function get_class_meetings() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$response['type'] = 'error';
			$response['message'] = 'ID bukan tipe angka dan/atau tanggal bukan format yang benar';
			$course_class_id = (int) $this->input->post('course_class_id', true);
			$date = $this->input->post('date', true);
			if ($course_class_id && $course_class_id > 0 && ctype_digit((string) $course_class_id) && is_valid_date($date)) {
				$class_meeting = $this->m_class_meetings->get_class_meetings($course_class_id, $date);
				$response['date'] = $class_meeting->date;
				$response['start_time'] = $class_meeting->start_time;
				$response['end_time'] = $class_meeting->end_time;
				$response['discussion'] = $class_meeting->discussion;
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Print Meeting Attendance
	 */
	public function print_meeting_attendance() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$response['type'] = 'error';
			$response['message'] = 'ID bukan tipe angka dan/atau tanggal bukan format yang benar';
			$course_class_id = (int) $this->input->post('course_class_id', true);
			$date = $this->input->post('date', true);
			if ($course_class_id && $course_class_id > 0 && ctype_digit((string) $course_class_id) && is_valid_date($date)) {
				// Class Meeting
				$class_meeting = $this->m_class_meetings->get_class_meetings($course_class_id, $date);
				$params['date'] = indo_date($class_meeting->date);
				$params['time'] = $class_meeting->start_time . ' - '. $class_meeting->end_time;
				$params['discussion'] = $class_meeting->discussion;
				// Course Class
				$course_class = $this->m_course_classes->get_course_classes_by_id($course_class_id);
				$params['academic_year'] = $course_class->academic_year;
				$params['semester'] = $course_class->semester;
				$params['subject_name'] = $course_class->subject_name;
				$params['class_group'] = $course_class->class_group;
				$params['full_name'] = $course_class->full_name;
				// Get Students
				$class_meeting_id = $this->m_class_meetings->class_meeting_id($course_class_id, $date);
				$params['students'] = $this->m_meeting_attendences->get_meeting_attendences($class_meeting_id);
				// PDF File Name
				$file_name = 'laporan-data-kehadiran-siswa-';
				$file_name .= $course_class->academic_year . '-';
				$file_name .= strtolower($course_class->semester) . '-';
				$file_name .= strtolower(str_replace(' ', '-', $course_class->subject_name)).'-';
				$file_name .= strtolower(str_replace(' ', '', $course_class->class_group)).'-';
				$file_name .= $class_meeting->date.'-';
				$file_name .= '.pdf';
				$params['file_name'] = $file_name;
				$this->load->library('Meeting_attendances');
				$this->meeting_attendances->create_pdf($params);
				$response['type'] = 'success';
				$response['file_name'] = $file_name;				
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}