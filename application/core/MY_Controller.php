<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */
	
class MY_Controller extends CI_Controller {

	/**
	 * General Variable
	 * @var Array
	 */
	protected $vars = [];
	
	public function __construct() {
		parent::__construct();
		$timezone = NULL !== $this->session->userdata('timezone') ? $this->session->userdata('timezone') : 'Asia/Jakarta';
		date_default_timezone_set($timezone);
	}
}

require_once(APPPATH.'/core/Public_Controller.php');
require_once(APPPATH.'/core/Admin_Controller.php');
require_once(APPPATH.'/core/Blog_Controller.php');