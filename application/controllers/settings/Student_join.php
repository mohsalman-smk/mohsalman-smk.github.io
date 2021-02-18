<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Student_join extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_settings');
		$this->pk = M_settings::$pk;
		$this->table = M_settings::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Album Siswa';
		$this->vars['settings'] = $this->vars['student_join_settings'] = true;
		$this->vars['timezone_list'] = json_encode(timezone_list());
		$this->vars['content'] = 'settings/student_join';
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
			$keyword = trim(strtolower($this->input->post('keyword', true)));
			$offset = ($page_number * $limit);
			$query = $this->m_settings->get_where($keyword, $limit, $offset, 'student_join');
			$total_rows = $this->m_settings->total_rows($keyword, 'student_join');
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
					$fill_data['updated_at'] = date('Y-m-d H:i:s');
					$fill_data['updated_by'] = $this->session->userdata('id');
					$response['action'] = 'update';		
					$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated';
				} else {
					$response['action'] = 'save';
					$response['type'] = 'error';
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
			'setting_value' => $this->input->post('setting_value', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('setting_value', 'Value', 'trim|required');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Upload
	 * @return Void
	 */
	public function upload() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				$query = $this->model->RowObject($this->table, $this->pk, $id);
				$file_name = $query->setting_value;
				$config['upload_path'] = './media_library/student_join/';
				$config['allowed_types'] = 'jpg|png|jpeg|gif';
				$config['max_size'] = 0;
				$config['encrypt_name'] = true;
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('file')) {
					$response['action'] = 'validation_errors';
					$response['type'] = 'error';
					$response['message'] = $this->upload->display_errors();
				} else {
					$file = $this->upload->data();
					$update = $this->model->update($id, $this->table, ['setting_value' => $file['file_name']]);
					if ($update) {
						// chmood new file
						@chmod(FCPATH.'media_library/student_join/'.$file['file_name'], 0777);
						// chmood old file
						@chmod(FCPATH.'media_library/student_join/'.$file_name, 0777);
						// unlink old file
						@unlink(FCPATH.'media_library/student_join/'.$file_name);
						// resize new image
						$this->image_resize(FCPATH.'media_library/student_join', $file['file_name'], $query->setting_variable);
					}
					$response['action'] = 'upload';
					$response['type'] = 'success';
					$response['message'] = 'uploaded';
				}
			} else {
				$response['action'] = 'upload';
				$response['type'] = 'error';
				$response['message'] = 'Not initialize ID or ID is not numeric !';
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	  * Resize student_join
	  */
	 private function image_resize($source, $file_name, $setting_variable = 'favicon') {
	 	$settings = [
	 		'student_join_1_width' => 200,
	 		'student_join_1_height' => 200,
	 		'student_join_2_width' => 200,
	 		'student_join_2_height' => 200,
	 		'student_join_3_width' => 200,
	 		'student_join_3_height' => 200,
	 		'student_join_4_width' => 200,
	 		'student_join_4_height' => 200,
	 		'student_join_5_width' => 200,
	 		'student_join_5_height' => 200,
	 		'student_join_6_width' => 200,
	 		'student_join_6_height' => 200,
	 		'student_join_7_width' => 200,
	 		'student_join_7_height' => 200,
	 		'student_join_8_width' => 200,
	 		'student_join_8_height' => 200,
	 		'student_join_9_width' => 200,
	 		'student_join_9_height' => 200,
	 		'student_join_10_width' => 200,
	 		'student_join_10_height' => 200,
	 		'student_join_11_width' => 200,
	 		'student_join_11_height' => 200,
	 		'student_join_12_width' => 200,
	 		'student_join_12_height' => 200,
	 		'student_join_13_width' => 200,
	 		'student_join_13_height' => 200,
	 		'student_join_14_width' => 200,
	 		'student_join_14_height' => 200,
	 		'student_join_15_width' => 200,
	 		'student_join_15_height' => 200,
	 		'student_join_16_width' => 200,
	 		'student_join_16_height' => 200,
	 		'student_join_17_width' => 200,
	 		'student_join_17_height' => 200,
	 		'student_join_18_width' => 200,
	 		'student_join_18_height' => 200,
	 		'student_join_19_width' => 200,
	 		'student_join_19_height' => 200,
	 		'student_join_20_width' => 200,
	 		'student_join_20_height' => 200,
	 		'student_join_21_width' => 200,
	 		'student_join_21_height' => 200,
	 		'student_join_22_width' => 200,
	 		'student_join_22_height' => 200,
	 		'student_join_23_width' => 200,
	 		'student_join_23_height' => 200,
	 		'student_join_24_width' => 200,
	 		'student_join_24_height' => 200,
	 		'student_join_25_width' => 200,
	 		'student_join_25_height' => 200,
	 		'student_join_26_width' => 200,
	 		'student_join_26_height' => 200,
	 		'student_join_27_width' => 200,
	 		'student_join_27_height' => 200,
	 		'student_join_28_width' => 200,
	 		'student_join_28_height' => 200,
	 		'student_join_29_width' => 200,
	 		'student_join_29_height' => 200,
	 		'student_join_30_width' => 200,
	 		'student_join_30_height' => 200,
	 		'student_join_31_width' => 200,
	 		'student_join_31_height' => 200,
	 		'student_join_32_width' => 200,
	 		'student_join_32_height' => 200
	 	];
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source .'/'.$file_name;
		$config['maintain_ratio'] = false;
		$config['width'] = (int) $settings[$setting_variable . '_width'];
		$config['height'] = (int) $settings[$setting_variable . '_height'];
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
	}
}