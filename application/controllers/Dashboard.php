<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Dashboard extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->model->clear_expired_data();
		$this->load->model([
			'm_dashboard',
			'm_users',
			'public/m_post_comments',
			'm_settings'
		]);
		$this->load->library('user_agent');
		$this->load->helper(['form', 'blog']);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Dashboard';
		$this->vars['dashboard'] = true;
		$this->vars['widget_box'] = $this->m_dashboard->widget_box();
		$this->vars['last_logged_in'] = $this->m_users->get_last_logged_in();
		$this->vars['recent_posts_comments'] = $this->m_post_comments->get_recent_comments();
		$recaptcha = $this->m_settings->get_recaptcha();
		$this->vars['recaptcha_site_key'] = $recaptcha['recaptcha_site_key'] ? TRUE : FALSE;
		$this->vars['recaptcha_secret_key'] = $recaptcha['recaptcha_secret_key'] ? TRUE : FALSE;
		$this->vars['google_map_api_key'] = (NULL !== $this->session->userdata('google_map_api_key') && $this->session->userdata('google_map_api_key')) ? TRUE : FALSE;
		$this->vars['latitude'] = (NULL !== $this->session->userdata('latitude') && $this->session->userdata('latitude')) ? TRUE : FALSE;
		$this->vars['longitude'] = (NULL !== $this->session->userdata('longitude') && $this->session->userdata('longitude')) ? TRUE : FALSE;
		$this->vars['sendgrid_api_key'] = $this->m_settings->get_sendgrid_api_key() ? TRUE : FALSE;
		$warning = FALSE;
		if (!$this->vars['recaptcha_site_key'] OR
			!$this->vars['recaptcha_secret_key'] OR
			!$this->vars['google_map_api_key'] OR
			!$this->vars['latitude'] OR
			!$this->vars['longitude'] OR
			!$this->vars['sendgrid_api_key']
			) {
			$warning = TRUE;
		}
		$this->vars['warning'] = $warning;
		$this->vars['content'] = 'backend/dashboard';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Sidebar Collapse
	 */
	public function sidebar_collapse() {
		$collapse = $this->session->userdata('sidebar_collapse') ? false : true;
		$this->session->set_userdata('sidebar_collapse', $collapse);
	}

	public function print_sessions() {
		echo '<pre>';
		print_r($this->session->userdata());
		echo '</pre>';
	}
}
