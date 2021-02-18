<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_tags extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'tags';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get All Tags
	 * @access  Public
	 * @param  	Int
	 * @param  	Boolean
	 * @return 	Resource
	 */
	public function get_tags($limit = 0, $random = FALSE) {
		$this->db->select('id, tag, slug');
		$this->db->where('is_deleted', 'false');
		if ($limit > 0) {
			$this->db->limit($limit);
		}
		if ($random) {
			$this->db->order_by('id','RANDOM');
		}
		return $this->db->get(self::$table);
	}
}