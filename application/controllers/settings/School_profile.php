<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class School_profile extends Admin_Controller {

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
		$this->vars['title'] = 'PENGATURAN PROFIL ' . ($this->session->userdata('school_level') >= 5 ? 'PERGURUAN TINGGI' : 'SEKOLAH');
		$this->vars['settings'] = $this->vars['school_profile_settings'] = true;
		$this->vars['content'] = 'settings/school_profile';
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
			$query = $this->m_settings->get_where($keyword, $limit, $offset, 'school_profile');
			$total_rows = $this->m_settings->total_rows($keyword, 'school_profile');
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
	 * @return 	Object
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
				$config['upload_path'] = './media_library/images/';
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
						@chmod(FCPATH.'media_library/images/'.$file['file_name'], 0777);
						// chmood old file
						@chmod(FCPATH.'media_library/images/'.$file_name, 0777);
						// unlink old file
						@unlink(FCPATH.'media_library/images/'.$file_name);
						// resize new image
						if ($query->setting_variable != 'headmaster_photo') {
							$this->image_resize('./media_library/images', $file['file_name'], $query->setting_variable);
						}
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
	  * Resize Images
	  */
	 private function image_resize($source, $file_name, $setting_variable = 'headmaster_photo') {
	 	$settings = [
	 		'headmaster_photo_height' => NULL !== $this->session->userdata('headmaster_photo_height') ? $this->session->userdata('headmaster_photo_height') : 344,
	 		'headmaster_photo_width' => NULL !== $this->session->userdata('headmaster_photo_width') ? $this->session->userdata('headmaster_photo_width') : 252,
	 		'logo_height' => NULL !== $this->session->userdata('logo_height') ? $this->session->userdata('logo_height') : 120,
	 		'logo_width' => NULL !== $this->session->userdata('logo_width') ? $this->session->userdata('logo_width') : 120,
	 		'logo_primary_height' => 41,
	 		'logo_primary_width' => 168,
	 		'logo_textprimary_height' => 41,
	 		'logo_textprimary_width' => 168,
	 		'logo_footer_height' => 41,
	 		'logo_footer_width' => 168
	 	];
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source .'/'.$file_name;
		$config['maintain_ratio'] = false;
		$config['width'] = (int) $settings[$setting_variable.'_width'];
		$config['height'] = (int) $settings[$setting_variable.'_height'];
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
	}
}
