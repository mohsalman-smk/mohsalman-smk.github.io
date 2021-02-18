<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Pollings extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('public/m_pollings');
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
					$answer_id = $this->input->post('answer_id', true);
					$response['type'] = $this->m_pollings->save($answer_id) ? 'success' : 'info';
					$response['message'] = $response['type'] == 'success' ? 'Terima kasih sudah mengikuti polling kami.' : 'Anda sudah mengikuti polling kami hari ini.';
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
		$val->set_rules('answer_id', 'Jawaban', 'trim|required|is_natural_no_zero');
		$val->set_message('required', '{field} harus diisi');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Eesult
	 */
	public function result() {
		$this->vars['title'] = 'Hasil Jajak Pendapat';
		$query = get_active_question();
		$results = $this->m_pollings->polling_result($query->id);
		$labels = [];
		$data = [];
		foreach($results->result() as $row) {
			array_push($labels, $row->labels);
			array_push($data, $row->data);
		}
		$this->vars['labels'] = json_encode($labels);
		$this->vars['data'] = json_encode($data);
		$this->vars['question'] = $query->question;
		$this->vars['content'] = 'themes/'.theme_folder().'/polling-result';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}
}
