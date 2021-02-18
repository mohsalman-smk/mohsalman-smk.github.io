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
	 * Get Data
	 * @param 	String 
	 * @param 	Int
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_where($keyword = '', $limit = 10, $offset = 0) {
		$this->db->select('id, link_url, link_title, link_target, link_image, is_deleted');
		$this->db->where('link_type', 'banner');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('link_url', $keyword);
			$this->db->or_like('link_title', $keyword);
			$this->db->or_like('link_target', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table, $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->where('link_type', 'banner');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('link_url', $keyword);
			$this->db->or_like('link_title', $keyword);
			$this->db->or_like('link_target', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table);
	}
}