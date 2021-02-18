<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Academic_years extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_academic_years');
		$this->pk = M_academic_years::$pk;
		$this->table = M_academic_years::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = $this->session->userdata('_academic_year');
		$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['academic_years'] = true;
		$this->vars['content'] = 'academic_years/read';
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
			$query = $this->m_academic_years->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_academic_years->total_rows($keyword);
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
	 * @return Object
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
					if ($response['type'] == 'success' && $fill_data['current_semester'] == 'true') {
						$this->m_academic_years->set_not_active($id, 'current_semester');
					}
					if ($response['type'] == 'success' && $fill_data['admission_semester'] == 'true') {
						$this->m_academic_years->set_not_active($id, 'admission_semester');
					}
				} else {
					if ($fill_data['current_semester'] == 'true') {
						$this->m_academic_years->set_not_active(0, 'current_semester');
					}
					if ($fill_data['admission_semester'] == 'true') {
						$this->m_academic_years->set_not_active(0, 'admission_semester');
					}
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
			'academic_year' => $this->input->post('academic_year', true),
			'semester' => $this->input->post('semester', true),
			'current_semester' => $this->input->post('current_semester', true),
			'admission_semester' => $this->input->post('admission_semester', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('academic_year', 'Academic Years', 'trim|required|min_length[9]|max_length[9]|callback_format_check');
		$val->set_rules('semester', 'Semester', 'trim|required');
		$val->set_rules('current_semester', 'Semester Aktif', 'trim|required|in_list[true,false]');
		$val->set_rules('admission_semester', 'Semester PPDB/PMB', 'trim|required|in_list[true,false]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Format Check
	 * @param 	String
	 * @return 	Boolean
	 */
	public function format_check($val) {
		$year = explode('-', substr($val, 0, 9));
		if (strpos($val, '-') === FALSE) {
			$this->form_validation->set_message('format_check', 'Tahun awal dan tahun akhir harus dipisah dengan tanda strip (-)');
			return FALSE;
		} else if (strlen($val) !== 9) {
			$this->form_validation->set_message('format_check', 'Format tahun pelajaran harus 9 digit. Contoh : 2017-2018');
			return FALSE;
		} else if ((int) $year[ 0 ] === 0 || (int) $year[ 1 ] === 0) {
			$this->form_validation->set_message('format_check', 'Format tahun pelajaran harus diisi angka. Contoh : 2017-2018');
			return FALSE;	
		} else if (($year[1] - $year[0]) != 1) {
			$this->form_validation->set_message('format_check', 'Tahun awal dan tahun akhir harus selisih satu !');
			return FALSE;
		} else if (strlen($year[0]) != 4 && strlen([1] != 4)) {
			$this->form_validation->set_message('format_check', 'Tahun harus 4 karakter !');
			return FALSE;
		}
		return TRUE;
	}
}