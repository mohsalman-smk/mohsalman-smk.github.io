<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Login extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		if ($this->auth->is_logged_in())
			redirect('dashboard');
	}

	/**
	 * Index
	 * @access  public
	 */
	public function index() {
		$this->vars['page_title'] = 'Login to Our Site';
		$this->vars['can_logged_in'] = $this->auth->check_login_attempts($_SERVER['REMOTE_ADDR']);
		$this->vars['login_info'] = $this->vars['can_logged_in'] ? 'Selamat datang <br> Silahkan masukan Username dan Password anda' : 'Akses anda terblokir oleh sistem kami selama 30 Menit';
		$this->vars['content'] = 'users/login';
		$this->load->view('users/index', $this->vars);
	}

	/**
	 * process
	 * @access  public
	 */
	public function process() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			if ($this->validation()) {
				$user_name = $this->input->post('username', TRUE);
				$user_password = $this->input->post('password', TRUE);
				$ip_address = get_ip_address();
				$logged_in = $this->auth->logged_in($user_name, $user_password, $ip_address) ? 'success' : 'error';
				$response['type'] = $logged_in;
				$response['message'] = $logged_in == 'success' ? 'logged_in' : 'not_logged_in';
				$response['can_logged_in'] = $this->auth->check_login_attempts($_SERVER['REMOTE_ADDR']);
			} else {
				$response['type'] = 'error';
				$response['message'] = validation_errors();
				$response['can_logged_in'] = TRUE;
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Validations Form
	 * @access  public
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('username', 'Username', 'trim|required');
		$val->set_rules('password', 'Password', 'trim|required');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}