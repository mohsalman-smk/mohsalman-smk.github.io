<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class By_student_status extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_students', 
			'm_academic_years'
		]);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'GRAFIK ' . strtoupper($this->session->userdata('_student')) .' BERDASARKAN STATUS '. strtoupper($this->session->userdata('_student'));
		$this->vars['academic'] = $this->vars['academic_chart'] = $this->vars['by_student_status'] = true;
		$this->vars['ds_academic_year'] = $this->m_academic_years->dropdown();
		$this->vars['content'] = 'students/by_student_status';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Generate Chart
	 */
	public function generate_chart() {
		if ($this->input->is_ajax_request()) {
			$academic_year_id = (int) $this->input->post('academic_year_id');
			$query = $this->m_students->chart_by_student_status($academic_year_id);
			$response = [];
			$response['title'] = 'GRAFIK ' . strtoupper($this->session->userdata('_student')) . ' BERDASARKAN STATUS PESERTA DIDIK ' . strtoupper($this->session->userdata('_academic_year'));
			$response['labels'] = [];
			$response['data'] = [];
			foreach($query->result() as $row) {
				array_push($response['labels'], $row->labels);
				array_push($response['data'], $row->data);
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
