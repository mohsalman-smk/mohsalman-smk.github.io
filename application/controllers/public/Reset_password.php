<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Reset_password extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_users');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$activation_key = $this->uri->segment(2);
		if ($activation_key) {
			$this->vars['page_title'] = 'Reset Password';
			$this->vars['content'] = 'users/reset_password';
			$this->load->view('users/index', $this->vars);
		} else {
			show_404();
		}
	}

	/**
	 * Process
	 */
	public function process() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			if ($this->input->post('csrf_token') && $this->token->is_valid_token($this->input->post('csrf_token'))) {
				if ($this->validation()) {
					$fill_data = $this->fill_data();
					$is_exist = $this->model->isValExist('activation_key', $fill_data['get_activation_key'], 'users');
					if ($is_exist) {
						$query = $this->model->RowObject('users', 'activation_key', $fill_data['get_activation_key']);
						if ($query->is_active == 'true') { // Akun masih aktif
							$request_date = new DateTime($query->activation_key_request_date);
							$today = new DateTime(date('Y-m-d H:i:s'));
							$diff = $today->diff($request_date);
							$hours = $diff->h;
							$hours = $hours + ($diff->days * 24);
							if ($hours > 48) { // lebih dari 2 x 24 jam maka cancel reset passwordnya
								$this->m_users->remove_activation_key($query->id);
								$response['type'] = 'error';
								$response['message'] = 'expired';
							} else {
								unset($fill_data['get_activation_key']);
								if ($this->m_users->reset_password($query->id, $fill_data)) { // reset password
									$response['type'] = 'success';
									$response['message'] = 'has_updated';
								} else { // gagal query
									$response['type'] = 'error';
									$response['message'] = 'cannot_updated';	
								}
							}
						} else { // Akun sudah di non aktifkan oleh admin
							$response['type'] = 'warning';
							$response['message'] = 'not_active';
						}
					} else { // activation_key tidak ditemukan
						$response['message'] = '404';
					}
				} else { // validasi error
					$response['type'] = 'error';
					$response['message'] = validation_errors();
				}
				$response['csrf_token'] = $this->token->get_token();
			} else {
				$response['message'] = 'token_error';
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
			'user_password' => password_hash($this->input->post('password', true), PASSWORD_BCRYPT),
			'get_activation_key' => $this->input->post('activation_key', true),
			'activation_key' => NULL,
			'activation_key_request_date' => NULL
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
		$val->set_rules('password', 'Kata Sandi', 'trim|required|min_length[6]');
		$val->set_rules('c_password', 'Kata Sandi', 'trim|matches[password]');
		$val->set_message('min_length', '{field} harus diisi minimal 6 karakter');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('matches', '{field} kata sandi harus sama');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}