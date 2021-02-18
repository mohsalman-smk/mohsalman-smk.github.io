<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Post_tags extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('public/m_posts');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$tag = $this->uri->segment(2);
		if (alpha_dash($tag)) {
			$this->vars['title'] = '#' . ucwords(str_replace('-', ' ', $tag));
			$total_rows = $this->m_posts->more_posts_by_tag($tag, 0)->num_rows();
			$this->vars['total_rows'] = $total_rows;
			$this->vars['total_page'] = ceil($total_rows / 6);
			$this->vars['query'] = $this->m_posts->more_posts_by_tag($tag);
			$this->vars['content'] = 'themes/'.theme_folder().'/loop-posts';
			$this->load->view('themes/'.theme_folder().'/index', $this->vars);
		} else {
			show_404();
		}
	}

	/**
	 * More Posts
	 */
	public function more_posts() {
		if ($this->input->is_ajax_request()) {
			$tag = $this->input->post('tag', true);
			$page_number = (int) $this->input->post('page_number', true);
			$offset = ($page_number - 1) * 6;
			$response = [];
			if (alpha_dash($tag)) {
				$query = $this->m_posts->more_posts_by_tag($tag, $offset);
				$total_rows = $this->m_posts->more_posts_by_tag($tag, 0)->num_rows();
				$rows = [];
				foreach($query->result() as $row) {
					$rows[] = [
						'id' => $row->id,
						'created_at' => day_name(date('N', strtotime($row->created_at))).', '.indo_date($row->created_at),
						'post_title' => $row->post_title,
						'post_image' => $row->post_image,
						'post_slug' => $row->post_slug,
						'post_content' => $row->post_content
					];
				}
				$response['rows'] = $rows;
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}