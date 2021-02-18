<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Achievements extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_achievements');
		$this->pk = M_achievements::$pk;
		$this->table = M_achievements::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'PRESTASI';
		$this->vars['achievements'] = true;
		$this->vars['content'] = 'achievements/read';
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
			$query = $this->m_achievements->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_achievements->total_rows($keyword);
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
	 * @return Object 
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			if ($this->validation()) {
				$fill_data = $this->fill_data();
				if ($id !== 0 && ctype_digit((string) $id)) {
					$query = $this->model->RowObject($this->table, $this->pk, $id);
					if ($query->student_id == $this->session->userdata('user_profile_id')) {
						$fill_data['updated_at'] = date('Y-m-d H:i:s');
					$fill_data['updated_by'] = $this->session->userdata('id');
						$response['action'] = 'update';		
						$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
						$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated'; 
					} else {
						$response['type'] = 'error';
						$response['message'] = 'forbidden';
					}
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
			'student_id' => $this->session->userdata('user_profile_id'),
			'achievement_description' => $this->input->post('achievement_description', true),
			'achievement_type' => $this->input->post('achievement_type', true),
			'achievement_level' => $this->input->post('achievement_level', true),
			'achievement_year' => $this->input->post('achievement_year', true),
			'achievement_organizer' => $this->input->post('achievement_organizer', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('achievement_description', 'Nama Prestasi', 'trim|required');
		$val->set_rules('achievement_type', 'Jenis Prestasi', 'trim|required|is_natural_no_zero');
		$val->set_rules('achievement_level', 'Tingkat Prestasi', 'trim|required|is_natural_no_zero');
		$val->set_rules('achievement_year', 'Tahun', 'trim|is_natural_no_zero|required|min_length[4]|max_length[4]');
		$val->set_rules('achievement_organizer', 'Penyelenggara', 'trim');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}