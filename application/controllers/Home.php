<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Home extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = $this->session->userdata('website') . ' | ' . $this->session->userdata('tagline');
		$this->vars['content'] = 'themes/'.theme_folder().'/home';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}
}
