<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Alumni extends Public_Controller {

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
		$this->load->helper('form');
		$this->load->model('m_settings');
		$recaptcha = $this->m_settings->get_recaptcha();
		$this->vars['page_title'] = 'Pendaftaran Alumni';
		$this->vars['recaptcha_site_key'] = $recaptcha['recaptcha_site_key'];
		$this->vars['content'] = 'themes/'.theme_folder().'/alumni-form';
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
				$is_uploaded = false;
				if (!empty($_FILES['file']['name'])) {
					$upload = $this->upload();
					if ($upload['type'] == 'success') {
						$is_uploaded = true;
						$fill_data['photo'] = $upload['file_name'];
					} else {
						$response['type'] = $upload['type'];
						$response['message'] = $upload['message'];
					}
				}
				$query = $this->model->insert('students', $fill_data);
				if (!isset($response['type'])) {
					$response['type'] = $query ? 'success' : 'error';
				}
				if (!isset($response['message'])) {
					$response['message'] = $query ? 'Data sudah tersimpan' : 'Data tidak tersimpan';
				}
			} else {
				$response['type'] = 'error';
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
		return [
			'is_alumni' => 'unverified',
			'full_name' => $this->input->post('full_name', true),
			'gender' => $this->input->post('gender', true),
			'birth_date' => $this->input->post('birth_date', true),
			'end_date' => $this->input->post('end_date', true).'-06-20',
			'identity_number' => $this->input->post('identity_number', true),
			'street_address' => $this->input->post('street_address', true),
			'email' => $this->input->post('email', true),
			'phone' => $this->input->post('phone', true),
			'mobile_phone' => $this->input->post('mobile_phone', true)
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
		$val->set_rules('full_name', 'Nama Lengkap', 'trim|required');
		$val->set_rules('gender', 'Jenis Kelamin', 'trim|required');
		$val->set_rules('birth_date', 'Tanggal Lahir', 'trim|required|callback_date_format_check');
		$val->set_rules('end_date', 'Tahun Lulus', 'trim|required|min_length[4]|max_length[4]|numeric');
		$val->set_rules('identity_number', $this->session->userdata('_identity_number'), 'trim');
		$val->set_rules('street_address', 'ALamat Jalan', 'trim|required');
		$val->set_rules('email', 'Email', 'trim|required|valid_email');
		$val->set_rules('phone', 'Nomor Telepon', 'trim');
		$val->set_rules('mobile_phone', 'Nomor Handphone', 'trim');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('valid_email', '{field} harus diisi dengan format email yang benar');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	  * upload Images
	  */
	private function upload() {
		$response = [];
		$config['upload_path'] = './media_library/students/';
		$config['allowed_types'] = 'jpg|jpeg';
		$config['max_size'] = 1024; // 1 Mb
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('file')) {
			$response['type'] = 'error';
			$response['message'] = $this->upload->display_errors();
			$response['file_name'] = '';
		} else {
			$file = $this->upload->data();
			// chmood file
			@chmod(FCPATH.'media_library/albums/'.$file['file_name'], 0777);
			$this->image_resize(FCPATH.'media_library/students/', $file['file_name']);
			$response['type'] = 'success';
			$response['message'] = 'uploaded';
			$response['file_name'] = $file['file_name'];
		}
		return $response;
	}

	/**
	  * Resize Images
	  */
	 private function image_resize($source, $file_name, $image_size = 'large') {
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source .'/'.$file_name;
		$config['new_image'] = $source .'/'.$image_size;
		$config['maintain_ratio'] = false;
		$config['width'] = (int) $this->session->userdata('student_photo_width');
		$config['height'] = (int) $this->session->userdata('student_photo_height');
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		@chmod($source.'/'.$file_name, 0644);
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
