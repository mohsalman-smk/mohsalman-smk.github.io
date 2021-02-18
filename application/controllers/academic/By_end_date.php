<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class By_end_date extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_students');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'GRAFIK ' . strtoupper($this->session->userdata('_student')) .' BERDASARKAN TAHUN LULUS';
		$this->vars['academic'] = $this->vars['academic_chart'] = $this->vars['by_end_date'] = true;
		$labels = [];
		$data = [];
		$query = $this->m_students->chart_by_end_date();
		foreach($query->result() as $row) {
			array_push($labels, $row->labels);
			array_push($data, $row->data);
		}
		$this->vars['labels'] = json_encode($labels);
		$this->vars['data'] = json_encode($data);
		$this->vars['content'] = 'students/by_end_date';
		$this->load->view('backend/index', $this->vars);
	}
}
