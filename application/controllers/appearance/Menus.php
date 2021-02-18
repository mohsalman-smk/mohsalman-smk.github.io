<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Menus extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_menus');
		$this->pk = M_menus::$pk;
		$this->table = M_menus::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Menu';
		$this->vars['appearance'] = $this->vars['menus'] = true;
		$this->vars['content'] = 'menus/read';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get All Menus
	 * @return Object
	 */
	public function get_menus() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_menus->get_all();
			$data = [];
			foreach($query->result() as $row) {
				$data[] = $row;
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Delete Menus
	 * @param 	Int
	 * @return 	Object
	 */
	public function delete() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				$check = $this->m_menus->is_child_exist($id);
				if (!$check) {
					$this->model->delete_permanently($this->pk, $id, $this->table);
					$response['type'] = $this->model->delete_permanently($this->pk, $id, $this->table) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'deleted' : 'not_deleted';
				} else {
					$response['type'] = 'warning';
					$response['message'] = 'The parent menu can not deleted !';
				}
			} else {
				$response['type'] = 'error';
				$response['message'] = 'Not initialize id OR id not a number';
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Delete All Menus
	 * @return 	Object
	 */
	public function delete_all() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$response['type'] = $this->model->truncate($this->table) ? 'success' : 'error';
			$response['message'] = $response['type'] == 'success' ? 'deleted' : 'not_deleted';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Get Nested List Menus
	 * @return Object
	 */
	public function nested_list() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_menus->parent_menus();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

   /**
    * Save menu position
    * @return Object
    */
   public function save_position() {
	   	if ($this->input->is_ajax_request()) {
			if (NULL !== $this->input->post('menus')) {
				$menus = json_decode($this->input->post('menus'), true);
				$this->m_menus->update_position(0, $menus);
			}
			$response = [];
			$response['growl'] = 'success';
			$response['message'] = 'Your data have been saved.';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}

   }

	/**
    * Get All Pages
    * @return Object
    */
	public function get_pages() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('m_pages');
			$query = $this->m_pages->get_pages();
			$data = [];
			foreach($query->result() as $row) {
				$data[] = [
					'id' => $row->id,
					'post_title' => $row->post_title
				];
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
    * Get All Post Categories
    * @return Object
    */
	public function get_post_categories() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('m_post_categories');
			$query = $this->m_post_categories->get_post_categories();
			$data = [];
			foreach($query->result() as $row) {
				$data[] = [
					'id' => $row->id,
					'category_name' => $row->category_name
				];
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
    * Get All File Categories
    * @return Object
    */
	public function get_file_categories() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('m_file_categories');
			$query = $this->m_file_categories->get_all();
			$data = [];
			foreach($query->result() as $row) {
				$data[] = [
					'id' => $row->id,
					'category_name' => $row->category_name
				];
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($data, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
    * Save Custom Links
    * @return Object
    */
	public function save_links() {
		if ($this->input->is_ajax_request()) {
			$fill_data = [
				'menu_url' => ($this->input->post('menu_url', true) && $this->input->post('menu_url', true) != '#') ? prep_url($this->input->post('menu_url', true)) : '#',
				'menu_title' => $this->input->post('menu_title', true),
				'menu_target' => $this->input->post('menu_target', true),
				'menu_type' => 'link'
			];
			$response = [];
			$response['action'] = 'save';
			$response['type'] = $this->model->insert($this->table, $fill_data) ? 'success' : 'error';
			$response['message'] = $response['type'] == 'success' ? 'created' : 'not_created';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
    * Save Menus From Pages
    * @return Object
    */
	public function save_pages() {
		if ($this->input->is_ajax_request()) {
			$ids = explode(',', $this->input->post('ids'));
			foreach($ids as $id) {
				$query = $this->model->RowObject('posts', 'id', $id);
				$fill_data = [
					'menu_title' => $query->post_title,
					'menu_url' => 'read/' . $id . '/'.$query->post_slug,
					'menu_type' => 'page',
					'menu_target' => '_self'
				];
				$this->model->insert('menus', $fill_data);
			}
			$response = [];
			$response['action'] = 'save';
			$response['type'] = 'success';
			$response['message'] = 'created';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
    * Save Menus From Posts Categories
    * @return Object
    */
	public function save_post_categories() {
		if ($this->input->is_ajax_request()) {
			$ids = explode(',', $this->input->post('ids'));
			foreach($ids as $id) {
				$query = $this->model->RowObject('categories', 'id', $id);
				$fill_data = [
					'menu_title' => $query->category_name,
					'menu_url' => 'category/'.$query->category_slug,
					'menu_type' => 'post_category',
					'menu_target' => '_self'
				];
				$this->model->insert('menus', $fill_data);
			}
			$response = [];
			$response['action'] = 'save';
			$response['type'] = 'success';
			$response['message'] = 'created';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
    * Save Menus From File Categories
    * @return Object
    */
	public function save_file_categories() {
		if ($this->input->is_ajax_request()) {
			$ids = explode(',', $this->input->post('ids'));
			foreach($ids as $id) {
				$query = $this->model->RowObject('categories', 'id', $id);
				$fill_data = [
					'menu_title' => $query->category_name,
					'menu_url' => 'download/'.$query->category_slug,
					'menu_type' => 'file_category',
					'menu_target' => '_self'
				];
				$this->model->insert('menus', $fill_data);
			}
			$response = [];
			$response['action'] = 'save';
			$response['type'] = 'success';
			$response['message'] = 'created';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
    * Save Menus From List Modules
    * @return Object
    */
	public function save_modules() {
		if ($this->input->is_ajax_request()) {
			$modules = explode(',', $this->input->post('modules'));
			foreach($modules as $module) {
				$fill_data = [
					'menu_title' => modules($module),
					'menu_url' => $module,
					'menu_type' => 'module',
					'menu_target' => '_self'
				];
				$this->model->insert('menus', $fill_data);
			}
			$response = [];
			$response['action'] = 'save';
			$response['type'] = 'success';
			$response['message'] = 'created';
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
		$data = [];
		$data['menu_title'] = $this->input->post('menu_title', true);
		$data['menu_url'] = $this->input->post('menu_url', true);
		$data['menu_target'] = $this->input->post('menu_target', true);
		$is_deleted = $this->input->post('is_deleted');
		$data['is_deleted'] = $is_deleted;
		if ($is_deleted == 'true') {
			$data['deleted_by'] = $this->session->userdata('id');
			$data['deleted_at'] = date('Y-m-d H:i:s');
		} else {
			$data['restored_by'] = $this->session->userdata('id');
			$data['restored_at'] = date('Y-m-d H:i:s');
		}
		return $data;
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('menu_title', 'Title', 'trim|required');
		$val->set_rules('menu_url', 'URL', 'trim|required');
		$val->set_rules('menu_target', 'Target', 'trim|required');
		$val->set_rules('is_deleted', 'Aktif ?', 'trim|required|in_list[true,false]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
