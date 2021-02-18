<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_pages extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'posts';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Another Pages
	 * @param 	Int
	 * @access 	public
	 * @return 	Query
	 */
	public function get_another_pages($id) {
		return $this->db
			->select('id, post_title, post_slug')
			->where('post_type', 'page')
			->where('is_deleted', 'false')
			->where(self::$pk.' <>', $id)
			->limit(10)
			->get(self::$table);
	}
}
