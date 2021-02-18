<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Post_comments extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * save
	 * @access  public
	 */
	public function index() {
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
				$response['message'] = $response['type'] == 'success' ? 'Komentar anda sudah tersimpan.' : 'Komentar anda tidak tersimpan.';
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
		return [
			'comment_author' => $this->input->post('comment_author', true),
			'comment_email' => $this->input->post('comment_email', true),
			'comment_url' => $this->input->post('comment_url', true),
			'comment_content' => $this->input->post('comment_content', true),
			'comment_type' => 'post',
			'comment_post_id' => $this->input->post('comment_post_id', true),
			'comment_status' => filter_var($this->session->userdata('comment_moderation'), FILTER_VALIDATE_BOOLEAN) ? 'unapproved' : 'approved',
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
		$val->set_rules('comment_author', 'Nama Lengkap', 'trim|required|alpha_numeric_spaces');
		$val->set_rules('comment_email', 'Email', 'trim|required|valid_email');
		$val->set_rules('comment_url', 'URL', 'trim|valid_url');
		$val->set_rules('comment_content', 'Komentar', 'trim|required');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('alpha_numeric_spaces', '{field} hanya bisa diisi dengan huruf, angka, dan spasi');
		$val->set_message('valid_email', '{field} harus diisi dengan format email yang benar');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Load More Comments
	 * @access  public
	 * @return Bool
	 */
	public function more_comments() {
		if ($this->input->is_ajax_request()) {
			$comment_post_id = (int) $this->input->post('comment_post_id', true);
			$page_number = (int) $this->input->post('page_number', true);
			$offset = ($page_number - 1) * (int) $this->session->userdata('comment_per_page');
			$response = [];
			if ($comment_post_id > 0) {
				$this->load->model('public/m_post_comments');
				$query = $this->m_post_comments->get_more_comments($comment_post_id, $offset);
				$rows = [];
				foreach($query->result() as $row) {
					$rows[] = $row;
				}
				$response['comments'] = $rows;
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
