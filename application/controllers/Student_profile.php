<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Student_profile extends Admin_Controller {

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
	 * @return Void
	 */
	public function index() {
		$this->load->helper('form');
		$this->load->model('m_options');
		$id = (int) $this->session->userdata('user_profile_id');
		$this->vars['title'] = 'Biodata';
		$this->vars['student_profile'] = true;
		$this->vars['religions'] = ['' => 'Pilih :'] + $this->m_options->get_options('religions');
		$this->vars['special_needs'] = ['' => 'Pilih :'] + $this->m_options->get_options('special_needs');
		$this->vars['residences'] = ['' => 'Pilih :'] + $this->m_options->get_options('residences');
		$this->vars['transportations'] = ['' => 'Pilih :'] + $this->m_options->get_options('transportations');
		$this->vars['educations'] = ['' => 'Pilih :'] + $this->m_options->get_options('educations');
		$this->vars['employments'] = ['' => 'Pilih :'] + $this->m_options->get_options('employments');
		$this->vars['monthly_incomes'] = ['' => 'Pilih :'] + $this->m_options->get_options('monthly_incomes');
		$this->vars['query'] = $this->model->RowObject('students', 'id', $id);
		$this->vars['content'] = 'students/profile';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * save
	 * @access  public
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->session->userdata('user_profile_id');
			$response = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				if ($this->validation()) {
					$fill_data = $this->fill_data();
					$fill_data['updated_by'] = $id;
					$response['type'] = $this->model->update($id, 'students', $fill_data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated';
				} else {
					$response['action'] = 'validation_errors';
					$response['type'] = 'error';
					$response['message'] = validation_errors();
				}
			} else {
				$response['type'] = 'error';
				$response['message'] = 'not_updated';
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
			'paud' => $this->input->post('paud', true),
			'tk' => $this->input->post('tk', true),
			'hobby' => $this->input->post('hobby', true),
			'ambition' => $this->input->post('ambition', true),
			'birth_place' => $this->input->post('birth_place', true),
			'birth_date' => $this->input->post('birth_date', true),
			'religion_id' => $this->input->post('religion_id', true),
			'special_need_id' => $this->input->post('special_need_id', true),
			'street_address' => $this->input->post('street_address', true),
			'rt' => $this->input->post('rt', true),
			'rw' => $this->input->post('rw', true),
			'sub_village' => $this->input->post('sub_village', true),
			'village' => $this->input->post('village', true),
			'sub_district' => $this->input->post('sub_district', true),
			'district' => $this->input->post('district', true),
			'postal_code' => $this->input->post('postal_code', true),
			'residence_id' => $this->input->post('residence_id', true),
			'transportation_id' => $this->input->post('transportation_id', true),
			'phone' => $this->input->post('phone', true),
			'mobile_phone' => $this->input->post('mobile_phone', true),
			'email' => $this->input->post('email') ? $this->input->post('email', true) : NULL,
			'sktm' => $this->input->post('sktm', true),
			'kks' => $this->input->post('kks', true),
			'kps' => $this->input->post('kps', true),
			'kip' => $this->input->post('kip', true),
			'kis' => $this->input->post('kis', true),
			'citizenship' => $this->input->post('citizenship', true),
			'country' => $this->input->post('country', true),
			'father_name' => $this->input->post('father_name', true),
			'father_birth_year' => $this->input->post('father_birth_year', true),
			'father_education_id' => $this->input->post('father_education_id', true),
			'father_employment_id' => $this->input->post('father_employment_id', true),
			'father_monthly_income_id' => $this->input->post('father_monthly_income_id', true),
			'father_special_need_id' => $this->input->post('father_special_need_id', true),
			'mother_name' => $this->input->post('mother_name', true),
			'mother_birth_year' => $this->input->post('mother_birth_year', true),
			'mother_education_id' => $this->input->post('mother_education_id', true),
			'mother_employment_id' => $this->input->post('mother_employment_id', true),
			'mother_monthly_income_id' => $this->input->post('mother_monthly_income_id', true),
			'mother_special_need_id' => $this->input->post('mother_special_need_id', true),
			'guardian_name' => $this->input->post('guardian_name', true),
			'guardian_birth_year' => $this->input->post('guardian_birth_year', true),
			'guardian_education_id' => $this->input->post('guardian_education_id', true),
			'guardian_employment_id' => $this->input->post('guardian_employment_id', true),
			'guardian_monthly_income_id' => $this->input->post('guardian_monthly_income_id', true),
			'mileage' => $this->input->post('mileage', true),
			'traveling_time' => $this->input->post('traveling_time', true),
			'height' => $this->input->post('height', true),
			'weight' => $this->input->post('weight', true),
			'sibling_number' => $this->input->post('sibling_number', true)
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
		$val->set_rules('email', 'Email', 'trim|required|valid_email');
		$val->set_rules('father_birth_year', 'Tahun Lahir Ayah', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('mother_birth_year', 'Tahun Lahir Ibu', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('guardian_birth_year', 'Tahun Lahir Wali', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('sibling_number', 'Jumlah Saudara Kandung', 'trim|numeric|min_length[1]|max_length[2]');
		$val->set_rules('rt', 'RT', 'trim|numeric');
		$val->set_rules('rw', 'RW', 'trim|numeric');
		$val->set_rules('postal_code', 'Kode Pos', 'trim|numeric');
		$val->set_rules('mileage', 'Jarak Tempat Tinggal ke Sekolah', 'trim|numeric');
		$val->set_rules('traveling_time', 'Waktu Tempuh ke Sekolah', 'trim|numeric');
		$val->set_rules('height', 'Tinggi Badan', 'trim|numeric|min_length[2]|max_length[3]');
		$val->set_rules('weight', 'Berat Badan', 'trim|numeric|min_length[2]|max_length[3]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}