<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Public_Controller extends MY_Controller {

	/**
	 * General Variable
	 * @var Array
	 */
	protected $vars = [];
	
	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		
		// Load Text Helper
		$this->load->helper(['text', 'blog_helper']);

		// Load Token Library
		$this->load->library('token');
		
		// CSRF Token
		$session_data['csrf_token'] = $this->token->get_token();

		// set session data
		$this->session->set_userdata($session_data);
		
		// redirect if under construction
		if ($this->session->userdata('site_maintenance') == 'true' && 
			$this->session->userdata('site_maintenance_end_date') >= date('Y-m-d') && 
			$this->uri->segment(1) !== 'login') {
			redirect('under-construction');
		}

		//  cache file
		if ($this->session->userdata('site_cache') == 'true' && (int) $this->session->userdata('site_cache_time') > 0) {
			$this->output->cache($this->session->userdata('site_cache_time'));
		}

		// Load Top Menus
		$this->load->model('m_menus');
		$this->vars['menus'] = $this->m_menus->get_parent_menu();
	}
}