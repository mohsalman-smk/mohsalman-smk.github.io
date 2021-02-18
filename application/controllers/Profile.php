<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Profile extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$id = (int) $this->session->userdata('id');
		$this->vars['title'] = 'Ubah Profil';
		$this->vars['user_profile'] = true;
		$this->vars['query'] = $this->model->RowObject('users', 'id', $id);
		$this->vars['content'] = 'users/profile';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * save
	 * @access  public
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->session->userdata('id');
			$response = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				if ($this->validation()) {
					$fill_data = $this->fill_data();
					$fill_data['updated_by'] = $id;
					$response['type'] = $this->model->update($id, 'users', $fill_data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated'; 
				} else {
					$response['action'] = 'validation_errors';
					$response['type'] = 'error';
					$response['message'] = validation_errors();
				}
			} else {
				$response['type'] = 'error';
				$response['message'] = 'not_updated';
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
			'user_full_name' => $this->input->post('user_full_name', true),
			'user_email' => $this->input->post('user_email', true),
			'user_url' => $this->input->post('user_url', true),
			'user_biography' => $this->input->post('user_biography', true)
		];
	}

	/**
	 * Validations Form
	 * @access  public
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('user_full_name', 'Full Name', 'trim|required');
		$val->set_rules('user_email', 'Email', 'trim|required|valid_email');
		$val->set_rules('user_url', 'URL', 'trim|valid_url');
		$val->set_rules('user_biography', 'Biography', 'trim');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}