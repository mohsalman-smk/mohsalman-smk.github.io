<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Admission_exam_attendances extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_admission_exam_attendances', 
			'm_admission_exam_schedule_details', 
			'm_admission_subject_scores'
		]);
		$this->pk = m_admission_exam_attendances::$pk;
		$this->table = m_admission_exam_attendances::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$exam_schedule_id = (int) $this->uri->segment(4);
		if ($exam_schedule_id != 0 && ctype_digit((string) $exam_schedule_id)) {
			$query = $this->model->RowObject('admission_exam_schedules', 'id', $exam_schedule_id);
			$subject_setting_detail_id = $query->subject_setting_detail_id;
			$this->vars['title'] = 'Pengaturan Peserta Ujian Tes Tulis';
			$this->vars['query'] = $this->m_admission_exam_schedule_details->get_title($exam_schedule_id);
			$this->vars['admission'] = $this->vars['admission_settings'] = $this->vars['admission_exam_schedules'] = true;
			$this->vars['content'] = 'admission/admission_exam_attendance';
			$this->load->view('backend/index', $this->vars);
		} else {
			show_404();
		}
	}

	/**
	 * Get Prospective Students
	 * @return Object
	 */
	public function get_prospective_students() {
		if ($this->input->is_ajax_request()) {
			$exam_schedule_id = (int) $this->input->post('exam_schedule_id', true);
			$response = [];
			$response['students'] = [];
			if ($exam_schedule_id != 0 && ctype_digit((string) $exam_schedule_id)) {
				$query = $this->model->RowObject('admission_exam_schedules', 'id', $exam_schedule_id);
				$students = $this->m_admission_exam_attendances->get_prospective_students($query->subject_setting_detail_id);
				if ($students->num_rows() > 0) {
					foreach ($students->result() as $row) {
						$response['students'][] = [
							'student_id' => $row->student_id,
							'registration_number' => $row->registration_number,
							'full_name' => $row->full_name
						];
					}
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
	 * Get Attendances List
	 * @return Object
	 */
	public function get_attendance_lists() {
		if ($this->input->is_ajax_request()) {
			$exam_schedule_id = (int) $this->input->post('exam_schedule_id', true);
			$response = [];
			$response['students'] = [];
			if ($exam_schedule_id != 0 && ctype_digit((string) $exam_schedule_id)) {
				$query = $this->m_admission_exam_attendances->get_attendance_lists($exam_schedule_id);
				if ($query->num_rows() > 0) {
					foreach ($query->result() as $row) {
						$response['students'][] = [
							'id' => $row->id,
							'registration_number' => $row->registration_number,
							'full_name' => $row->full_name,
							'presence' => $row->presence
						];
					}
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
	 * Save Attendance List
	 * @return Object
	 */
	public function save_attendance_lists() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$student_ids = $this->input->post('student_ids', true);
			$exam_schedule_id = (int) $this->input->post('exam_schedule_id', true);
			$ids = [];
			foreach (explode(',', $student_ids) as $student_id) {
				array_push($ids, trim($student_id));
			}
			$query = $this->m_admission_exam_attendances->save_attendance_lists($ids, $exam_schedule_id);
			$response['type'] = $query ? 'success' : 'error';
			$response['message'] = $query ? 'Data sudah disipman' : 'Data tidak tersimpan. Kemungkinan terjadi duplikasi data atau server bermasalah, silahkan periksa kembali data Anda.';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Save Presences
	 * @return Object
	 */
	public function save_presences() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$presences = json_decode($this->input->post('presences'), true);
			$response['message'] = $this->m_admission_exam_attendances->save_presences($presences) ? 'updated':'not_updated';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Delete Attendance List
	 * @return Object
	 */
	public function delete_attendance_lists() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$ids = $this->input->post('ids', true);
			$array_ids = [];
			foreach (explode(',', $ids) as $id) {
				array_push($array_ids, trim($id));
			}
			$query = $this->m_admission_exam_attendances->delete_attendance_lists($array_ids);
			$response['type'] = $query ? 'success' : 'error';
			$response['message'] = $query ? 'Data sudah terhapus' : 'Data tidak terhapus.';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}