<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Readmoress extends Public_Controller {

	/**
	 * Total Rows
	 */
	public $total_rows = 0;

	/**
	 * Total Page
	 */
	public $total_pages = 0;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model(['public/m_announcement', 'public/m_post_comments', 'm_settings']);
		$this->total_rows = $this->m_post_comments->get_more_comments($this->uri->segment(2), 0)->num_rows();
		$this->total_pages = ceil($this->total_rows / $this->session->userdata('comment_per_page'));
	}
	
	/**
	 * Readmore
	 */
	public function index() {
		$this->load->helper(['text', 'form']);
		$id = (int) $this->uri->segment(2);
		if ($id !== 0 && ctype_digit((string) $id)) {
			$this->vars['query'] = $this->model->RowObject('announcement', 'id', $id);
			if (filter_var($this->vars['query']->is_deleted, FILTER_VALIDATE_BOOLEAN)) {
				redirect(base_url(), 'refresh');
			}
			if ($this->vars['query']->post_status == 'draft') {
				redirect(base_url(), 'refresh');
			}
			if ($this->vars['query']->post_visibility == 'private' && ! $this->auth->is_logged_in()) {
				redirect(base_url(), 'refresh');
			}
			$this->m_announcement->increase_viewer($id);
			$recaptcha = $this->m_settings->get_recaptcha();
			$this->vars['recaptcha_site_key'] = $recaptcha['recaptcha_site_key'];
			$this->vars['post_comments'] = $this->m_post_comments->get_post_comments($id);
			$this->vars['total_pages'] = $this->total_pages;
			$this->vars['page_title'] = $this->vars['query']->post_title;
			$this->vars['post_type'] = 'post';
			if ($this->vars['query']->post_type === 'page') {
				$this->vars['post_type'] = 'page';
			}
			$this->vars['post_author'] = $this->model->RowObject('users', 'id', $this->vars['query']->post_author)->user_full_name;
			$this->vars['content'] = 'themes/'.theme_folder().'/single-announcement';
			$this->load->view('themes/'.theme_folder().'/index', $this->vars);
		} else {
			show_404();
		}
	}
}