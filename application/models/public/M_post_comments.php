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
	 * Get Recent Comments
	 * @return Resource
	 */
	public function get_recent_comments($limit = 5) {
		return $this->db
			->select('x2.id, x1.comment_author, x1.comment_url, x1.comment_content, x2.id AS comment_post_id, x2.post_title, x2.post_slug, x1.created_at')
			->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT')
			->where('x1.comment_type', 'post')
			->where('x1.comment_status', 'approved')
			->where('x1.is_deleted', 'false')
			->order_by('x1.created_at', 'DESC')
			->limit($limit)
			->get(self::$table. ' x1');
	}

	/**
	 * Get Comments by Post ID
	 * @param 	int
	 * @return 	Query
	 */
	public function get_post_comments($id) {
		return $this->db
			->select('x1.id, x1.comment_author, x1.comment_url, LEFT(x1.created_at, 10) AS created_at, x1.comment_content')
			->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT')
			->where('x1.comment_type', 'post')
			->where('x1.comment_status', 'approved')
			->where('x1.is_deleted', 'false')
			->where('x1.comment_post_id', $id)
			->order_by('x1.created_at', 'DESC')
			->limit($this->session->userdata('comment_per_page'))
			->get(self::$table. ' x1');
	}

	/**
	 * More Comments by Post ID
	 * @param 	int
	 * @param 	int
	 * @return 	Query
	 */
	public function get_more_comments($comment_post_id = 0, $offset = 0) {
		$this->db->select('x1.id, x1.comment_author, x1.comment_url, LEFT(x1.created_at, 10) AS created_at, x1.comment_content');
		$this->db->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT');
		$this->db->where('x1.comment_type', 'post');
		$this->db->where('x1.comment_status', 'approved');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.comment_post_id', $comment_post_id);
		$this->db->order_by('x1.created_at', 'DESC');
		return $this->db->get(self::$table.' x1', (int) $this->session->userdata('comment_per_page'), $offset);
	}
}
