<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Form_settings extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_form_settings');
		$this->pk = M_form_settings::$pk;
		$this->table = M_form_settings::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['admission'] = $this->vars['admission_settings'] = $this->vars['form_settings'] = true;
		$this->vars['query'] = $this->m_form_settings->get_all();
		$this->vars['content'] = 'admission/form_settings';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Save or Update
	 * @return void
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$settings = json_decode($this->input->post('field_setting'));
			$success = 0; $error = 0;
			foreach ($settings as $values) {
				$fill_data = [];
				$field_setting = (array) $values;
				$id = $field_setting['id'];
				unset($field_setting['id']);
				$fill_data['field_setting'] = json_encode($field_setting);
				$fill_data['updated_at'] = date('Y-m-d H:i:s');
				$fill_data['updated_by'] = $this->session->userdata('id');
				$this->model->update($id, $this->table, $fill_data) ? $success++ : $error++;
			}
			$response['message'] = $success . ' record berhasil diperbaharui'. ($error > 0 ? ', dan '.$error . ' record gagal diperbaharui.' : '');
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
