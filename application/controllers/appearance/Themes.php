<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Themes extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_themes');
		$this->pk = M_themes::$pk;
		$this->table = M_themes::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Tema';
		$this->vars['appearance'] = $this->vars['themes'] = true;
		$this->vars['content'] = 'themes/read';
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
			$query = $this->m_themes->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_themes->total_rows($keyword);
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
					if ($response['type'] == 'success' && $fill_data['is_active'] == 'true') {
						$this->m_themes->set_not_active($id);
					}
				} else {
					if ($fill_data['is_active'] == 'true') {
						$this->m_themes->set_not_active();
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
			'theme_name' => $this->input->post('theme_name', true),
			'theme_folder' => trim(strtolower($this->input->post('theme_folder', true))),
			'theme_author' => $this->input->post('theme_author', true),
			'is_active' => $this->input->post('is_active', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('theme_name', 'Theme Name', 'trim|required');
		$val->set_rules('theme_folder', 'Theme Folder', 'trim|required');
		$val->set_rules('theme_author', 'Author', 'trim|required');
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
				$config['upload_path']   = './views/themes/';
				$config['allowed_types'] = 'zip';
				$config['max_size']      = 0;
				$config['encrypt_name']  = false;
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('file')) {
					$response['action'] = 'validation_errors';
					$response['type'] = 'error';
					$response['message'] = $this->upload->display_errors();
				} else {
					$file = $this->upload->data();
					if ($query->theme_folder == $file['raw_name']) {
						$zip = new ZipArchive;
						if ($zip->open(VIEWPATH . 'themes/' . $file['file_name'])) {
							$zip->extractTo(VIEWPATH . 'themes/');
							$zip->close();
							// chmod Theme Folder
							$this->chmod_themes('./views/themes/'.$file['raw_name']);
							$response['action'] = 'upload';
							$response['type'] = 'success';
							$response['message'] = 'extracted';
						} else {
							$response['action'] = 'upload';
							$response['type'] = 'error';
							$response['message'] = 'not_extracted';
						}
					} else {
						$response['action'] = 'upload';
						$response['type'] = 'warning';
						$response['message'] = 'Nama file yang diupload tidak sama dengan "'. $query->theme_folder.'"';
					}
					// Delete ZIP File
					@chmod('./views/themes/' . $file['file_name'], 0777);
					@unlink('./views/themes/' . $file['file_name']);
				}
	    	} else {
	    		$response['action'] = 'error';
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
	 * chmod Theme Folder
	 * @param String
	 * @return Void
	 */
	private function chmod_themes($path) {
		@chmod($path, 0777);
		$dir = new DirectoryIterator($path);
		foreach ($dir as $item) {
			@chmod($item->getPathname(), 0777);
			if ($item->isDir() && !$item->isDot()) {
				$this->chmod_themes($item->getPathname());
			}
		}
	}
}