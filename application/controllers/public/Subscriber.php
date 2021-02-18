<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Subscriber extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_subscribers');
	}
		
	/**
	 * Save or Update
	 * @return Object 
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			if ($this->input->post('csrf_token') && $this->token->is_valid_token($this->input->post('csrf_token'))) {
				if ($this->validation()) {
					$email = $this->input->post('subscriber', true);
					$response['type'] = $this->m_subscribers->save($email) ? 'success' : 'info';
					$response['message'] = $response['type'] == 'success' ? 'Email anda sudah tersimpan' : 'Email anda sudah terdaftar dalam database kami.';
				} else {
					$response['type'] = 'error';
					$response['message'] = validation_errors();
				}
				$response['csrf_token'] = $this->token->get_token();
			} else {
				$response['type'] = 'token_error';
			}
			
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
	
	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('subscriber', 'Email', 'trim|required|valid_email');
		$val->set_message('valid_email', 'Masukan email dengan format yang benar');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}