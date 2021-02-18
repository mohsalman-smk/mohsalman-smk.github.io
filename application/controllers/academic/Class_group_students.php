<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Class_group_students extends Admin_Controller {
	
	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->model([
			'm_academic_years', 
			'm_class_groups', 
			'm_class_group_students',
			'm_students'
			]);
	}

	/**
	 * Create
	 */
	public function create() {
		$this->vars['title'] = 'PENGATURAN ROMBONGAN BELAJAR';
		$this->vars['academic'] = $this->vars['academic_settings'] = $this->vars['class_group_students'] = true;
		$this->vars['ds_academic_years'] = $this->m_academic_years->dropdown();
		$this->vars['ds_class_groups'] = $this->m_class_groups->dropdown();
		$this->vars['content'] = 'class_group_students/create';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get Students
	 */
	public function get_students() {
		if ($this->input->is_ajax_request()) {
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$class_group_id = $this->input->post('class_group_id', true);
			$query = $this->m_class_group_students->get_students($academic_year_id, $class_group_id);
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Save to Destination Class
	 */
	public function save_to_destination_class() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$student_ids = $this->input->post('student_ids', true);
			$ids = [];
			foreach (explode(',', $student_ids) as $student_id) {
				array_push($ids, trim($student_id));
			}
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$query = $this->m_class_group_students->save_to_destination_class($ids, $academic_year_id, $class_group_id);
			$response['type'] = $query ? 'success' : 'error';
			$response['message'] = $query ? 'Data sudah disipman' : 'Data tidak tersimpan. Kemungkinan terjadi duplikasi data, pengaturan wali kelas belum diatur, atau server bermasalah, silahkan periksa kembali data Anda.';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Delete Permanently
	 */
	public function delete_permanently() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$student_ids = $this->input->post('student_ids', true);
			$ids = [];
			foreach (explode(',', $student_ids) as $student_id) {
				array_push($ids, trim($student_id));
			}
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$query = $this->m_class_group_students->delete_permanently($ids, $academic_year_id, $class_group_id);
			$response['type'] = $query ? 'success' : 'error';
			$response['message'] = $query ? 'Data sudah terhapus' : 'Data tidak terhapus';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Set as alumni
	 */
	public function set_as_alumni() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$student_ids = $this->input->post('student_ids', true);
			$ids = [];
			foreach (explode(',', $student_ids) as $student_id) {
				array_push($ids, trim($student_id));
			}
			$end_date = (int) $this->input->post('end_date', true);
			$query = $this->m_students->set_as_alumni($ids, $end_date);
			$response['type'] = $query ? 'success' : 'error';
			$response['message'] = $query ? 'Data sudah tersimpan' : 'Data tidak tersimpan';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}