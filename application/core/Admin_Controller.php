<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Admin_Controller extends MY_Controller {

	/**
	 * Primary key
	 * @var string
	 */
	protected $pk;

	/**
	 * Table
	 * @var string
	 */
	protected $table;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();

		// Restrict
		$this->auth->restrict();
		
		// Check privileges Users
		if (!in_array($this->uri->segment(1), $this->session->userdata('user_privileges'))) {
			redirect(base_url());
			return;
		}

		// $this->output->enable_profiler();
	}

	/**
	 * deleted data | SET is_deleted to true
	 */
	public function delete() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$response['action'] = 'delete';
			$response['type'] = 'warning';
			$response['message'] = 'not_selected';
			$ids = explode(',', $this->input->post($this->pk));
			if (count($ids) > 0) {
				if($this->model->delete($ids, $this->table)) {
					$response = [
						'type' => 'success',
						'message' => 'deleted',
						'id' => $ids
					];
				} else {
					$response = [
						'type' => 'error',
						'message' => 'not_deleted'
					];
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
	 * Restored data | SET is_deleted to false
	 */
	public function restore() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$response['action'] = 'restore';
			$response['type'] = 'warning';
			$response['message'] = 'not_selected';
			$ids = explode(',', $this->input->post($this->pk));
			if (count($ids) > 0) {
				if($this->model->restore($ids, $this->table)) {
					$response = [
						'type' => 'success',
						'message' => 'restored',					
						'id' => $ids
					];
				} else {
					$response = [
						'type' => 'error',
						'message' => 'not_restored'
					];
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
	 * Email Check
	 * @param 	string
	 * @param 	int
	 * @return Bool
	 */
	public function email_check($str, $id) {
		$query = $this->model->is_email_exist($str, $id);
		if ($query['is_exist'] === TRUE) {
			$this->form_validation->set_message('email_check', 'Email sudah digunakan oleh ' . $query['used_by'] . '. Silahkan gunakan email lain');
			return FALSE;
		}
		return TRUE;
	}
}