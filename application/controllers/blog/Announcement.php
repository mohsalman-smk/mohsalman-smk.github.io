<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Announcement extends Blog_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_announcement', 
			'm_post_categories'
		]);
		$this->pk = M_announcement::$pk;
		$this->table = M_announcement::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'PENGUMUMAN';
		$this->vars['blog'] = $this->vars['announcement'] = $this->vars['all_announcement'] = true;
		$this->vars['content'] = 'announcement/read';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Add new
	 * @return Void
	 */
	public function create() {
		$this->load->helper('form');
		$this->vars['query'] = FALSE;
		$id = (int) $this->uri->segment(4);
		if ($id !== 0 && ctype_digit((string) $id)) {
			$this->vars['query'] = $this->model->RowObject($this->table, $this->pk, $id);
			$post_image = 'media_library/announcement/medium/'.$this->vars['query']->post_image;
			$this->vars['post_image'] = file_exists(FCPATH . $post_image) ? base_url($post_image) : '';
		}
		$this->vars['option_categories'] = $this->m_post_categories->get_post_categories();
		$this->vars['title'] = $id && ctype_digit((string) $id) ? 'Edit Tulisan' : 'Tambah Tulisan';
		$this->vars['blog'] = $this->vars['announcement'] = $this->vars['announcement_create'] = true;
		$this->vars['action'] = site_url('blog/announcement/save/'.$id);
		$this->vars['content'] = 'announcement/create';
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
			$query = $this->m_announcement->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_announcement->total_rows($keyword);
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
			$id = (int) $this->uri->segment(4);
			$response = [];
			if ($this->validation()) {
				$fill_data = $this->fill_data();
				$error_msg = [];
				if (!empty($_FILES['post_image'])) {
					$upload = $this->upload_image($id);
					if ($upload['type'] == 'success') {
						$fill_data['post_image'] = $upload['file_name'];
					} else {
						$error_msg = $upload['message'];
					}
				}
				if (count($error_msg) > 0) {
					$response['action'] = 'error';
					$response['type'] = 'error';
					$response['message'] = $error_msg;
				} else {
					if ($id == 0) {
						$fill_data['created_at'] = NULL;
						$fill_data['created_by'] = $this->session->userdata('id');
						$response['action'] = 'save';
						$response['type'] = $this->model->insert($this->table, $fill_data) ? 'success' : 'error';
						$response['message'] = $response['type'] == 'success' ? 'created' : 'not_created';
					} else {
						$fill_data['updated_at'] = date('Y-m-d H:i:s');
						$fill_data['updated_by'] = $this->session->userdata('id');
						unset($fill_data['post_author']);
						$response['action'] = 'update';		
						$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
						$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated'; 
					}
					// Create tags from announcement
					$this->load->model('m_tags');
					$this->m_tags->create($fill_data['post_tags']);
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
	 * Save published date
	 * @return Object 
	 */
	public function save_published_date() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			$fill_data = [
				'created_at' => $this->input->post('created_at', true)
			];
			if ($id !== 0 && ctype_digit((string) $id)) {
				$fill_data['updated_at'] = date('Y-m-d H:i:s');
					$fill_data['updated_by'] = $this->session->userdata('id');
				$response['action'] = 'update';		
				$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
				$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated'; 
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
			'post_title' => $this->input->post('post_title', true),
			'post_content' => $this->input->post('post_content'),
			'post_author' => $this->session->userdata('id'),
			'post_categories' => $this->input->post('post_categories', true),
			'post_type' => 'post',
			'post_status' => $this->input->post('post_status', true),
			'post_visibility' => $this->input->post('post_visibility', true),
			'post_comment_status' => $this->input->post('post_comment_status', true),
			'post_slug' => slugify($this->input->post('post_title', true)),
			'post_tags' => strtolower($this->input->post('post_tags', true))
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('post_title', 'Title', 'trim|required');
		$val->set_rules('post_content', 'Content', 'trim|required');
		$val->set_rules('post_status', 'Status', 'trim|required|in_list[publish,draft]');
		$val->set_rules('post_visibility', 'Visibilitas', 'trim|required|in_list[public,private]');
		$val->set_rules('post_comment_status', 'Komentar', 'trim|required|in_list[open,close]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Post Image Upload Handler
	 * @param 	Int
	 * @return 	Array
	 */
	protected function upload_image($id) {
		$response = [];
		$config['upload_path'] = './media_library/images/';
		$config['allowed_types'] = 'jpg|png|jpeg|gif';
		$config['max_size'] = 0;
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('post_image')) {
			$response['type'] = 'error';
			$response['message'] = $this->upload->display_errors();
		} else {
			$file = $this->upload->data();
			// chmood new file
			@chmod(FCPATH.'media_library/images/'.$file['file_name'], 0777);
			// resize new image
			$this->resize_image(FCPATH.'media_library/images', $file['file_name']);
			$response['type'] = 'success';
			$response['file_name'] = $file['file_name'];
			if ($id > 0) {
				$query = $this->model->RowObject($this->table, $this->pk, $id);
				// chmood old file
				@chmod(FCPATH.'media_library/announcement/thumbnail/'.$query->post_image, 0777);
				@chmod(FCPATH.'media_library/announcement/medium/'.$query->post_image, 0777);
				@chmod(FCPATH.'media_library/announcement/large/'.$query->post_image, 0777);
				// unlink old file
				@unlink(FCPATH.'media_library/announcement/thumbnail/'.$query->post_image);
				@unlink(FCPATH.'media_library/announcement/medium/'.$query->post_image);
				@unlink(FCPATH.'media_library/announcement/large/'.$query->post_image);
			}
		}
		return $response;
	}

	/**
	  * Resize Images
	  * @param 		String
	  * @param 		String
	  * @return 	Void
	  */
	 private function resize_image($source, $file_name) {
		$this->load->library('image_lib');
		// Thumbnail Image
		$thumb['image_library'] = 'gd2';
		$thumb['source_image'] = $source .'/'. $file_name;
		$thumb['new_image'] = './media_library/announcement/thumbnail/'. $file_name;
		$thumb['maintain_ratio'] = false;
		$thumb['width'] = (int) $this->session->userdata('post_image_thumbnail_width');
		$thumb['height'] = (int) $this->session->userdata('post_image_thumbnail_height');
		$this->image_lib->initialize($thumb);
		$this->image_lib->resize();
		$this->image_lib->clear();
		// Medium Image
		$medium['image_library'] = 'gd2';
		$medium['source_image'] = $source .'/'. $file_name;
		$medium['new_image'] = './media_library/announcement/medium/'. $file_name;
		$medium['maintain_ratio'] = false;
		$medium['width'] = (int) $this->session->userdata('post_image_medium_width');
		$medium['height'] = (int) $this->session->userdata('post_image_medium_height');
		$this->image_lib->initialize($medium);
		$this->image_lib->resize();
		$this->image_lib->clear();
		// Large Image
		$large['image_library'] = 'gd2';
		$large['source_image'] = $source .'/'. $file_name;
		$large['new_image'] = './media_library/announcement/large/'. $file_name;
		$large['maintain_ratio'] = false;
		$large['width'] = (int) $this->session->userdata('post_image_large_width');
		$large['height'] = (int) $this->session->userdata('post_image_large_height');
		$this->image_lib->initialize($large);
		$this->image_lib->resize();
		$this->image_lib->clear();
		// Remove Original File
		@unlink($source .'/'. $file_name);
	}
}