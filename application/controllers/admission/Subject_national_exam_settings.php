<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Subject_national_exam_settings extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_admission_subject_settings', 
			'm_academic_years', 
			'm_majors', 
			'm_admission_types'
		]);
		$this->pk = M_admission_subject_settings::$pk;
		$this->table = M_admission_subject_settings::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Pengaturan Nilai Ujian Nasional';
		$this->vars['admission'] = $this->vars['admission_settings'] = $this->vars['subject_national_exam_settings'] = true;
		$majors = $this->m_majors->dropdown();
		if ($this->session->userdata('school_level') >= 3) {
			$majors  = [0 => 'Unset'] + $majors;
		}
		$this->vars['majors_dropdown'] = json_encode($majors);
		$this->vars['admission_types_dropdown'] = json_encode($this->m_admission_types->dropdown());
		$this->vars['academic_years_dropdown'] = json_encode($this->m_academic_years->dropdown());
		$this->vars['content'] = 'admission/subject_national_exam_settings';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Pagination
	 * @return Object
	 */
	public function pagination() {
		if ($this->input->is_ajax_request()) {
			$page_number = (int) $this->input->post('page_number', true);
			$limit = (int) $this->input->post('per_page', true);
			$keyword = trim($this->input->post('keyword', true));
			$offset = ($page_number * $limit);
			$query = $this->m_admission_subject_settings->get_where($keyword, $limit, $offset, 'national_exam');
			$total_rows = $this->m_admission_subject_settings->total_rows($keyword, 'national_exam');
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
			'academic_year_id' => $this->input->post('academic_year_id', true),
			'admission_type_id' => $this->input->post('admission_type_id', true),
			'major_id' => $this->input->post('major_id', true) ? $this->input->post('major_id', true) : 0,
			'subject_type' => 'national_exam'
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('academic_year_id', 'Tahun Pelajaran', 'trim|required|is_natural_no_zero');
		$val->set_rules('admission_type_id', 'Jalur Pendaftaran', 'trim|required|is_natural_no_zero');
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$val->set_rules('major_id', $this->session->userdata('_major'), 'trim|required');
		}
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('is_natural_no_zero', '{field} harus diisi dengan angka dan tidak boleh Nol');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}