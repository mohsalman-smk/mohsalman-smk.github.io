<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Gallery_videos extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('public/m_videos');
	}
	
	/**
	 * Index
	 */
	public function index() {
		$this->vars['query'] = $this->m_videos->get_videos(0);
		$this->vars['content'] = 'themes/'.theme_folder().'/loop-videos';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}
}