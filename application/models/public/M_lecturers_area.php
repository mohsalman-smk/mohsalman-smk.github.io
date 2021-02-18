<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_lecturers_area extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'lecturers_area';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get All Image Sliders
	 * @access  Public
	 * @return Resource
	 */
	public function get_lecturers_area() {
		return $this->db
			->select('id, title, caption, url, email, instagram, twitter, facebook, image')
			->where('is_deleted', 'false')
			->get(self::$table);
	}
}