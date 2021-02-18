<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Meeting_attendences extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
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
	 * Update Meeting Attendences
	 * @return Object
	 */
	public function update() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$meeting_attendences = json_decode($this->input->post('meeting_attendences'), true);
			$query = $this->m_meeting_attendences->update($meeting_attendences);
			$response['status'] = $query > 0 ? 'success' : 'error';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Get Meeting Attendences
	 */
	public function get_meeting_attendences() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$response['meeting_attendences'] = [];
			$course_class_id = (int) $this->input->post('course_class_id', true);
			$date = $this->input->post('date', true);
			if ($course_class_id !== 0 && ctype_digit((string) $course_class_id) && is_valid_date($date)) {
				// Get Class Meeting ID
				$class_meeting_id = $this->m_class_meetings->class_meeting_id($course_class_id, $date);
				// Get Course Class
				$query = $this->model->RowObject('course_classes', 'id', $course_class_id);
				// Generate if not exist Attendance
				$this->m_meeting_attendences->insert($class_meeting_id, $query->academic_year_id, $query->class_group_id);
				if ($class_meeting_id && $class_meeting_id > 0 && ctype_digit((string) $class_meeting_id)) {
					$response['type'] = 'success';
					$response['meeting_attendences'] = $this->m_meeting_attendences->get_meeting_attendences($class_meeting_id);
				}
			}
			
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}