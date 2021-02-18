<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Students extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_students',
			'm_majors'
		]);
		$this->pk = M_students::$pk;
		$this->table = M_students::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = strtoupper($this->session->userdata('_student'));
		$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['students'] = true;
		$majors = $this->m_majors->dropdown();
		if ($this->session->userdata('school_level') >= 3) {
			$majors  = [0 => 'Unset'] + $majors;
		}
		$this->vars['ds_majors'] = json_encode($majors);
		$this->vars['content'] = 'students/read';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Student Profile
	 * @param 	Int
	 * @access 	public
	 * @return 	Void
	 */
	public function profile() {
		$id = (int) $this->uri->segment(4);
		if ($id !== 0 && ctype_digit((string) $id)) {
			$this->load->model(['m_students', 'm_verification_subject_scores', 'm_scholarships', 'm_achievements']);
			$this->vars['student'] = $this->m_students->profile($id);
			$this->vars['scholarships'] = $this->m_scholarships->get_by_student_id($id);
			$this->vars['achievements'] = $this->m_achievements->get_by_student_id($id);
			$this->vars['title'] = 'Profil ' . $this->session->userdata('_student');
			$this->vars['photo'] = base_url('media_library/images/no-image.jpg');
			$photo_name = $this->vars['student']->photo;
			$photo = 'media_library/students/' . $photo_name;
			if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
				$this->vars['photo'] = base_url($photo);
			}
			$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['students'] = true;
			$this->vars['content'] = 'students/profile_preview';
			$this->load->view('backend/index', $this->vars);
		} else {
			show_404();
		}
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
			$query = $this->m_students->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_students->total_rows($keyword);
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
					$fill_data['created_by'] = $this->session->userdata('id');
					$response['action'] = 'save';
					$response['type'] = $this->model->insert($this->table, $fill_data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'created' : 'not_created';
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
	 * Fill Data
	 * @return Array
	 */
	private function fill_data() {
		return [
			'is_student' => 'true',
			'is_transfer' => $this->input->post('is_transfer', true),
			'start_date' => $this->input->post('start_date', true),
			'identity_number' => $this->input->post('identity_number') ? $this->input->post('identity_number', true) : NULL,
			'major_id' => $this->input->post('major_id') ? $this->input->post('major_id', true) : 0,
			'paud' => $this->input->post('paud', true),
			'tk' => $this->input->post('tk', true),
			'hobby' => $this->input->post('hobby', true),
			'ambition' => $this->input->post('ambition', true),
			'full_name' => $this->input->post('full_name', true),
			'gender' => $this->input->post('gender', true),
			'nisn' => $this->input->post('nisn') ? $this->input->post('nisn', true) : NULL,
			'nik' => $this->input->post('nik') ? $this->input->post('nik', true) : NULL,
			'skhun' => $this->input->post('skhun') ? $this->input->post('skhun', true) : NULL,
			'prev_exam_number' => $this->input->post('prev_exam_number') ? $this->input->post('prev_exam_number', true) : NULL,
			'prev_diploma_number' => $this->input->post('prev_diploma_number') ? $this->input->post('prev_diploma_number', true) : NULL,
			'prev_school_name' => $this->input->post('prev_school_name') ? $this->input->post('prev_school_name', true) : NULL,
			'prev_school_address' => $this->input->post('prev_school_address') ? $this->input->post('prev_school_address', true) : NULL,
			'birth_place' => $this->input->post('birth_place', true),
			'birth_date' => $this->input->post('birth_date', true),
			'religion_id' => $this->input->post('religion_id', true),
			'special_need_id' => $this->input->post('special_need_id') == 0 ? NULL : $this->input->post('special_need_id', true),
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
			'sibling_number' => $this->input->post('sibling_number', true),
			'student_status_id' => $this->input->post('student_status_id', true),
			'end_date' => $this->input->post('end_date', true),
			'reason' => $this->input->post('reason', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$id = (int) $this->input->post('id', true);
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('identity_number', $this->session->userdata('_identity_number'), 'trim|required');
		$val->set_rules('full_name', 'Nama Lengkap', 'trim|required');
		$val->set_rules('student_status_id', 'Status ' . $this->session->userdata('_student'), 'trim|required');
		$val->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check['.$id.']');
		$val->set_rules('father_birth_year', 'Tahun Lahir Ayah', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('mother_birth_year', 'Tahun Lahir Ibu', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('guardian_birth_year', 'Tahun Lahir Wali', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('sibling_number', 'Jumlah Saudara Kandung', 'trim|numeric|min_length[1]|max_length[2]');
		$val->set_rules('rt', 'RT', 'trim|numeric');
		$val->set_rules('rw', 'RW', 'trim|numeric');
		$val->set_rules('postal_code', 'Kode Pos', 'trim|numeric');
		$val->set_rules('mileage', 'Jarak Tempat Tinggal ke Sekolah', 'trim|numeric');
		$val->set_rules('traveling_time', 'Waktu Tempuh ke Sekolah', 'trim|numeric');
		$val->set_rules('height', 'Tinggi Badan', 'trim|numeric');
		$val->set_rules('weight', 'Berat Badan', 'trim|numeric');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('valid_email', '{field} harus diisi dengan format email yang benar');
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
	 * Student Reports
	 * @access 	public
	 */
	public function student_reports() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_students->student_reports();
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
	  * Create Student Account - Single Activation 
	  * Insert students record to users
	  */
	public function create_account() {
		if ($this->input->is_ajax_request()) {
			$response['type'] = 'error';
			$response['message'] = 'Undefined Message';
			$id = (int) $this->input->post('id', true);
			$query = $this->model->RowObject($this->table, $this->pk, $id);
			$is_email_exist = $this->model->is_email_exist($query->email, $id);
			if ($is_email_exist['is_exist'] === TRUE) {
				$response['type'] = 'error';
				$response['message'] = 'Email sudah digunakan oleh '.$is_email_exist['used_by'];
			} else {
				if ($id !== 0 && ctype_digit((string) $id)) {
					$data = [];
					$data['user_name'] = $query->identity_number;
					$data['user_password'] = password_hash($query->identity_number, PASSWORD_BCRYPT);
					$data['user_full_name'] = $query->full_name;
					$data['user_email'] = $query->email;
					$data['user_registered'] = date('Y-m-d H:i:s');
					$data['user_group_id'] = 0;
					$data['user_type'] = 'student';
					$data['user_profile_id'] = $id;
					$data['created_at'] = date('Y-m-d H:i:s');
					$data['created_by'] = $this->session->userdata('id');
					$response['type'] = $this->model->insert('users', $data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'Akun sudah diaktifkan' : 'Akun gagal diaktifkan. Nama Pengguna dan/atau Email sudah digunakan.';
				} else {
					$response['type'] = 'error';
					$response['message'] = 'ID is not number';
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
	  * Create All Student Accounts
	  * Insert students record to users
	  */
	public function create_accounts() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$success = 0;
			$existed = 0;
			$query = $this->m_students->get_active_students();
			$fill_data = [];
			foreach($query->result() as $row) {
				$user_name_exist = $this->model->isValExist('user_name', $row->identity_number, 'users');
				$email_exist = $this->model->isValExist('user_email', $row->email, 'users');
				if (!$user_name_exist && !$email_exist) {
					$fill_data[] = [
						'user_name' => $row->identity_number,
						'user_password' => password_hash($row->identity_number, PASSWORD_BCRYPT),
						'user_full_name' => $row->full_name,
						'user_email' => $row->email,
						'user_registered' => date('Y-m-d H:i:s'),
						'user_group_id' => 0,
						'user_type' => 'student',
						'user_profile_id' => $row->id,
						'created_at' => NULL,
						'created_by' => $this->session->userdata('id'),
					];
					$success++;
				} else {
					$existed++;
				}
			}
			if (count($fill_data) > 0) {
				$this->db->insert_batch('users', $fill_data);
			}
			$response['type'] = 'success';
			$response['message'] = ($success > 0 ? $success .' akun sudah diaktifkan' : '') . ($success > 0 && $existed > 0 ? ' dan ' : '') . ($existed > 0 ? $existed.' akun sudah diaktifkan sebelumnya.':'.');
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
