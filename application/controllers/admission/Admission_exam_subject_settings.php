<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Admission_exam_subject_settings extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_admission_subject_setting_details', 
			'm_subjects'
		]);
		$this->pk = M_admission_subject_setting_details::$pk;
		$this->table = M_admission_subject_setting_details::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$subject_setting_id = (int) $this->uri->segment(4);
		if ($subject_setting_id != 0 && ctype_digit((string) $subject_setting_id)) {
			$query = $this->model->RowObject('admission_subject_settings', $this->pk, $subject_setting_id);
			$academic_year = $this->model->RowObject('academic_years', $this->pk, $query->academic_year_id);
			$admission_type = $this->model->RowObject('admission_types', $this->pk, $query->admission_type_id);
			if (in_array($this->session->userdata('school_level'), have_majors()) && (int) $query->major_id > 0) {
				$major = $this->model->RowObject('majors', $this->pk, $query->major_id);
			}
			$this->vars['title'] = 'Pengaturan Ujian Tes Tulis';
			$sub_title = $this->session->userdata('_academic_year') . ' ' . $academic_year->academic_year.' Jalur ' .$admission_type->admission_type;
			if (in_array($this->session->userdata('school_level'), have_majors()) && (int) $query->major_id > 0) {
				$sub_title .= ' - '. $major->major_name;
			}
			$this->vars['sub_title'] = $sub_title;
			$this->vars['admission'] = $this->vars['admission_settings'] = $this->vars['admission_exam_schedules'] = true;
			$this->vars['subjects_dropdown'] = json_encode($this->m_subjects->dropdown());
			$this->vars['content'] = 'admission/admission_exam_subject_settings';
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
			$subject_setting_id = (int) $this->input->post('subject_setting_id', true);
			$page_number = (int) $this->input->post('page_number', true);
			$limit = (int) $this->input->post('per_page', true);
			$keyword = trim($this->input->post('keyword', true));
			$offset = ($page_number * $limit);
			$query = $this->m_admission_subject_setting_details->get_where($subject_setting_id, $keyword, $limit, $offset);
			$total_rows = $this->m_admission_subject_setting_details->total_rows($subject_setting_id, $keyword);
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
				// Generate Subject Scores
				if ($fill_data['subject_setting_id']) {
					$query = $this->model->RowObject('admission_subject_settings', 'id', $fill_data['subject_setting_id']);
					$this->load->model('m_admission_subject_scores');
					$this->m_admission_subject_scores->generate_subject_scores($query->academic_year_id, $query->admission_type_id, $query->major_id, 'exam_schedule');
				}
				// Save Data
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
			'subject_setting_id' => (int) $this->input->post('subject_setting_id', true),
			'subject_id' => (int) $this->input->post('subject_id', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('subject_id', $this->session->userdata('_subject'), 'trim|is_natural_no_zero|required');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
