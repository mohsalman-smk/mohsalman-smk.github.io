<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Admission_selection_results extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_registrants',
			'm_verification_subject_scores'
		]);
	}

	/**
	 * Index
	 * @access  public
	 */
	public function index() {
		// if isset
		$announcement_start_date = $this->session->userdata('announcement_start_date');
		$announcement_end_date = $this->session->userdata('announcement_end_date');
		if (NULL !== $announcement_start_date && NULL !== $announcement_end_date) {
			// If not in array, redirect
			$date_range = array_date($announcement_start_date, $announcement_end_date);
			if (!in_array(date('Y-m-d'), $date_range)) {
				redirect(base_url());
			}
		}

		$this->load->model('m_settings');
		$recaptcha = $this->m_settings->get_recaptcha();
		$this->vars['page_title'] = 'Hasil Seleksi Penerimaan ' . $this->session->userdata('_student') . ' Baru Tahun '.$this->session->userdata('admission_year');
		$this->vars['action'] = 'admission_selection_results/selection_results';
		$this->vars['button'] = '<i class="fa fa-search"></i> LIHAT HASIL SELEKSI';
		$this->vars['onclick'] = 'selection_results()';
		$this->vars['recaptcha_site_key'] = $recaptcha['recaptcha_site_key'];
		$this->vars['content'] = 'themes/'.theme_folder().'/admission-search-form';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * Selection Results
	 * @return Object
	 */
	public function selection_results() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$recaptcha_status = $this->session->userdata('recaptcha_status');
			if (NULL !== $recaptcha_status && $recaptcha_status == 'enable') {
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
				$birth_date = $this->input->post('birth_date', true);
				$registration_number = $this->input->post('registration_number', true);
				$query = $this->m_registrants->selection_result($registration_number, $birth_date);
				$response['type'] = $query['type'];
				$response['message'] = $query['message'];
				$response['subject_scores'] = $this->m_verification_subject_scores->find_subject_scores($registration_number, $birth_date);
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
	 * Validations Form
	 * @access  public
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('registration_number', 'Nomor Pendaftaran', 'trim|required|numeric|max_length[10]|min_length[10]');
		$val->set_rules('birth_date', 'Tanggal Lahir', 'trim|required|callback_date_format_check');
		$val->set_message('required', '{field} harus diisi');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Declaration Check
	 * @return Bool
	 */
	public function date_format_check($str) {
		if (!is_valid_date($str)) {
			$this->form_validation->set_message('date_format_check', 'Tanggal lahir harus diisi dengan format YYYY-MM-DD');
			return FALSE;
		}
		return TRUE;
	}
}
