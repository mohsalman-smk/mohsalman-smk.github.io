<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Student_status extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_student_status');
		$this->pk = M_student_status::$pk;
		$this->table = M_student_status::$table;
	}

	/**
	* Index
	*
	* @return	void
	*/
	public function index() {
		$this->vars['title'] = 'STATUS ' . strtoupper($this->session->userdata('_student'));
		$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['student_status'] = true;
		$this->vars['content'] = 'student_status/read';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	* Pagination
	*
	* @return	Json
	*/
	public function pagination() {
		if ($this->input->is_ajax_request()) {
			$page_number = (int) $this->input->post('page_number', true);
			$limit = (int) $this->input->post('per_page', true);
			$keyword = trim(strtolower($this->input->post('keyword', true)));
			$offset = ($page_number * $limit);
			$query = $this->m_student_status->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_student_status->total_rows($keyword);
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
	* @return 	Void
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
	* @return 	Object
	*/
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			if ($this->validation()) {
				$fill_data = $this->fill_data();
				if ($id !== 0 && ctype_digit((string) $id)) {
					$query = $this->model->RowObject($this->table, $this->pk, $id);
					if (!in_array(strtolower($query->option_name), ['lulus', 'aktif'])) {
						$fill_data['updated_at'] = date('Y-m-d H:i:s');
						$fill_data['updated_at'] = date('Y-m-d H:i:s');
						$fill_data['updated_by'] = $this->session->userdata('id');
						$response['action'] = 'update';
						$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
						$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated';
					} else {
						$response['type'] = 'info';
						$response['message'] = 'Status peserta didik Lulus dan Aktif tidak dapat diubah !';
					}
				} else {
					if (!in_array(strtolower($fill_data['option_name']), ['lulus', 'aktif'])) {
						$fill_data['created_at'] = NULL;
						$fill_data['created_by'] = $this->session->userdata('id');
						$response['action'] = 'save';
						$response['type'] = $this->model->insert($this->table, $fill_data) ? 'success' : 'error';
						$response['message'] = $response['type'] == 'success' ? 'created' : 'not_created';
					} else {
						$response['type'] = 'info';
						$response['message'] = 'Status peserta didik '. strtoupper($fill_data['option_name']) .' sudah ada !';
					}
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
			'option_group' => 'student_status',
			'option_name' => $this->input->post('option_name', true)
		];
	}

	/**
	* Validations Form
	* @return Bool
	*/
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('option_name', 'Status ' . $this->session->userdata('_student'), 'trim|required');
		$val->set_message('required', '{field} harus diisi');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
