<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Search extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper(['text']);
		$this->load->model([
			'm_posts',
			'm_pages'
		]);
	}

	/**
	 * Index
	 * @access  public
	 */
	public function index() {
		if ($_POST) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('keyword', 'Kata Kunci Pencarian', 'trim|required|alpha_numeric_spaces|max_length[100]');
			$this->vars['posts'] = $this->vars['pages'] = FALSE;
			if ($this->form_validation->run() == FALSE) {
				$this->session->unset_userdata('keyword');
				$this->vars['query'] = FALSE;
				$this->vars['title'] = validation_errors();
			} else {
				$keyword = trim(strip_tags($this->input->post('keyword', true)));
				$this->session->set_userdata('keyword', $keyword);
				$this->vars['title'] = 'Hasil pencarian dengan kata kunci "'.$this->session->userdata('keyword').'"';
				$this->vars['query'] = $this->m_posts->search($keyword);
			}
			$this->vars['content'] = 'themes/'.theme_folder().'/search-results';
			$this->load->view('themes/'.theme_folder().'/index', $this->vars);
		} else {
			redirect(base_url());
		}
	}
}
