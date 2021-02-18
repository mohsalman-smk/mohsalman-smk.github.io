<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Gallery_photos extends Public_Controller {

	/**
	 * Total Rows
	 */
	private $total_rows = 0;

	/**
	 * Total Page
	 */
	private $total_pages = 0;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('public/m_albums');
		$this->total_rows = $this->m_albums->total_rows();
		$this->total_pages = ceil($this->total_rows / 6);
	}

	/**
	 * Index
	 */
	public function index() {
		$this->vars['total_pages'] = $this->total_pages;
		$this->vars['query'] = $this->m_albums->get_albums( 6 );
		$this->vars['content'] = 'themes/'.theme_folder().'/loop-albums';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * More Photos
	 */
	public function more_photos() {
		if ($this->input->is_ajax_request()) {
			$page_number = (int) $this->input->post('page_number', true);
			$offset = ($page_number - 1) * 6;
			$query = $this->m_albums->get_albums(6, $offset);
			$rows = [];
			foreach($query->result() as $row) {
				$rows[] = $row;
			}
			$response = [
				'rows' => $rows,
				'total_rows' => $this->total_rows
			];

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}

	/**
	 * List Images
	 * @return Object
	 */
	public function preview() {
		if ($this->input->is_ajax_request()) {
			$response = [];
			$id = $this->input->post('id');
			if ($id !== 0 && ctype_digit((string) $id)) {
				$this->load->model('m_photos');
				$query = $this->m_photos->get_image_by_album_id($id);
				$items = [];
				foreach($query->result() as $row) {
					$items[] = [
						'src' => base_url('media_library/albums/large/'.$row->photo_name)
					];
				}
				$response = [
					'count' => count($items),
					'items' => $items
				];
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
