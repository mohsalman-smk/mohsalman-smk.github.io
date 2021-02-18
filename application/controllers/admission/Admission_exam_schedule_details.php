<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Admission_exam_schedule_details extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_admission_exam_schedule_details', 
			'm_rooms', 
			'm_admission_exam_attendances'
		]);
		$this->pk = M_admission_exam_schedule_details::$pk;
		$this->table = M_admission_exam_schedule_details::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$subject_setting_detail_id = (int) $this->uri->segment(4);
		if ($subject_setting_detail_id != 0 && ctype_digit((string) $subject_setting_detail_id)) {
			$query = $this->model->RowObject('admission_subject_setting_details', $this->pk, $subject_setting_detail_id);
			$subjects = $this->model->RowObject('subjects', $this->pk, $query->subject_id);
			$subject_setting = $this->model->RowObject('admission_subject_settings', $this->pk, $query->subject_setting_id);
			$academic_year = $this->model->RowObject('academic_years', $this->pk, $subject_setting->academic_year_id);
			$admission_type = $this->model->RowObject('admission_types', $this->pk, $subject_setting->admission_type_id);
			if (in_array($this->session->userdata('school_level'), have_majors()) && (int) $subject_setting->major_id > 0) {
				$major = $this->model->RowObject('majors', $this->pk, $subject_setting->major_id);
			}
			$this->vars['title'] = 'Pengaturan Ujian tes Tulis';
			$sub_title = $this->session->userdata('_academic_year') . ' ' . $academic_year->academic_year.' Jalur ' .$admission_type->admission_type;
			if (in_array($this->session->userdata('school_level'), have_majors()) && (int) $subject_setting->major_id > 0) {
				$sub_title .= ' - ' . $this->session->userdata('_major') . ' ' . $major->major_name;
			}
			$sub_title .= ' Mata Pelajaran '. $subjects->subject_name;
			$this->vars['sub_title'] = $sub_title;
			$this->vars['admission'] = $this->vars['admission_settings'] = $this->vars['admission_exam_schedules'] = true;
			$this->vars['rooms_dropdown'] = json_encode($this->m_rooms->dropdown());
			$this->vars['content'] = 'admission/admission_exam_schedule_details';
			$this->load->view('backend/index', $this->vars);
		} else {
			show_404();
		}
	}

	/**
	 * Pagination
	 * @return Object
	 */
	public function pagination() {
		if ($this->input->is_ajax_request()) {
			$subject_setting_detail_id = (int) $this->input->post('subject_setting_detail_id', true);
			$page_number = (int) $this->input->post('page_number', true);
			$limit = (int) $this->input->post('per_page', true);
			$keyword = trim($this->input->post('keyword', true));
			$offset = ($page_number * $limit);
			$query = $this->m_admission_exam_schedule_details->get_where($subject_setting_detail_id, $keyword, $limit, $offset);
			$total_rows = $this->m_admission_exam_schedule_details->total_rows($subject_setting_detail_id, $keyword);
			$total_page = $limit > 0 ? ceil($total_rows / $limit) : 1;
			$response = [];
			$response['total_page'] = 0;
			$response['total_rows'] = 0;
			if ($query->num_rows() > 0) {
				$rows = [];
				foreach($query->result() as $row) {
					$rows[] = $row;
				}
				$response = [
					'total_page' => (int) $total_page,
					'total_rows' => (int) $total_rows,
					'rows' => $rows
				];
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Find by ID
	 * @return Void
	 */
	public function find_id() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$query = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				$query = $this->model->RowObject($this->table, $this->pk, $id);
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Save or Update
	 * @return void
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			if ($this->validation()) {
				$fill_data = $this->fill_data();
				if ($id !== 0 && ctype_digit((string) $id)) {
					$fill_data['updated_at'] = date('Y-m-d H:i:s');
					$fill_data['updated_by'] = $this->session->userdata('id');
					$response['action'] = 'update';
					$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated';
				} else {
					$fill_data['created_by'] = $this->session->userdata('id');
					$response['action'] = 'save';
					$response['type'] = $this->model->insert($this->table, $fill_data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'created' : 'not_created';
				}
			} else {
				$response['action'] = 'validation_errors';
				$response['type'] = 'error';
				$response['message'] = validation_errors();
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
			'subject_setting_detail_id' => $this->input->post('subject_setting_detail_id', true),
			'room_id' => $this->input->post('room_id', true),
			'exam_date' => $this->input->post('exam_date', true),
			'exam_start_time' => $this->input->post('exam_start_time', true),
			'exam_end_time' => $this->input->post('exam_end_time', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('room_id', 'Ruang Ujian', 'trim|is_natural_no_zero|required');
		$val->set_rules('exam_date', 'Tanggal Pelaksanaan', 'trim|required');
		$val->set_rules('exam_start_time', 'Jam Mulai', 'trim|required');
		$val->set_rules('exam_end_time', 'Jam Selesai', 'trim|required');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Generate PDF Exam Attendances
	 */
	public function print_exam_attendances() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				$header = $this->m_admission_exam_schedule_details->get_title($id);
				$students = $this->m_admission_exam_attendances->get_attendance_lists($id);
				$file_name = 'daftar-hadir-ujian-tes-tulis-penerimaan-'. ($this->session->userdata('school_level') >= 5 ? 'mahasiswa' : 'peserta-didik').'-baru-tahun-'.$this->session->userdata('admission_year').'.pdf';
				$this->load->library('Exam_attendances');
				$this->exam_attendances->create_pdf($header, $students);
				$response['type'] = 'success';
				$response['file_name'] = $file_name;
			} else {
				$response['type'] = 'error';
				$response['message'] = 'Format data tidak valid.';
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
