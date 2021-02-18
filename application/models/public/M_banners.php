<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_banners extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'links';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get All Banners
	 * @access  Public
	 * @return 	Resource
	 */
	public function get_banners() {
		return $this->db
			->select("id
				, link_title
				, link_url
				, link_target
				, link_image
			")
			->where('link_type', 'banner')
			->where('is_deleted', 'false')
			->get(self::$table);
	}
}