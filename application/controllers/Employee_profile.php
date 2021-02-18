<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Employee_profile extends Admin_Controller {

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
		$id = NULL !== $this->session->userdata('user_profile_id') ? $this->session->userdata('user_profile_id') : 0;
		$this->vars['title'] = 'Biodata';
		$this->vars['employee_profile'] = true;
		$this->vars['religions'] = ['' => 'Pilih :'] + $this->m_options->get_options('religions');
		$this->vars['marriage_status'] = ['' => 'Pilih :'] + $this->m_options->get_options('marriage_status');
		$this->vars['employment_status'] = ['' => 'Pilih :'] + $this->m_options->get_options('employment_status');
		$this->vars['employments'] = ['' => 'Pilih :'] + $this->m_options->get_options('employments');
		$this->vars['employment_types'] = ['' => 'Pilih :'] + $this->m_options->get_options('employment_types');
		$this->vars['institution_lifters'] = ['' => 'Pilih :'] + $this->m_options->get_options('institution_lifters');
		$this->vars['salary_sources'] = ['' => 'Pilih :'] + $this->m_options->get_options('salary_sources');
		$this->vars['laboratory_skills'] = ['' => 'Pilih :'] + $this->m_options->get_options('laboratory_skills');
		$this->vars['special_needs'] = ['' => 'Pilih :'] + $this->m_options->get_options('special_needs');
		$this->vars['ranks'] = ['' => 'Pilih :'] + $this->m_options->get_options('ranks');
		$this->vars['query'] = $this->model->RowObject('employees', 'id', $id);
		$this->vars['content'] = 'employees/profile';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * save
	 * @access  public
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = NULL !== $this->session->userdata('user_profile_id') ? $this->session->userdata('user_profile_id') : 0;
			$response = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				if ($this->validation()) {
					$fill_data = $this->fill_data();
					$fill_data['updated_by'] = $id;
					$response['type'] = $this->model->update($id, 'employees', $fill_data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated';
					if ($response['type'] == 'success') {
						$nik = $fill_data['nik'];
						if ($nik != $this->session->userdata('user_name')) {
							$this->load->model('m_users');
							$query = $this->m_users->change_user_name($nik);
							if ($query) {
								$this->session->set_userdata('user_name', $nik);
							}
						}
					}
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
			'assignment_letter_number' => $this->input->post('assignment_letter_number', true),
			'assignment_letter_date' => $this->input->post('assignment_letter_date', true),
			'assignment_start_date' => $this->input->post('assignment_start_date', true),
			'parent_School_status' => $this->input->post('parent_School_status', true),
			'full_name' => $this->input->post('full_name', true),
			'gender' => $this->input->post('gender', true),
			'nik' => $this->input->post('nik') ? $this->input->post('nik', true) : NULL,
			'birth_place' => $this->input->post('birth_place', true),
			'birth_date' => $this->input->post('birth_date', true),
			'mother_name' => $this->input->post('mother_name', true),
			'street_address' => $this->input->post('street_address', true),
			'rt' => $this->input->post('rt', true),
			'rw' => $this->input->post('rw', true),
			'sub_village' => $this->input->post('sub_village', true),
			'village' => $this->input->post('village', true),
			'sub_district' => $this->input->post('sub_district', true),
			'district' => $this->input->post('district', true),
			'postal_code' => $this->input->post('postal_code', true),
			'religion_id' => $this->input->post('religion_id', true),
			'marriage_status_id' => $this->input->post('marriage_status_id', true),
			'spouse_name' => $this->input->post('spouse_name', true),
			'spouse_employment_id' => $this->input->post('spouse_employment_id', true),
			'citizenship' => $this->input->post('citizenship', true),
			'country' => $this->input->post('country', true),
			'npwp' => $this->input->post('npwp') ? $this->input->post('npwp', true) : NULL,
			'employment_status_id' => $this->input->post('employment_status_id', true),
			'nip' => $this->input->post('nip') ? $this->input->post('nip', true) : NULL,
			'niy' => $this->input->post('niy') ? $this->input->post('niy', true) : NULL,
			'nuptk' => $this->input->post('nuptk') ? $this->input->post('nuptk', true) : NULL,
			'employment_type_id' => $this->input->post('employment_type_id', true),
			'decree_appointment' => $this->input->post('decree_appointment', true),
			'appointment_start_date' => $this->input->post('appointment_start_date', true),
			'institution_lifter_id' => $this->input->post('institution_lifter_id', true),
			'decree_cpns' => $this->input->post('decree_cpns', true),
			'pns_start_date' => $this->input->post('pns_start_date', true),
			'rank_id' => $this->input->post('rank_id', true),
			'salary_source_id' => $this->input->post('salary_source_id', true),
			'headmaster_license' => $this->input->post('headmaster_license', true),
			'laboratory_skill_id' => $this->input->post('laboratory_skill_id') ? $this->input->post('laboratory_skill_id', true) : NULL,
			'special_need_id' => $this->input->post('special_need_id', true),
			'braille_skills' => $this->input->post('braille_skills', true),
			'sign_language_skills' => $this->input->post('sign_language_skills', true),
			'phone' => $this->input->post('phone', true),
			'mobile_phone' => $this->input->post('mobile_phone', true),
			'email' => $this->input->post('email') ? $this->input->post('email', true) : NULL
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
		$val->set_rules('nik', 'NIK', 'trim|required');
		$val->set_rules('email', 'Email', 'trim|required|valid_email');
		$val->set_rules('rt', 'RT', 'trim|numeric');
		$val->set_rules('rw', 'RW', 'trim|numeric');
		$val->set_rules('postal_code', 'Kode Pos', 'trim|numeric');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
