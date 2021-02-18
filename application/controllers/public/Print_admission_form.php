<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Print_admission_form extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_registrants', 
			'm_settings'
		]);
	}

	/**
	 * Index
	 * @access  public
	 */
	public function index() {
		$recaptcha = $this->m_settings->get_recaptcha();
		$this->vars['page_title'] = 'Cetak Formulir Penerimaan ' . $this->session->userdata('_student') . ' Baru Tahun '.$this->session->userdata('admission_year');
		$this->vars['action'] = 'print_admission_form/process';
		$this->vars['button'] = '<i class="fa fa-file-pdf-o"></i> CETAK FORMULIR';
		$this->vars['onclick'] = 'print_admission_form()';
		$this->vars['recaptcha_site_key'] = $recaptcha['recaptcha_site_key'];
		$this->vars['content'] = 'themes/'.theme_folder().'/admission-search-form';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * PDF Generated Process
	 * @access  public
	 */
	public function process() {
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
				$birth_date = $this->input->post('birth_date');
				$registration_number = $this->input->post('registration_number');
				$result = $this->m_registrants->find_registrant($birth_date, $registration_number);
				if (!count($result)) {
					$response['type'] = 'warning';
					$response['message'] = 'Data dengan tanggal lahir '.indo_date($birth_date).' dan nomor pendaftaran '.$registration_number.' tidak ditemukan.';
				} else {
					$file_name = 'formulir-penerimaan-'. ($this->session->userdata('school_level') >= 5 ? 'mahasiswa' : 'peserta-didik').'-baru-tahun-'.$this->session->userdata('admission_year');
					$file_name .= '-'.$birth_date.'-'.$registration_number.'.pdf';
					if (!file_exists(FCPATH.'media_library/students/'.$file_name)) {
						$this->load->library('admission');
						$this->admission->create_pdf($result);
					}
					$response['type'] = 'success';
					$response['file_name'] = $file_name;
				}
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
