<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Change_password extends Admin_Controller {

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
		$this->vars['title'] = 'Ubah Kata Sandi';
		$this->vars['change_password'] = true;
		$this->vars['content'] = 'users/change_password';
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
					$query = $this->model->RowObject('users', 'id', $id);
					if (password_verify($this->input->post('current_password', true), $query->user_password)) {
						$fill_data = $this->fill_data();
						$fill_data['updated_by'] = $id;
						$response['type'] = $this->model->update($id, 'users', $fill_data) ? 'success' : 'error';
						$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated'; 
					} else {
						$response['type'] = 'error';
						$response['message'] = 'not_updated';
					}
				} else {
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
			'user_password' => password_hash($this->input->post('new_password', true), PASSWORD_BCRYPT)
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
		$val->set_rules('current_password', 'Kata Sandi Saat Ini', 'trim|required');
		$val->set_rules('new_password', 'Kata Sandi Baru', 'trim|required');
		$val->set_rules('retype_new_password', 'Ulangi Kata Sandi Baru', 'trim|required|matches[new_password]');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('matches', 'Kata sandi tidak sama');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}