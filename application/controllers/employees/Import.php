<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Import extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_employees');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'IMPORT GURU DAN TENAGA KEPENDIDIKAN';
		$this->vars['employees'] = $this->vars['import_employees'] = true;
		$this->vars['content'] = 'employees/import';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Save
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$rows = explode("\n", $this->input->post('employees'));
			$success = $failed = $exist = 0;
			foreach($rows as $row) {
				$exp = explode("\t", $row);
				if (count($exp) != 6) continue;
				$fill_data = [];
				$fill_data['nik'] = trim($exp[0]);
				$fill_data['full_name'] = trim($exp[1]);
				$fill_data['gender'] = trim($exp[2]) == 'L' ? 'M' : 'F';
				$fill_data['street_address'] = trim($exp[3]);
				$fill_data['birth_place'] = trim($exp[4]);
				$fill_data['birth_date'] = trim($exp[5]);
				$fill_data['email'] = trim($exp[0]).'@'.str_replace(['http://www.', 'https://www.', 'http://', 'https://'], '', rtrim($this->session->userdata('website'), '/'));
				$query = $this->model->isValExist('nik', trim($exp[0]), 'employees');
				if (!$query) {
					$this->model->insert('employees', $fill_data) ? $success++ : $failed++;
				} else {
					$exist++;
				}
			}
			$response = [];
			$response['type'] = 'info';
			$response['message'] = 'Success : ' . $success. ' rows, Failed : '. $failed .', Exist : ' . $exist;
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}