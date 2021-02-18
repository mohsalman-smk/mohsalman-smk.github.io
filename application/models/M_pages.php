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
	 * Get Data
	 * @param 	String
	 * @param 	Int
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_where($keyword = '', $limit = 10, $offset = 0) {
		$this->db->select('
			x1.id
			, x1.post_title
			, x2.user_full_name AS post_author
			, x1.created_at
			, x1.is_deleted
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'page');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.post_title', $keyword);
			$this->db->or_like('x2.user_full_name', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table. ' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'page');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.post_title', $keyword);
			$this->db->or_like('x2.user_full_name', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table. ' x1');
	}

	/**
	 * Get All Pages
	 * @access 	public
	 * @return 	Query
	 */
	public function get_pages() {
		return $this->db
			->select('id, post_title')
			->where('post_type', 'page')
			->where('is_deleted', 'false')
			->get(self::$table);
	}
}
