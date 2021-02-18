<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Blank_admission_form extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('admission');
		$this->load->model(['m_admission_types', 'm_options']);
	}
	
	/**
	 * Index
	 * @access  public
	 */
	public function index() {
		$admission_types = [];
		foreach($this->m_admission_types->dropdown() as $key => $value) {
			array_push($admission_types, $value);
		}
		$religions = [];
		foreach ($this->m_options->get_options('religion') as $key => $value) {
			array_push($religions, $value);
		}
		$special_needs = [];
		foreach ($this->m_options->get_options('special_needs') as $key => $value) {
			array_push($special_needs, $value);
		}
		$this->admission->blank_pdf([
			'admission_types' => $admission_types,
			'religions' => $religions,
			'special_needs' => $special_needs
		]);
	}
}