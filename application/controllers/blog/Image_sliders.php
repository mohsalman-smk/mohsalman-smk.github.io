<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Image_sliders extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_image_sliders');
		$this->pk = M_image_sliders::$pk;
		$this->table = M_image_sliders::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'GAMBAR SLIDE';
		$this->vars['blog'] = $this->vars['image_sliders'] = true;
		$this->vars['content'] = 'image_sliders/read';
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
			$query = $this->m_image_sliders->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_image_sliders->total_rows($keyword);
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
			'title' => $this->input->post('title', true),
			'caption' => $this->input->post('caption', true),
			'url' => $this->input->post('url', true),
			'bottom' => $this->input->post('bottom', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('title', 'Judul', 'trim');
		$val->set_rules('caption', 'Description', 'trim');
		$val->set_rules('url', 'URL', 'trim');
		$val->set_rules('bottom', 'Tombol', 'trim');
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
				$file_name = $query->image;
				$config = [];
				$config['upload_path'] = './media_library/image_sliders/';
				$config['allowed_types'] = 'jpg|png|jpeg';
				$config['max_size'] = 0;
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('file')) {
					$response['action'] = 'validation_errors';
					$response['type'] = 'error';
					$response['message'] = $this->upload->display_errors();
				} else {
					$file = $this->upload->data();
					$update = $this->model->update($id, $this->table, ['image' => $file['file_name']]);
					if ($update) {
						// chmood new file
						@chmod(FCPATH.'media_library/image_sliders/'.$file['file_name'], 0777);
						// chmood old file
						@chmod(FCPATH.'media_library/image_sliders/'.$file_name, 0777);
						// unlink old file
						@unlink(FCPATH.'media_library/image_sliders/'.$file_name);
						// resize new image
						$this->image_resize(FCPATH.'media_library/image_sliders', $file['file_name']);
					}
					$response['action'] = 'upload';
					$response['type'] = 'success';
					$response['message'] = 'uploaded';
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
	  * Image Resize 
	  * @param String
	  * @param String
	  * @return Void
	  */
	 private function image_resize($source, $file_name) {
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source .'/'.$file_name;
		$config['maintain_ratio'] = false;
		$config['width'] = (int) $this->session->userdata('image_slider_width');
		$config['height'] = (int) $this->session->userdata('image_slider_height');
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
	}
}