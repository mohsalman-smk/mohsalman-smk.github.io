<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Registrants extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_registrants', 
			'm_majors', 
			'm_admission_types', 
			'm_admission_phases'
		]);
		$this->pk = M_registrants::$pk;
		$this->table = M_registrants::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'CALON ' . strtoupper($this->session->userdata('_student')) . ' BARU';
		$this->vars['admission_year'] = $this->session->userdata('admission_year');
		$this->vars['admission'] = $this->vars['registrants'] = true;
		$this->vars['ds_majors'] = json_encode([]);
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->vars['ds_majors'] = json_encode([0 => 'Unset'] + $this->m_majors->dropdown());
		}
		$this->vars['ds_admission_types'] = json_encode($this->m_admission_types->dropdown());
		$this->vars['ds_admission_phases'] = json_encode($this->m_admission_phases->dropdown());
		$this->vars['content'] = 'admission/registrants';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Pagination
	 * @return Object
	 */
	public function pagination() {
		if ($this->input->is_ajax_request()) {
			$page_number = (int) $this->input->post('page_number', true);
			$limit = (int) $this->input->post('per_page', true);
			$keyword = trim($this->input->post('keyword', true));
			$offset = ($page_number * $limit);
			$query = $this->m_registrants->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_registrants->total_rows($keyword);
			$total_page = $limit > 0 ? ceil($total_rows / $limit) : 1;
			$response = [];
			$response['total_page'] = 0;
			$response['total_rows'] = 0;
			if ($query->num_rows() > 0) {
				$rows = [];
				foreach($query->result() as $row) {
					$rows[] = $row;
				}
				$response = [
					'total_page' => (int) $total_page,
					'total_rows' => (int) $total_rows,
					'rows' => $rows
				];
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Find by ID
	 * @return Object
	 */
	public function find_id() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$query = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				$query = $this->model->RowObject($this->table, $this->pk, $id);
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Save or Update
	 * @return Object
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			if ($this->validation()) {
				$fill_data = $this->fill_data();
				if ($id !== 0 && ctype_digit((string) $id)) {
					$fill_data['updated_at'] = date('Y-m-d H:i:s');
					$fill_data['updated_by'] = $this->session->userdata('id');
					$response['action'] = 'update';
					$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated';
				} else {
					$response['action'] = 'save';
					$response['type'] = 'error';
					$response['message'] = 'not_created';
				}
			} else {
				$response['action'] = 'validation_errors';
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
	 * Verified prospective studnets
	 * @return 	Object
	 */
	public function verified() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			$fill_data['updated_at'] = date('Y-m-d H:i:s');
			$fill_data['updated_by'] = $this->session->userdata('id');
			$fill_data['re_registration'] = $this->input->post('re_registration', true);
			$response['action'] = 'update';
			$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
			$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated';
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
			'is_transfer' => $this->input->post('is_transfer', true),
			'admission_type_id' => (int) $this->input->post('admission_type_id', true),
			'admission_phase_id' => (int) $this->input->post('admission_phase_id', true),
			'prev_school_name' => $this->input->post('prev_school_name', true),
			'prev_school_address' => $this->input->post('prev_school_address', true),
			'prev_exam_number' => $this->input->post('prev_exam_number', true),
			'paud' => $this->input->post('paud', true),
			'tk' => $this->input->post('tk', true),
			'skhun' => $this->input->post('skhun', true),
			'prev_diploma_number' => $this->input->post('prev_diploma_number', true),
			'achievement' => $this->input->post('achievement', true),
			'hobby' => $this->input->post('hobby', true),
			'ambition' => $this->input->post('ambition', true),
			'first_choice_id' => (int) $this->input->post('first_choice_id', true),
			'second_choice_id' => (int) $this->input->post('second_choice_id', true),
			'full_name' => $this->input->post('full_name', true),
			'gender' => $this->input->post('gender', true),
			'nisn' => $this->input->post('nisn') ? $this->input->post('nisn', true) : NULL,
			'nik' => $this->input->post('nik') ? $this->input->post('nik', true) : NULL,
			'birth_place' => $this->input->post('birth_place', true),
			'birth_date' => $this->input->post('birth_date', true),
			'religion_id' => (int) $this->input->post('religion_id', true),
			'special_need_id' => (int) $this->input->post('special_need_id', true),
			'street_address' => $this->input->post('street_address', true),
			'rt' => $this->input->post('rt', true),
			'rw' => $this->input->post('rw', true),
			'sub_village' => $this->input->post('sub_village', true),
			'village' => $this->input->post('village', true),
			'sub_district' => $this->input->post('sub_district', true),
			'district' => $this->input->post('district', true),
			'postal_code' => $this->input->post('postal_code', true),
			'residence_id' => (int) $this->input->post('residence_id', true),
			'transportation_id' => (int) $this->input->post('transportation_id', true),
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
			'father_education_id' => (int) $this->input->post('father_education_id', true),
			'father_employment_id' => (int) $this->input->post('father_employment_id', true),
			'father_monthly_income_id' => (int) $this->input->post('father_monthly_income_id', true),
			'father_special_need_id' => (int) $this->input->post('father_special_need_id', true),
			'mother_name' => $this->input->post('mother_name', true),
			'mother_birth_year' => $this->input->post('mother_birth_year', true),
			'mother_education_id' => (int) $this->input->post('mother_education_id', true),
			'mother_employment_id' => (int) $this->input->post('mother_employment_id', true),
			'mother_monthly_income_id' => (int) $this->input->post('mother_monthly_income_id', true),
			'mother_special_need_id' => (int) $this->input->post('mother_special_need_id', true),
			'guardian_name' => $this->input->post('guardian_name', true),
			'guardian_birth_year' => $this->input->post('guardian_birth_year', true),
			'guardian_education_id' => (int) $this->input->post('guardian_education_id', true),
			'guardian_employment_id' => (int) $this->input->post('guardian_employment_id', true),
			'guardian_monthly_income_id' => (int) $this->input->post('guardian_monthly_income_id', true),
			'mileage' => $this->input->post('mileage', true),
			'traveling_time' => $this->input->post('traveling_time', true),
			'height' => $this->input->post('height', true),
			'weight' => $this->input->post('weight', true),
			'sibling_number' => $this->input->post('sibling_number', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('admission_type_id', 'Jalur Pendaftaran', 'trim|required|is_natural_no_zero');
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$val->set_rules('first_choice_id', 'First Choice', 'trim|required');
			$val->set_rules('second_choice_id', 'Second Choice', 'trim');
		}
		$val->set_rules('full_name', 'Full Name', 'trim|required');
		$val->set_rules('email', 'Email', 'trim|valid_email');
		$val->set_rules('father_birth_year', 'Father Birth Year', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('mother_birth_year', 'Mother Birth Year', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('guardian_birth_year', 'Guardian Birth Year', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Upload
	 * @return Void
	 */
	public function upload() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				$query = $this->model->RowObject($this->table, $this->pk, $id);
				$file_name = $query->photo;
				$config = [];
				$config['upload_path'] = './media_library/students/';
				$config['allowed_types'] = 'jpg|png|jpeg|gif';
				$config['max_size'] = 0;
				$config['encrypt_name'] = true;
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('file')) {
					$response['action'] = 'validation_errors';
					$response['type'] = 'error';
					$response['message'] = $this->upload->display_errors();
				} else {
					$file = $this->upload->data();
					$update = $this->model->update($id, $this->table, ['photo' => $file['file_name']]);
					if ($update) {
						// chmood new file
						@chmod(FCPATH.'media_library/students/'.$file['file_name'], 0777);
						// chmood old file
						@chmod(FCPATH.'media_library/students/'.$file_name, 0777);
						// unlink old file
						@unlink(FCPATH.'media_library/students/'.$file_name);
						// resize new image
						$this->image_resize(FCPATH.'media_library/students', $file['file_name']);
					}
					$response['action'] = 'upload';
					$response['type'] = 'success';
					$response['message'] = 'uploaded';
				}
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	  * Resize Images
	  * @param 	String
	  * @param 	String
	  */
	 private function image_resize($source, $file_name) {
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source .'/'.$file_name;
		$config['maintain_ratio'] = false;
		$config['width'] = (int) $this->session->userdata('student_photo_width');
		$config['height'] = (int) $this->session->userdata('student_photo_height');
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
	}

	/**
	  * Print PDF Registration Form
	  */
	public function print_admission_form() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$query = $this->model->RowObject($this->table, $this->pk, $id);
			$response = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				$this->load->model('m_registrants');
				$result = $this->m_registrants->find_registrant($query->birth_date, $query->registration_number);
				if (count($result) == 0) {
					$response['type'] = 'warning';
					$response['message'] = 'Data dengan tanggal lahir '.indo_date($query->birth_date).' dan nomor pendaftaran '.$query->registration_number.' tidak ditemukan.';
				} else {
					$file_name = 'formulir-penerimaan-'. ($this->session->userdata('school_level') >= 5 ? 'mahasiswa' : 'peserta-didik').'-baru-tahun-'.$this->session->userdata('admission_year');
					$file_name .= '-'.$query->birth_date.'-'.$query->registration_number.'.pdf';
					$this->load->library('admission');
					$this->admission->create_pdf($result);
					$response['type'] = 'success';
					$response['file_name'] = $file_name;
				}
			} else {
				$response['type'] = 'error';
				$response['message'] = 'Format data tidak valid.';
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	  * Print PDF Registration Form
	  */
	public function print_exam_cards() {
		if ($this->input->is_ajax_request()) {
			$id = (int) $this->input->post('id', true);
			$response = [];
			if ($id !== 0 && ctype_digit((string) $id)) {
				$students = $this->model->RowObject($this->table, $this->pk, $id);
				$this->load->model('m_admission_exam_schedule_details');
				$schedules = $this->m_admission_exam_schedule_details->exam_schedule_by_student_id($id);
				$file_name = 'kartu-peserta-ujian-penerimaan-'. ($this->session->userdata('school_level') >= 5 ? 'mahasiswa' : 'peserta-didik').'-baru-tahun-'.$this->session->userdata('admission_year');
				$file_name .= '-'.$students->registration_number.'.pdf';
				$this->load->library('Exam_cards');
				$this->exam_cards->create_pdf($file_name, $students, $schedules);
				$response['type'] = 'success';
				$response['file_name'] = $file_name;
			} else {
				$response['type'] = 'error';
				$response['message'] = 'Format data tidak valid.';
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Admission Reports
	 * @access 	public
	 */
	public function admission_reports() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_registrants->admission_reports();
			$response = [];
			foreach ($query->result() as $row) {
				array_push($response, $row);
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Profile
	 * @access 	public
	 * @return 	Void
	 */
	public function profile() {
		$id = (int) $this->uri->segment(4);
		if ($id !== 0 && ctype_digit((string) $id)) {
			$this->load->model(['m_students', 'm_verification_subject_scores']);
			$this->vars['student'] = $this->m_students->profile($id);
			$this->vars['subjects'] = $this->m_verification_subject_scores->subject_scores_by_student_id($id);
			$this->vars['title'] = 'Profil Calon ' . $this->session->userdata('_student') . ' Baru';
			$this->vars['photo'] = base_url('media_library/images/no-image.jpg');
			$photo_name = $this->vars['student']->photo;
			$photo = 'media_library/students/' . $photo_name;
			if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
				$this->vars['photo'] = base_url($photo);
			}
			$this->vars['admission'] = $this->vars['registrants'] = true;
			$this->vars['content'] = 'admission/profile_preview';
			$this->load->view('backend/index', $this->vars);
		} else {
			show_404();
		}
	}
}
