<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Alumni_directory extends Public_Controller {

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
		$this->load->model('public/m_alumni');
		$this->total_rows = $this->m_alumni->total_rows();
		$this->total_pages = ceil($this->total_rows / 20);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['page_title'] = 'DIREKTORI ALUMNI';
		$this->vars['total_pages'] = $this->total_pages;
		$this->vars['query'] = $this->m_alumni->get_alumni();
		$this->vars['content'] = 'themes/'.theme_folder().'/loop-alumni';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * More Files
	 */
	public function more_alumni() {
		if ($this->input->is_ajax_request()) {
			$page_number = (int) $this->input->post('page_number', true);
			$offset = ($page_number - 1) * 20;
			$response = [];
			$query = $this->m_alumni->get_alumni($offset);
			$rows = [];
			foreach($query->result() as $row) {
				$photo = 'no-image.jpg';
				if ($row->photo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/media_library/students/'.$row->photo)) {
					$photo = $row->photo;
				}
				$rows[] = [
					'identity_number' => $row->identity_number,
					'full_name' => $row->full_name,
					'gender' => $row->gender,
					'birth_place' => $row->birth_place,
					'birth_date' => indo_date($row->birth_date),
					'start_date' => $row->start_date,
					'end_date' => $row->end_date,
					'photo' => base_url('media_library/students/'.$photo)
				];
			}
			$response['rows'] = $rows;
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT))
				->_display();
			exit;
		}
	}
}
