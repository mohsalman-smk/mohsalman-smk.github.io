<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Feed extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('public/m_posts');
		$this->load->helper(['xml', 'text']);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['feed_name'] = $this->session->userdata('website');
		$this->vars['encoding'] = 'utf-8';
		$this->vars['feed_url'] = base_url().'feed';
		$this->vars['page_description'] = $this->session->userdata('meta_description');
		$this->vars['page_language'] = 'en-en';
		$this->vars['creator_email'] = $this->session->userdata('email');
		$this->vars['posts'] = $this->m_posts->feed();
		header('Content-Type: text/xml; charset=utf-8', true);
		$this->load->view('frontend/feed', $this->vars);
	}
}
