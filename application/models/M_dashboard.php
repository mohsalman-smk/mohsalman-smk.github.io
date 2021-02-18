<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_dashboard extends CI_Model {

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
	 * @return Resource
	 */
	public function widget_box() {
		$query = $this->db->query("
			SELECT (SELECT COUNT(*) FROM comments x1 WHERE x1.comment_type='message') AS messages
			, (SELECT COUNT(*) FROM comments x1 WHERE x1.comment_type='post') AS comments
			, (SELECT COUNT(*) FROM posts x1 WHERE x1.post_type='post') AS posts
			, (SELECT COUNT(*) FROM posts x1 WHERE x1.post_type='page') AS pages
			, (SELECT COUNT(*) FROM categories WHERE category_type='post') AS categories
			, (SELECT COUNT(*) FROM tags) AS tags
			, (SELECT COUNT(*) FROM links) AS links
			, (SELECT COUNT(*) FROM quotes) AS quotes
		");
		return $query->row();
	}
}