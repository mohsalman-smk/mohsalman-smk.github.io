<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMA Karya Budi Cileunyi
 * @version    v4.6.2
 * @author     Ginanjar Restu U., S.Pd. | https://instagram.com/ginanjar_ru | lapakphp@gmail.com
 * @copyright  (c) 2017-2018
 * @link       http://sma-karyabudi.sch.id
 * @since      Version v4.6.2
 */

class Contact_us extends Public_Controller {

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
	 * @access  public
	 */
	public function index() {
		$this->load->model('m_settings');
		$recaptcha = $this->m_settings->get_recaptcha();
		$this->vars['recaptcha_site_key'] = $recaptcha['recaptcha_site_key'];
		$this->vars['page_title'] = 'Hubungi Kami';
		$this->vars['latitude'] = NULL !== $this->session->userdata('latitude') ? $this->session->userdata('latitude') : 0;
		$this->vars['longitude'] = NULL !== $this->session->userdata('longitude') ? $this->session->userdata('longitude') : 0;
		$this->vars['api_key'] = NULL !== $this->session->userdata('google_map_api_key') ? $this->session->userdata('google_map_api_key') : 0;
		$this->vars['content'] = 'themes/'.theme_folder().'/contact-us';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * save
	 * @access  public
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			if (NULL !== $this->session->userdata('recaptcha_status') && $this->session->userdata('recaptcha_status') == 'enable') {
				$this->load->library('recaptcha');
				$recaptcha = $this->input->post('recaptcha');
				$recaptcha_verified = $this->recaptcha->verifyResponse($recaptcha);
				if (!$recaptcha_verified['success']) {
					$response['type'] = 'recaptcha_error';
	    			$response['message'] = 'Recaptcha Error!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT))
						->_display();
					exit;
				}
			}

			if ($this->validation()) {
				$fill_data = $this->fill_data();
				$response['action'] = 'save';
				$response['type'] = $this->model->insert('comments', $fill_data) ? 'success' : 'error';
				$response['message'] = $response['type'] == 'success' ? 'Pesan anda sudah tersimpan.' : 'Pesan anda tidak tersimpan.';
			} else {
				$response['type'] = 'validation_errors';
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
		$this->load->library('user_agent');
		$disallowed = explode(',', $this->session->userdata('comment_blacklist'));
		return [
			'comment_author' => $this->input->post('comment_author', true),
			'comment_email' => $this->input->post('comment_email', true),
			'comment_url' => prep_url($this->input->post('comment_url', true)),
			'comment_content' => word_censor($this->input->post('comment_content', true), $disallowed, '****'),
			'comment_type' => 'message',
			'comment_ip_address' => $_SERVER['REMOTE_ADDR'],
			'comment_agent' => $this->agent->agent_string()
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
		$val->set_rules('comment_author', 'Nama Lengkap', 'trim|required');
		$val->set_rules('comment_email', 'Email', 'trim|required|valid_email');
		$val->set_rules('comment_url', 'URL', 'trim|valid_url');
		$val->set_rules('comment_content', 'Komentar', 'trim|required');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('valid_email', '{field} harus diisi dengan format email yang benar');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
