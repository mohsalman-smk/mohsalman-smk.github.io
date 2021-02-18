<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Employees extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_employees');
		$this->pk = M_employees::$pk;
		$this->table = M_employees::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = $this->session->userdata('school_level') >= 5 ? 'STAFF DAN DOSEN' : 'GURU DAN TENAGA KEPENDIDIKAN';
		$this->vars['employees'] = $this->vars['all_employees'] = true;
		$this->vars['content'] = 'employees/read';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Profile
	 * @param 	Int
	 * @access 	public
	 * @return 	Void
	 */
	public function profile() {
		$id = (int) $this->uri->segment(4);
		if ($id !== 0 && ctype_digit((string) $id)) {
			$this->vars['query'] = $this->m_employees->profile($id);
			$this->vars['title'] = 'Profil ' . $this->session->userdata('school_level') >= 5 ? 'Staff dan Dosen' : 'Guru dan Tenaga Kependidikan';
			$this->vars['photo'] = base_url('media_library/images/no-image.jpg');
			$photo_name = $this->vars['query']->photo;
			$photo = 'media_library/employees/' . $photo_name;
			if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
				$this->vars['photo'] = base_url($photo);
			}
			$this->vars['employees'] = $this->vars['all_employees'] = true;
			$this->vars['content'] = 'employees/profile_preview';
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
			$query = $this->m_employees->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_employees->total_rows($keyword);
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
			'assignment_letter_number' => $this->input->post('assignment_letter_number', true),
			'assignment_letter_date' => $this->input->post('assignment_letter_date', true),
			'assignment_start_date' => $this->input->post('assignment_start_date', true),
			'parent_school_status' => $this->input->post('parent_school_status', true),
			'full_name' => $this->input->post('full_name', true),
			'gender' => $this->input->post('gender', true),
			'nik' => $this->input->post('nik', true),
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
			'npwp' => $this->input->post('npwp', true) ? $this->input->post('npwp', true) : NULL,
			'employment_status_id' => $this->input->post('employment_status_id', true),
			'nip' => $this->input->post('nip', true) ? $this->input->post('nip', true) : NULL,
			'niy' => $this->input->post('niy', true) ? $this->input->post('niy', true) : NULL,
			'nuptk' => $this->input->post('nuptk', true),
			'employment_type_id' => $this->input->post('employment_type_id', true),
			'decree_appointment' => $this->input->post('decree_appointment', true),
			'appointment_start_date' => $this->input->post('appointment_start_date', true),
			'institution_lifter_id' => $this->input->post('institution_lifter_id', true),
			'decree_cpns' => $this->input->post('decree_cpns', true),
			'pns_start_date' => $this->input->post('pns_start_date', true),
			'rank_id' => $this->input->post('rank_id', true),
			'salary_source_id' => $this->input->post('salary_source_id', true),
			'headmaster_license' => $this->input->post('headmaster_license', true),
			'laboratory_skill_id' => $this->input->post('laboratory_skill_id', true),
			'special_need_id' => $this->input->post('special_need_id', true),
			'braille_skills' => $this->input->post('braille_skills', true),
			'sign_language_skills' => $this->input->post('sign_language_skills', true),
			'phone' => $this->input->post('phone', true),
			'mobile_phone' => $this->input->post('mobile_phone', true),
			'email' => $this->input->post('email', true)
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
		$val->set_rules('full_name', 'Full Name', 'trim|required');
		$val->set_rules('nik', 'NIK', 'trim|required');
		$val->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_check['.$id.']');
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
				$config['upload_path'] = './media_library/employees/';
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
						@chmod(FCPATH.'media_library/employees/'.$file['file_name'], 0777);
						// chmood old file
						@chmod(FCPATH.'media_library/employees/'.$file_name, 0777);
						// unlink old file
						@unlink(FCPATH.'media_library/employees/'.$file_name);
						// resize new image
						$this->image_resize(FCPATH.'media_library/employees', $file['file_name']);
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
	  * Image Resize
	  * @param String
	  * @param String
	  * @return Void
	  */
	 private function image_resize($source, $file_name) {
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $source .'/'.$file_name;
		$config['maintain_ratio'] = false;
		$config['width'] = (int) $this->session->userdata('employee_photo_width');
		$config['height'] = (int) $this->session->userdata('employee_photo_height');
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
	}

	/**
	  * Create Employees Account
	  */
	public function create_employee_account() {
		if ($this->input->is_ajax_request()) {
			$response = [];
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
					$data['user_name'] = $query->nik;
					$data['user_password'] = password_hash($query->nik, PASSWORD_BCRYPT);
					$data['user_full_name'] = $query->full_name;
					$data['user_email'] = $query->email;
					$data['user_registered'] = date('Y-m-d H:i:s');
					$data['user_group_id'] = 0;
					$data['user_type'] = 'employee';
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
	  * Create All Employees Accounts
	  * Insert students record to users
	  */
	public function create_employee_accounts() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$success = 0;
			$existed = 0;
			$query = $this->m_employees->get_active_employees();
			$fill_data = [];
			foreach($query->result() as $row) {
				$user_name_exist = $this->model->isValExist('user_name', $row->nik, 'users');
				$email_exist = $this->model->isValExist('user_email', $row->email, 'users');
				if (!$user_name_exist && !$email_exist) {
					$fill_data[] = [
						'user_name' => $row->nik,
						'user_password' => password_hash($row->nik, PASSWORD_BCRYPT),
						'user_full_name' => $row->full_name,
						'user_email' => $row->email,
						'user_registered' => date('Y-m-d H:i:s'),
						'user_group_id' => 0,
						'user_type' => 'employee',
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
