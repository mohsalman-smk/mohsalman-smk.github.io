<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_videos extends CI_Model {

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
	 * Recent Videos
	 * @access  Public
	 * @return 	String
	 */
	public function get_videos($limit = 6) {
		$this->db->select('id, post_title, post_content');
		$this->db->where('post_type', 'video');
		$this->db->where('is_deleted', 'false');
		$this->db->order_by('created_at', 'DESC');
		if ($limit > 0) {
			$this->db->limit($limit);
		}
		return $this->db->get(self::$table);
	}
}
