<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
  * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_post_comments extends CI_Model {

	/**
	 * Primary key
	 *
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'comments';

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
			, x1.comment_author
			, x1.comment_email
			, x1.created_at
			, x1.comment_content
			, x1.comment_status
			, x2.post_title
			, x2.id AS comment_post_id
			, x2.post_slug
			, x1.is_deleted'
		);
		$this->db->where('x1.comment_type', 'post');
		$this->db->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.comment_author', $keyword);
			$this->db->or_like('x1.comment_email', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
			$this->db->or_like('x1.comment_content', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table.' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->where('comment_type', 'post');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('comment_author', $keyword);
			$this->db->or_like('comment_email', $keyword);
			$this->db->or_like('created_at', $keyword);
			$this->db->or_like('comment_content', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table);
	}
}
