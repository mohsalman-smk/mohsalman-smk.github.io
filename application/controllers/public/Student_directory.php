<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Student_directory extends Public_Controller {

	/**
	 * Total Rows
	 */
	public $total_rows = 0;

	/**
	 * Total Page
	 */
	public $total_pages = 0;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->model([
			'm_students', 
			'm_academic_years', 
			'm_class_groups', 
			'm_class_group_students'
		]);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['page_title'] = 'DIREKTORI ' . ($this->session->userdata('school_level') >= 5 ? 'MAHASISWA TAHUN AKADEMIK' : 'PESERTA DIDIK TAHUN PELAJARAN'). ' ' . $this->session->userdata('current_academic_year');
		$this->vars['ds_academic_years'] = $this->m_academic_years->dropdown();
		$this->vars['ds_class_groups'] = $this->m_class_groups->dropdown();
		$this->vars['content'] = 'themes/'.theme_folder().'/loop-students';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * Search Students
	 * @return	Json
	 */
	public function search_students() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			if ($this->validation()) {
				$academic_year_id = (int) $this->input->post('academic_year_id', true);
				$class_group_id = (int) $this->input->post('class_group_id', true);
				$query = $this->m_class_group_students->search_students($academic_year_id, $class_group_id);
				$rows = [];
				foreach($query->result() as $row) {
					$photo = 'no-image.jpg';
					if ($row->photo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/media_library/students/'.$row->photo)) {
						$photo = $row->photo;
					}
					$rows[] = [
						'identity_number' => $row->identity_number,
						'full_name' => $row->full_name,
						'gender' => $row->gender,
						'birth_place' => $row->birth_place,
						'birth_date' => indo_date($row->birth_date),
						'photo' => base_url('media_library/students/'.$photo)
					];
				}
				$response['rows'] = $rows;
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
	 * Validation Form
	 * @return Bool
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('academic_year_id', 'Tahun Pelajaran', 'trim|required|numeric');
		$val->set_rules('class_group_id', 'Kelas', 'trim|required|numeric');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * More Students
	 */
	public function more_students() {
		if ($this->input->is_ajax_request()) {
			$page_number = (int) $this->input->post('page_number', true);
			$offset = ($page_number - 1) * 20;
			$response = [];
			$query = $this->m_class_group_students->more_students($offset);
			$rows = [];
			foreach($query->result() as $row) {
				$photo = 'no-image.jpg';
				if ($row->photo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/media_library/students/'.$row->photo)) {
					$photo = $row->photo;
				}
				$rows[] = [
					'class_name' => $row->class_name,
					'identity_number' => $row->identity_number,
					'full_name' => $row->full_name,
					'gender' => $row->gender,
					'birth_place' => $row->birth_place,
					'birth_date' => indo_date($row->birth_date),
					'photo' => base_url('media_library/students/'.$photo)
				];
			}
			$response['rows'] = $rows;
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
