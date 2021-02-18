<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Under_construction extends CI_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$site_maintenance = $this->session->userdata('site_maintenance');
		if (NULL !== $site_maintenance && !filter_var($site_maintenance, FILTER_VALIDATE_BOOLEAN)) {
			redirect(base_url(), 'refresh');
		}
	}

	/**
	 * Index
	 * @access  public
	 */
	public function index() {
		$this->load->view('frontend/under_construction');
	}
}