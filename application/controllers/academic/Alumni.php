<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Alumni extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_alumni');
		$this->pk = M_alumni::$pk;
		$this->table = M_alumni::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'ALUMNI';
		$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['alumni'] = true;
		$this->vars['content'] = 'alumni/read';
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
			$this->load->model(['m_students', 'm_scholarships', 'm_achievements']);
			$this->vars['student'] = $this->m_students->profile($id);
			$this->vars['scholarships'] = $this->m_scholarships->get_by_student_id($id);
			$this->vars['achievements'] = $this->m_achievements->get_by_student_id($id);
			$this->vars['title'] = 'Profil Alumni';
			$this->vars['photo'] = base_url('media_library/images/no-image.jpg');
			$photo_name = $this->vars['student']->photo;
			$photo = 'media_library/students/' . $photo_name;
			if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
				$this->vars['photo'] = base_url($photo);
			}
			$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['alumni'] = true;
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
			$query = $this->m_alumni->get_where($keyword, $limit, $offset);
			$total_rows = $this->m_alumni->total_rows($keyword);
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
					if ($fill_data['is_alumni'] == 'false') {
						$this->load->model('m_student_status');
						$student_status_id = (int) $this->m_student_status->find_student_status_id('aktif');
						if ($student_status_id > 0) {
							$fill_data['student_status_id'] = $student_status_id;
						}
					} else if ($fill_data['is_alumni'] == 'unverified') {
						$fill_data['is_student'] = 'false';
						$fill_data['is_prospective_student'] = 'false';
					}
					$response['action'] = 'update';
					$response['type'] = $this->model->update($id, $this->table, $fill_data) ? 'success' : 'error';
					$response['message'] = $response['type'] == 'success' ? 'updated' : 'not_updated';
				} else {
					$response['action'] = 'update';
					$response['type'] = 'error';
					$response['message'] = 'not_updated';
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
			'is_alumni' => $this->input->post('is_alumni', true),
			'full_name' => $this->input->post('full_name', true),
			'street_address' => $this->input->post('street_address', true),
			'rt' => $this->input->post('rt', true),
			'rw' => $this->input->post('rw', true),
			'sub_village' => $this->input->post('sub_village', true),
			'village' => $this->input->post('village', true),
			'sub_district' => $this->input->post('sub_district', true),
			'district' => $this->input->post('district', true),
			'postal_code' => $this->input->post('postal_code', true),
			'phone' => $this->input->post('phone', true),
			'mobile_phone' => $this->input->post('mobile_phone', true),
			'email' => $this->input->post('email', true),
			'end_date' => $this->input->post('end_date', true),
			'reason' => $this->input->post('reason', true)
		];
	}

	/**
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('full_name', 'Full Name', 'trim|required');
		$val->set_rules('email', 'Email', 'trim|required|valid_email');
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
					$data = $this->upload->data();
					$query = $this->model->update($id, $this->table, ['photo' => $data['file_name']]);
					if ($query) {
						@chmod(FCPATH.'media_library/students/'.$file_name, 0777);
						$crop = $this->image_resize(FCPATH.'media_library/students/', $data['file_name']);
						if ($crop) {
							@unlink(FCPATH.'media_library/students/'.$file_name);
						}
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
	 * Alumni Reports
	 * @access 	public
	 */
	public function alumni_reports() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_alumni->alumni_reports();
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
	  * Create Alumni Account - Single Activation
	  * Insert Alumni record to users
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
	  * Create All Alumni Accounts
	  * Insert Alumni record to users
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
