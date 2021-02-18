<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Posts extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_posts', 
			'm_post_categories'
		]);
		$this->pk = M_posts::$pk;
		$this->table = M_posts::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'TULISAN';
		$this->vars['posts'] = TRUE;
		$this->vars['content'] = 'posts/member_read';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Add new
	 * @return Void
	 */
	public function create() {
		$this->load->helper('form');
		$id = $this->uri->segment(3);
		if ($id !== 0 && ctype_digit((string) $id)) {
			$this->vars['query'] = $this->model->RowObject($this->table, $this->pk, $id);
			$post_image = 'media_library/posts/medium/'.$this->vars['query']->post_image;
			$this->vars['post_image'] = file_exists(FCPATH . $post_image) ? base_url($post_image) : '';
			// Check Authorization
			if ((int) $this->vars['query']->post_author !== (int) $this->session->userdata('id')) {
				redirect('dashboard', 'refresh');
			}
			// If has published, forbidden to edit
			if ($this->vars['query']->post_status === 'publish') {
				redirect('dashboard', 'refresh');
			}
		} else {
			$this->vars['query'] = FALSE;
		}
		$this->vars['option_categories'] = $this->m_post_categories->get_post_categories();
		$this->vars['title'] = $id && is_int(intval($id)) ? 'Edit Tulisan' : 'Tambah Tulisan';
		$this->vars['posts'] = TRUE;
		$this->vars['action'] = site_url('posts/save/'.$id);
		$this->vars['content'] = 'posts/member_create';
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
			$query = $this->m_posts->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_posts->total_rows($keyword);
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
	 * @return Object 
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->uri->segment(3);
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
						$response['action'] = 'update';		
						$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
						$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated'; 
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
			'post_title' => $this->input->post('post_title', true),
			'post_content' => $this->input->post('post_content'),
			'post_author' => $this->session->userdata('id'),
			'post_categories' => $this->input->post('post_categories', true),
			'post_type' => 'post',
			'post_slug' => slugify($this->input->post('post_title', true))
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
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}