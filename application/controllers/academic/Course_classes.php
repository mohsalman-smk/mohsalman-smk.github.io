<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Course_classes extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_course_classes'
			, 'm_academic_years'
			, 'm_class_groups'
		]);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->load->helper('form');
		$this->vars['title'] = 'PENGATURAN MATA ' . ($this->session->userdata('school_level') >= 5 ? 'KULIAH' : 'PELAJARAN');
		$this->vars['academic'] = $this->vars['academic_settings'] = $this->vars['course_classes'] = true;
		$this->vars['academic_year_dropdown'] = $this->m_academic_years->dropdown();
		$this->vars['class_group_dropdown'] = $this->m_class_groups->dropdown();
		$this->vars['content'] = 'course_classes/create';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get Subjects
	 */
	public function get_subjects() {
		if ($this->input->is_ajax_request()) {
			$copy_data = $this->input->post('copy_data', true);
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$semester = $this->input->post('semester', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$query = $this->m_course_classes->get_subjects($copy_data, $academic_year_id, $semester, $class_group_id);
			
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}

	}

	/**
	 * Get Course Classes
	 */
	public function get_course_classes() {
		if ($this->input->is_ajax_request()) {
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$semester = $this->input->post('semester', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$query = $this->m_course_classes->get_course_classes($academic_year_id, $semester, $class_group_id);
		
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
	public function save() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$subject_ids = $this->input->post('subject_ids', true);
			$ids = [];
			foreach (explode(',', $subject_ids) as $subject_id) {
				array_push($ids, trim($subject_id));
			}
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$semester = $this->input->post('semester', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$query = $this->m_course_classes->save($ids, $academic_year_id, $semester, $class_group_id);
			$response['type'] = $query ? 'success' : 'error';
			$response['message'] = $query ? 'Data sudah disipman' : 'Data tidak tersimpan. Kemungkinan terjadi duplikasi data atau server bermasalah, silahkan periksa kembali data Anda.';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * Change Is Deleted
	 */
	public function change_deleted_status() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$subject_ids = $this->input->post('subject_ids', true);
			$ids = [];
			foreach (explode(',', $subject_ids) as $subject_id) {
				array_push($ids, trim($subject_id));
			}
			$is_deleted = $this->input->post('is_deleted', true);
			$academic_year_id = (int) $this->input->post('academic_year_id', true);
			$semester = $this->input->post('semester', true);
			$class_group_id = (int) $this->input->post('class_group_id', true);
			$query = $this->m_course_classes->change_deleted_status($ids, $academic_year_id, $semester, $class_group_id, $is_deleted);
			$response['type'] = $query ? 'success' : 'error';
			if ($is_deleted == 'true') {
				$response['message'] = $query ? 'Data sudah terhapus' : 'Data tidak terhapus';
			} else {
				$response['message'] = $query ? 'Data sudah dikembalikan' : 'Data gagal dikembalikan';
			}
		
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
