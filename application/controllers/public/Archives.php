<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Archives extends Public_Controller {

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
		$year = substr($this->uri->segment(2), 0, 4);
		$month = substr($this->uri->segment(3), 0, 2);
		if ($year && $month) {
			$this->vars['title'] = 'ARSIP BULAN ' . bulan($month).' '.$year;
			$total_rows = $this->m_posts->more_archive_posts(0, $year, $month)->num_rows();
			$this->vars['total_page'] = ceil($total_rows / 6);
			$this->vars['query'] = $this->m_posts->more_archive_posts(0, $year, $month);
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
			$year = substr($this->input->post('year', true), 0, 4);
			$month = substr($this->input->post('month', true), 0, 2);
			$page_number = (int) $this->input->post('page_number', true);
			$offset = ($page_number - 1) * 6;
			$query = $this->m_posts->more_archive_posts($offset, $year, $month);
			$total_rows = $this->m_posts->more_archive_posts(0, $year, $month)->num_rows();
			$rows = [];
			foreach($query->result() as $row) {
				$rows[] = [
					'id' => $row->id,
					'posted_date' => day_name(date('N', strtotime($row->created_at))).', '.indo_date($row->created_at),
					'created_at' => $row->created_at,
					'post_title' => $row->post_title,
					'post_image' => $row->post_image,
					'post_slug' => $row->post_slug,
					'post_content' => $row->post_content
				];
			}
			$response = [
				'rows' => $rows,
				'total_page' => ceil($total_rows / 6)
			];

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
