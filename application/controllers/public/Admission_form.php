<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Admission_form extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		// If close, redirect
		if ($this->session->userdata('admission_status') == 'close') {
			redirect(base_url());
		}

		// If not in array, redirect
		$admission_start_date = $this->session->userdata('admission_start_date');
		$admission_end_date = $this->session->userdata('admission_end_date');
		if (NULL !== $admission_start_date && NULL !== $admission_end_date) {
			$date_range = array_date($admission_start_date, $admission_end_date);
			if (!in_array(date('Y-m-d'), $date_range)) {
				redirect(base_url());
			}
		}

		$this->load->model('m_registrants');
		$this->pk = M_registrants::$pk;
		$this->table = M_registrants::$table;
	}

	/**
	* Index
	* @access  public
	*/
	public function index() {
		$this->load->helper(['string', 'form']);
		$this->load->model(['m_options', 'm_majors', 'm_admission_types', 'm_settings']);
		$recaptcha = $this->m_settings->get_recaptcha();
		$this->vars['recaptcha_site_key'] = $recaptcha['recaptcha_site_key'];
		$this->vars['page_title'] = 'Formulir Penerimaan ' . $this->session->userdata('_student') . ' Baru Tahun '.$this->session->userdata('admission_year');
		$this->vars['religions'] = ['' => 'Pilih :'] + $this->m_options->get_options('religions');
		$this->vars['special_needs'] = $this->m_options->get_options('special_needs');
		$this->vars['residences'] = ['' => 'Pilih :'] + $this->m_options->get_options('residences');
		$this->vars['transportations'] = ['' => 'Pilih :'] + $this->m_options->get_options('transportations');
		$this->vars['educations'] = ['' => 'Pilih :'] + $this->m_options->get_options('educations');
		$this->vars['employments'] = ['' => 'Pilih :'] + $this->m_options->get_options('employments');
		$this->vars['monthly_incomes'] = ['' => 'Pilih :'] + $this->m_options->get_options('monthly_incomes');
		$this->vars['majors'] = ['' => 'Pilih :'] + $this->m_majors->dropdown();
		$this->vars['admission_types'] = ['' => 'Pilih :'] + $this->m_admission_types->dropdown();
		$this->vars['content'] = 'themes/'.theme_folder().'/admission-form';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	* Save
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
				// Photo Uploaded
				$is_photo_uploaded = false;
				if (!empty($_FILES['photo']['name'])) {
					$photo = $this->photo_uploaded();
					if ($photo['type'] == 'success') {
						$is_photo_uploaded = true;
						$fill_data['photo'] = $photo['file_name'];
					} else {
						$response['type'] = $photo['type'];
						$response['message'] = $photo['message'];
					}
				}
				// Get Subject Values / Nilai Mata Pelajaran
				$subject_scores = json_decode($this->input->post('subject_scores'), true);
				$query = $this->m_registrants->save_registration_form($fill_data, $subject_scores);
				if ($query) {
					$result = $this->m_registrants->find_registrant($fill_data['birth_date'], $fill_data['registration_number']);
					$this->load->library('admission');
					$this->admission->create_pdf($result);
				}
				if (!isset($response['type'])) {
					$response['type'] = $query ? 'success' : 'error';
				}
				if (!isset($response['message'])) {
					$response['message'] = $query ? 'created' : 'not_created';
				}
				$file_name = 'formulir-penerimaan-'. ($this->session->userdata('school_level') >= 5 ? 'mahasiswa' : 'peserta-didik').'-baru-tahun-'.$this->session->userdata('admission_year');
				$file_name .= '-'.$fill_data['birth_date'].'-'.$fill_data['registration_number'].'.pdf';
				$response['file_name'] = $file_name;
				if (!$query && $is_photo_uploaded) {
					@unlink(FCPATH.'media_library/students/'.$photo['file_name']);
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
	* Upload Photo
	*/
	private function photo_uploaded() {
		$response = [];
		$config['upload_path'] = './media_library/students/';
		$config['allowed_types'] = 'jpg|jpeg';
		$config['max_size'] = 1024;
		$config['encrypt_name'] = true;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('photo')) {
			$response['type'] = 'error';
			$response['message'] = $this->upload->display_errors();
			$response['file_name'] = '';
		} else {
			$file = $this->upload->data();
			$response['type'] = 'success';
			$response['message'] = 'uploaded';
			$response['file_name'] = $file['file_name'];
		}
		return $response;
	}

	/**
	* Get Subject Settings
	*/
	public function get_subject_settings() {
		if ($this->input->is_ajax_request()) {
			$admission_type_id = (int) $this->input->post('admission_type_id', true);
			$major_id = $this->input->post('major_id', true);
			$this->load->model('m_admission_subject_settings');
			$response = [];
			$response['semester_report_subjects'] = $this->m_admission_subject_settings->get_subject_settings($admission_type_id, $major_id, 'semester_report', 'public');
			$response['national_exam_subjects'] = $this->m_admission_subject_settings->get_subject_settings($admission_type_id, $major_id, 'national_exam', 'public');
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
		$data = [];
		// Wajib diisi :
		$data['registration_number'] = $this->m_registrants->registration_number();
		$data['is_prospective_student'] = 'true';
		$data['is_alumni'] = 'false';
		$data['is_student'] = 'false';
		$data['re_registration'] = 'false';
		$data['is_transfer'] = $this->input->post('is_transfer', true);
		$data['admission_type_id'] = (int) $this->input->post('admission_type_id', true);
		$data['admission_phase_id'] = NULL !== $this->session->userdata('admission_phase_id') ? $this->session->userdata('admission_phase_id') : 0;
		$data['full_name'] = $this->input->post('full_name', true);
		$data['birth_date'] = $this->input->post('birth_date', true);
		$data['gender'] = $this->input->post('gender', true);
		$data['district'] = $this->input->post('district', true);
		if (filter_var($this->session->userdata('form_first_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['first_choice_id'] = $this->input->post('first_choice_id', true) ? (int) $this->input->post('first_choice_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_second_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['second_choice_id'] = $this->input->post('second_choice_id', true) ? (int) $this->input->post('second_choice_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_nisn')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['nisn'] = $this->input->post('nisn', true) ? $this->input->post('nisn', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['nik'] = $this->input->post('nik', true) ? $this->input->post('nik', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_prev_exam_number')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['prev_exam_number'] = $this->input->post('prev_exam_number', true) ? $this->input->post('prev_exam_number', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_achievement')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['achievement'] = $this->input->post('achievement', true) ? $this->input->post('achievement', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_paud')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['paud'] = $this->input->post('paud', true) ? $this->input->post('paud', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_tk')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['tk'] = $this->input->post('tk', true) ? $this->input->post('tk', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_skhun')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['skhun'] = $this->input->post('skhun', true) ? $this->input->post('skhun', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_prev_school_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['prev_school_name'] = $this->input->post('prev_school_name', true) ? $this->input->post('prev_school_name', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_prev_school_address')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['prev_school_address'] = $this->input->post('prev_school_address', true) ? $this->input->post('prev_school_address', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_prev_diploma_number')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['prev_diploma_number'] = $this->input->post('prev_diploma_number', true) ? $this->input->post('prev_diploma_number', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_hobby')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['hobby'] = $this->input->post('hobby', true) ? $this->input->post('hobby', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_ambition')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['ambition'] = $this->input->post('ambition', true) ? $this->input->post('ambition', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_birth_place')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['birth_place'] = $this->input->post('birth_place', true) ? $this->input->post('birth_place', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_religion_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['religion_id'] = $this->input->post('religion_id', true) ? (int) $this->input->post('religion_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['special_need_id'] = $this->input->post('special_need_id', true) ? (int) $this->input->post('special_need_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_street_address')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['street_address'] = $this->input->post('street_address', true) ? $this->input->post('street_address', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_rt')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['rt'] = $this->input->post('rt', true) ? $this->input->post('rt', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_rw')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['rw'] = $this->input->post('rw', true) ? $this->input->post('rw', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_sub_village')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['sub_village'] = $this->input->post('sub_village', true) ? $this->input->post('sub_village', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_village')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['village'] = $this->input->post('village', true) ? $this->input->post('village', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_sub_district')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['sub_district'] = $this->input->post('sub_district', true) ? $this->input->post('sub_district', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_postal_code')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['postal_code'] = $this->input->post('postal_code', true) ? $this->input->post('postal_code', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_residence_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['residence_id'] = $this->input->post('residence_id', true) ? $this->input->post('residence_id', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_transportation_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['transportation_id'] = $this->input->post('transportation_id', true) ? $this->input->post('transportation_id', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['phone'] = $this->input->post('phone', true) ? $this->input->post('phone', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_mobile_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['mobile_phone'] = $this->input->post('mobile_phone', true) ? $this->input->post('mobile_phone', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_email')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['email'] = $this->input->post('email', true) ? $this->input->post('email', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_sktm')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['sktm'] = $this->input->post('sktm', true) ? $this->input->post('sktm', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_kks')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['kks'] = $this->input->post('kks', true) ? $this->input->post('kks', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_kps')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['kps'] = $this->input->post('kps', true) ? $this->input->post('kps', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_kip')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['kip'] = $this->input->post('kip', true) ? $this->input->post('kip', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_kis')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['kis'] = $this->input->post('kis', true) ? $this->input->post('kis', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_citizenship')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['citizenship'] = $this->input->post('citizenship', true) ? $this->input->post('citizenship', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_country')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['country'] = $this->input->post('country', true) ? $this->input->post('country', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_father_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['father_name'] = $this->input->post('father_name', true) ? $this->input->post('father_name', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_father_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['father_birth_year'] = $this->input->post('father_birth_year', true) ? $this->input->post('father_birth_year', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_father_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['father_education_id'] = $this->input->post('father_education_id', true) ? (int) $this->input->post('father_education_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_father_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['father_employment_id'] = $this->input->post('father_employment_id', true) ? (int) $this->input->post('father_employment_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_father_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['father_monthly_income_id'] = $this->input->post('father_monthly_income_id', true) ? (int) $this->input->post('father_monthly_income_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_father_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['father_special_need_id'] = $this->input->post('father_special_need_id', true) ? (int) $this->input->post('father_special_need_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_mother_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['mother_name'] = $this->input->post('mother_name', true) ? $this->input->post('mother_name', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_mother_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['mother_birth_year'] = $this->input->post('mother_birth_year', true) ? $this->input->post('mother_birth_year', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_mother_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['mother_education_id'] = $this->input->post('mother_education_id', true) ? (int) $this->input->post('mother_education_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_mother_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['mother_employment_id'] = $this->input->post('mother_employment_id', true) ? (int) $this->input->post('mother_employment_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_mother_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['mother_monthly_income_id'] = $this->input->post('mother_monthly_income_id', true) ? (int) $this->input->post('mother_monthly_income_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_mother_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['mother_special_need_id'] = $this->input->post('mother_special_need_id', true) ? (int) $this->input->post('mother_special_need_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_guardian_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['guardian_name'] = $this->input->post('guardian_name', true) ? $this->input->post('guardian_name', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_guardian_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['guardian_birth_year'] = $this->input->post('guardian_birth_year', true) ? $this->input->post('guardian_birth_year', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_guardian_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['guardian_education_id'] = $this->input->post('guardian_education_id', true) ? (int) $this->input->post('guardian_education_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_guardian_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['guardian_employment_id'] = $this->input->post('guardian_employment_id', true) ? (int) $this->input->post('guardian_employment_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_guardian_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['guardian_monthly_income_id'] = $this->input->post('guardian_monthly_income_id', true) ? (int) $this->input->post('guardian_monthly_income_id', true) : 0;
		}
		if (filter_var($this->session->userdata('form_mileage')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['mileage'] = $this->input->post('mileage', true) ? $this->input->post('mileage', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_traveling_time')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['traveling_time'] = $this->input->post('traveling_time', true) ? $this->input->post('traveling_time', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_height')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['height'] = $this->input->post('height', true) ? $this->input->post('height', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_weight')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['weight'] = $this->input->post('weight', true) ? $this->input->post('weight', true) : NULL;
		}
		if (filter_var($this->session->userdata('form_sibling_number')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$data['sibling_number'] = $this->input->post('sibling_number', true) ? (int) $this->input->post('sibling_number', true) : 0;
		}
		return $data;
	}

	/**
	* Validations Form
	* @return Bool
	*/
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		// Wajib diisi karena dipakai untuk pencarian data [birth_date && registration_number]
		$val->set_rules('birth_date', 'Tanggal Lahir', 'trim|required');
		// Wajib diisi karena dipakai untuk Footer di pencetakan PDF
		$val->set_rules('district', 'Kabupaten', 'trim|required');
		$val->set_rules('full_name', 'Nama Lengkap', 'trim|required');
		$val->set_rules('gender', 'Jenis Kelamin', 'trim|required|in_list[M,F]');
		$val->set_rules('is_transfer', 'Jenis Pendaftaran', 'trim|required|in_list[true,false]');
		$val->set_rules('admission_type_id', 'Jalur Pendaftaran', 'trim|is_natural_no_zero|required');

		if (filter_var($this->session->userdata('form_first_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_first_choice_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('first_choice_id', 'Pilihan I', $rules);
		}
		if (filter_var($this->session->userdata('form_second_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_second_choice_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('second_choice_id', 'Pilihan II', $rules);
		}
		if (filter_var($this->session->userdata('form_photo')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			if (filter_var($this->session->userdata('form_photo')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				$val->set_rules('photo', 'Foto', 'callback_photo_check');
			}
		}
		if (filter_var($this->session->userdata('form_nisn')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_nisn')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('nisn', 'Nomor Induk Siswa Nasional', $rules);
		}
		if (filter_var($this->session->userdata('form_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_nik')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('nik', 'Nomor Induk Kependudukan', $rules);
		}
		if (filter_var($this->session->userdata('form_prev_exam_number')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_prev_exam_number')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('prev_exam_number', 'Nomor Ujian Nasional Sebelumnya', $rules);
		}
		if (filter_var($this->session->userdata('form_achievement')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_achievement')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('achievement', 'Prestasi yang Pernah Diraih', $rules);
		}
		if (filter_var($this->session->userdata('form_paud')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_paud')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('paud', 'Apakah Pernah PAUD ?', $rules);
		}
		if (filter_var($this->session->userdata('form_tk')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_tk')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('tk', 'Apaka Pernah TK ?', $rules);
		}
		if (filter_var($this->session->userdata('form_skhun')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_skhun')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('skhun', 'Nomor Seri SKHUN', $rules);
		}
		if (filter_var($this->session->userdata('form_prev_school_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_prev_school_name')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('prev_school_name', 'Nama Sekolah Asal ', $rules);
		}
		if (filter_var($this->session->userdata('form_prev_school_address')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_prev_school_address')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('prev_school_address', 'Alamat Sekolah Asal ', $rules);
		}
		if (filter_var($this->session->userdata('form_prev_diploma_number')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_prev_diploma_number')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('prev_diploma_number', 'Nomor Seri Ijazah Sebelumnya', $rules);
		}
		if (filter_var($this->session->userdata('form_hobby')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_hobby')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('hobby', 'Hobi', $rules);
		}
		if (filter_var($this->session->userdata('form_ambition')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_ambition')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('ambition', 'Cita-cita', $rules);
		}
		if (filter_var($this->session->userdata('form_full_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_full_name')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('full_name', 'Nama Lengkap', $rules);
		}
		if (filter_var($this->session->userdata('form_gender')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_gender')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('gender', 'Jenis Kelamin', $rules);
		}
		if (filter_var($this->session->userdata('form_birth_place')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_birth_place')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('birth_place', 'Tempat Lahir', $rules);
		}
		if (filter_var($this->session->userdata('form_religion_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_religion_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('religion_id', 'Agama', $rules);
		}
		if (filter_var($this->session->userdata('form_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_special_need_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('special_need_id', 'Kebutuhan Khusus', $rules);
		}
		if (filter_var($this->session->userdata('form_street_address')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_street_address')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('street_address', 'Alamat Jalan', $rules);
		}
		if (filter_var($this->session->userdata('form_rt')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_rt')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('rt', 'RT', $rules);
		}
		if (filter_var($this->session->userdata('form_rw')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_rw')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('rw', 'RW', $rules);
		}
		if (filter_var($this->session->userdata('form_sub_village')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_sub_village')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('sub_village', 'Nama Dusun', $rules);
		}
		if (filter_var($this->session->userdata('form_village')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_village')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('village', 'Nama Kelurahan/ Desa', $rules);
		}
		if (filter_var($this->session->userdata('form_sub_district')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_sub_district')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('sub_district', 'Kecamatan', $rules);
		}
		if (filter_var($this->session->userdata('form_postal_code')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_postal_code')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('postal_code', 'Kode Pos', $rules);
		}
		if (filter_var($this->session->userdata('form_residence_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_residence_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('residence_id', 'Tempat Tinggal', $rules);
		}
		if (filter_var($this->session->userdata('form_transportation_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_transportation_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('transportation_id', 'Moda Transportasi', $rules);
		}
		if (filter_var($this->session->userdata('form_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_phone')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('phone', 'Telepon', $rules);
		}
		if (filter_var($this->session->userdata('form_mobile_phone')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_mobile_phone')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('mobile_phone', 'Nomor HP', $rules);
		}
		if (filter_var($this->session->userdata('form_email')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'valid_email'];
			if (filter_var($this->session->userdata('form_email')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('email', 'Email', $rules);
		}
		if (filter_var($this->session->userdata('form_sktm')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_sktm')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('sktm', 'Surat Keterangan Tidak Mampu (SKTM)', $rules);
		}
		if (filter_var($this->session->userdata('form_kks')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_kks')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('kks', 'Kartu Keluarga Sejahtera (KKS)', $rules);
		}
		if (filter_var($this->session->userdata('form_kps')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_kps')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('kps', 'Kartu Pra Sejahtera (KPS)', $rules);
		}
		if (filter_var($this->session->userdata('form_kip')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_kip')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('kip', 'Kartu Indonesia Pintar (KIP)', $rules);
		}
		if (filter_var($this->session->userdata('form_kis')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_kis')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('kis', 'Kartu Indonesia Sehat (KIS)', $rules);
		}
		if (filter_var($this->session->userdata('form_citizenship')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_citizenship')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required', 'in_list[WNI,WNA]');
			}
			$val->set_rules('citizenship', 'Kewarganegaraan', $rules);
		}
		if (filter_var($this->session->userdata('form_country')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_country')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('country', 'Nama Negara', $rules);
		}
		if (filter_var($this->session->userdata('form_father_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_father_name')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('father_name', 'Nama Ayah', $rules);
		}
		if (filter_var($this->session->userdata('form_father_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric', 'min_length[4]', 'max_length[4]'];
			if (filter_var($this->session->userdata('form_first_choice_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('father_birth_year', 'Tahun Lahir Ayah', $rules);
		}
		if (filter_var($this->session->userdata('form_father_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_father_education_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('father_education_id', 'Pendidikan Ayah', $rules);
		}
		if (filter_var($this->session->userdata('form_father_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_father_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('father_employment_id', 'Pekerjaan Ayah', $rules);
		}
		if (filter_var($this->session->userdata('form_father_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_father_monthly_income_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('father_monthly_income_id', 'Penghasilan Bulanan Ayah', $rules);
		}
		if (filter_var($this->session->userdata('form_father_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_father_special_need_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('father_special_need_id', 'Kebutuhan Khusus Ayah', $rules);
		}
		if (filter_var($this->session->userdata('form_mother_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_mother_name')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('mother_name', 'Nama Ibu', $rules);
		}
		if (filter_var($this->session->userdata('form_mother_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric', 'min_length[4]', 'max_length[4]'];
			if (filter_var($this->session->userdata('form_mother_birth_year')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('mother_birth_year', 'Tahun Lahir Ibu', $rules);
		}
		if (filter_var($this->session->userdata('form_mother_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_mother_education_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('mother_education_id', 'Pendidikan Ibu', $rules);
		}
		if (filter_var($this->session->userdata('form_mother_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_mother_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('mother_employment_id', 'Pekerjaan Ibu', $rules);
		}
		if (filter_var($this->session->userdata('form_mother_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_mother_monthly_income_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('mother_monthly_income_id', 'Penghasilan Bulanan Ibu', $rules);
		}
		if (filter_var($this->session->userdata('form_mother_special_need_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_mother_special_need_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('mother_special_need_id', 'Kebutuhan Khusus Ibu', $rules);
		}
		if (filter_var($this->session->userdata('form_guardian_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim'];
			if (filter_var($this->session->userdata('form_guardian_name')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('guardian_name', 'Nama Wali', $rules);
		}
		if (filter_var($this->session->userdata('form_guardian_birth_year')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric', 'min_length[4]', 'max_length[4]'];
			if (filter_var($this->session->userdata('form_guardian_birth_year')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('guardian_birth_year', 'Tahun Lahir Wali', $rules);
		}
		if (filter_var($this->session->userdata('form_guardian_education_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_guardian_education_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('guardian_education_id', 'Pendidikan Wali', $rules);
		}
		if (filter_var($this->session->userdata('form_guardian_employment_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_guardian_employment_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('guardian_employment_id', 'Pekerjaan Wali', $rules);
		}
		if (filter_var($this->session->userdata('form_guardian_monthly_income_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric'];
			if (filter_var($this->session->userdata('form_guardian_monthly_income_id')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('guardian_monthly_income_id', 'Penghasilan Bulanan Wali', $rules);
		}
		if (filter_var($this->session->userdata('form_mileage')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric', 'min_length[1]', 'max_length[5]'];
			if (filter_var($this->session->userdata('form_mileage')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('mileage', 'Jarak Tempat Tinggal ke Sekolah', $rules);
		}
		if (filter_var($this->session->userdata('form_traveling_time')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric', 'min_length[1]', 'max_length[5]'];
			if (filter_var($this->session->userdata('form_traveling_time')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('traveling_time', 'Waktu Tempuh ke Sekolah', $rules);
		}
		if (filter_var($this->session->userdata('form_height')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric', 'min_length[2]', 'max_length[3]'];
			if (filter_var($this->session->userdata('form_height')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('height', 'Tinggi Badan', $rules);
		}
		if (filter_var($this->session->userdata('form_weight')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric', 'min_length[2]', 'max_length[3]'];
			if (filter_var($this->session->userdata('form_weight')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('weight', 'Berat Badan', $rules);
		}
		if (filter_var($this->session->userdata('form_sibling_number')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$rules = ['trim', 'numeric', 'min_length[1]', 'max_length[2]'];
			if (filter_var($this->session->userdata('form_sibling_number')['admission_required'], FILTER_VALIDATE_BOOLEAN)) {
				array_push($rules, 'required');
			}
			$val->set_rules('sibling_number', 'Jumlah Saudara Kandung', $rules);
		}

		$val->set_rules('declaration', 'Pernyataan', 'trim|required|in_list[true]|callback_declaration_check');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('min_length', '{field} Harus Diisi Minimal {param} Karakter');
		$val->set_message('max_length', '{field} harus Diisi Maksimal {param} Karakter');
		$val->set_message('numeric', '{field} harus diisi dengan angka');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	* Declaration Check
	* @return Bool
	*/
	public function declaration_check($str) {
		if (!filter_var($str, FILTER_VALIDATE_BOOLEAN)) {
			$this->form_validation->set_message('declaration_check', 'Pernyataan Harus Diceklis');
			return FALSE;
		}
		return TRUE;
	}

	/**
	* Photo Check
	* @return Bool
	*/
	public function photo_check() {
		if(empty($_FILES['photo']['name'])) {
			$this->form_validation->set_message('photo_check', 'Foto belum dipilih.');
			return FALSE;
		}
		return TRUE;
	}
}
