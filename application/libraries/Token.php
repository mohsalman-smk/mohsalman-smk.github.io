<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Token {

	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var object
	 */
	protected $CI;

	/**
	 * @var token
	 * @access private
	 */
	private $token;
     
	/**
	 * @var old token
	 * @access private
	 */
	private $old_token;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		$this->CI = &get_instance();
		if (NULL !== $this->CI->session->userdata('token')) {
			$this->old_token = $this->CI->session->userdata('token');
		}
	}

	/**
	 * Set Token
	 * @access private
	 * @return string
	 */
	private function set_token() {
		$ip = $_SERVER['REMOTE_ADDR'];
		$uniqid = uniqid(mt_rand(), true);
		return md5($ip . $uniqid);
	}

	/**
	 * Get Token
	 * @access public
	 * @return string
	 */
	public function get_token() {
		$this->token = $this->set_token();
		$this->CI->session->set_userdata('token', $this->token);
		return $this->token;
	}

	/**
	 * Token validated
	 * @access public
	 * @return bool
	 */
	public function  is_valid_token($token) {
		return $token === $this->old_token;
	}
}