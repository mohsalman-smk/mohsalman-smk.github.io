<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_posts extends CI_Model {

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
		$this->db->select("
			x1.id
			, x1.post_title
			, x2.user_full_name AS post_author
			, x1.post_status
			, x1.created_at
			, x1.is_deleted
		");
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		if ($this->session->userdata('user_type') == 'student' || $this->session->userdata('user_type') == 'employee') {
			$this->db->where('x1.post_author', $this->session->userdata('id'));
		}
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.post_title', $keyword);
			$this->db->or_like('x2.user_full_name', $keyword);
			$this->db->or_like('x1.post_status', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
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
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		if ($this->session->userdata('user_type') == 'student' || $this->session->userdata('user_type') == 'employee') {
			$this->db->where('x1.post_author', $this->session->userdata('id'));
		}
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.post_title', $keyword);
			$this->db->or_like('x2.user_full_name', $keyword);
			$this->db->or_like('x1.post_status', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results('posts x1');
	}

	/**
	 * Get Posts for RSS Feed
	 * @return Resource
	 */
	public function feed() {
		return $this->db
			->select('id, post_title, post_content, post_slug, LEFT(created_at, 10) AS created_at')
			->where('post_type', 'post')
			->where('post_status', 'publish')
			->where('is_deleted', 'false')
			->limit((int) $this->session->userdata('post_rss_count'))
			->get(self::$table);
	}

	/**
	 * Get Archives
	 * @access  Public
	 * @param Int
	 * @return Resource
	 */
	public function get_archives($year) {
		$this->db->select("SUBSTR(x1.created_at, 6, 2) as `code`, MONTHNAME(x1.created_at) AS `month`, COUNT(*) AS `count`");
		$this->db->where('YEAR(x1.created_at)', $year);
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		if (!$this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->group_by("1,2");
		$this->db->order_by('1', 'ASC');
		return $this->db->get('posts x1');
	}

	/**
	 * Get Recent Posts
	 * @access  Public
	 * @param Int
	 * @return Resource
	 */
	public function get_recent_posts($limit = 6) {
		$this->db->select('
			x1.id
		  , x1.post_title
		  , LEFT(x1.created_at, 10) AS created_at
		  , x1.post_content
		  , x1.post_image
		  , x1.post_slug
		  , x1.post_counter
		  , x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		if (!$this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->order_by('x1.created_at', 'DESC');
		$this->db->limit($limit);
		return $this->db->get('posts x1');
	}

	/**
	 * Get Popular Posts
	 * @access  Public
	 * @param Int
	 * @return Resource
	 */
	public function get_popular_posts($limit = 6) {
		$this->db->select('
			x1.id
		  , x1.post_title
		  , LEFT(x1.created_at, 10) AS created_at
		  , x1.post_content
		  , x1.post_image
		  , x1.post_slug
		  , x1.post_counter
		  , x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		if (!$this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->order_by('x1.post_counter', 'DESC');
		$this->db->limit($limit);
		return $this->db->get('posts x1');
	}

	/**
	 * Get Most Commented
	 * @access  Public
	 * @param Int
	 * @return Resource
	 */
	public function get_most_commented($limit = 6) {
		$this->db->select('
			x1.id
		  , x1.post_title
		  , LEFT(x1.created_at, 10) AS created_at
		  , x1.post_content
		  , x1.post_image
		  , x1.post_slug
		  , x1.post_counter
		  , x2.user_full_name AS post_author
		  , COUNT(x3.id) AS total_comment
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->join('comments x3', 'x1.id = x3.comment_post_id AND x3.comment_type = "post"', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		if (!$this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->group_by([1,2,3,4,5,6,7,8]);
		$this->db->order_by('9', 'DESC');
		$this->db->limit($limit);
		$this->db->having('COUNT(x3.id) > 0');
		return $this->db->get('posts x1');
	}

	/**
	 * Get recent added posts / for dashboard
	 * @access  Public
	 * @return Resource
	 */
	public function get_recent_added_posts() {
		$this->db->select('x1.post_title, x2.user_full_name AS author, LEFT(x1.created_at, 10) AS created_at');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.post_status', 'publish');
		if (!$this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->order_by('x1.created_at', 'DESC');
		$this->db->limit(5);
		return $this->db->get(self::$table.' x1');
	}

	/**
	 * Get Random Posts
	 * @access  Public
	 * @return Resource
	 */
	public function get_random_posts($limit = 5) {
		$this->db->select('
			x1.id
		  , x1.post_title
		  , LEFT(x1.created_at, 10) AS created_at
		  , x1.post_content
		  , x1.post_image
		  , x1.post_slug
		  , x1.post_counter
		  , x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		if (!$this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->order_by('RAND()');
		$this->db->limit($limit);
		return $this->db->get(self::$table .' x1');
	}

	/**
	 * Get Related Posts
	 * @param 	String
	 * @param 	Int
	 * @access 	public
	 * @return 	Query
	 */
	public function get_related_posts($categories = '', $id) {
		$categories = explode(',', $categories);
		$this->db->select('id, post_title, post_content, LEFT(created_at, 10) AS created_at, post_image, post_slug, post_counter');
		$this->db->where('post_type', 'post');
		$this->db->where('is_deleted', 'false');
		$this->db->where('id !=', $id);
		if (! $this->auth->is_logged_in()) {
			$this->db->where('post_visibility', 'public');
		}
		$no = 0;
		$this->db->group_start();
		foreach ($categories as $category) {
			if ($no == 0) {
				$this->db->like('post_categories', $category);
			} else {
				$this->db->or_like('post_categories', $category);
			}
			$no++;
		}
		$this->db->group_end();
		$this->db->order_by('LEFT(created_at, 10) DESC');
		$this->db->limit((int) $this->session->userdata('post_related_count'));
		return $this->db->get(self::$table);
	}

	/**
	 * Get Year From Posted Date
	 * @access 	public
	 * @return 	Resource
	 */
	public function get_archive_year() {
		$this->db->select('LEFT(created_at, 4) as year');
		$this->db->where('post_type', 'post');
		$this->db->where('is_deleted', 'false');
		$this->db->where('post_status', 'publish');
		if (! $this->auth->is_logged_in()) {
			$this->db->where('post_visibility', 'public');
		}
		$this->db->group_by('1');
		$this->db->order_by('1', 'DESC');
		return $this->db->get(self::$table);
	}

	/**
	 * Get Related Posts
	 * @param 	Int
	 * @access 	public
	 * @return 	Resource
	 */
	public function get_archive_posts($year, $month) {
		$this->db->select('
			x1.id
			, x1.post_title
			, x2.user_full_name AS author
			, x1.post_content
			, LEFT(x1.created_at, 10) AS created_at
			, x1.post_categories
			, x1.post_image
			, x1.post_slug
			, x1.post_counter
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.post_status', 'publish');
		$this->db->where('LEFT(x1.created_at, 4)=', $year)	;
		$this->db->where('SUBSTRING(x1.created_at, 6, 2)=', $month);
		if (! $this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->order_by('x1.created_at', 'DESC');
		return $this->db->get(self::$table.' x1');
	}

	/**
	 * Get post category
	 * @param 	Int
	 * @param 	Int
	 * @access 	public
	 * @return 	Resource
	 */
	public function get_post_category($id, $limit= 0) {
		$this->db->select('x1.id, x1.post_title, x2.user_full_name AS author, x1.post_content, LEFT(x1.created_at, 10) AS created_at, x1.post_image, x1.post_slug, x1.post_counter');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.post_status', 'publish');
		if (! $this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->like('x1.post_categories', $id);
		$this->db->order_by('x1.created_at', 'DESC');
		if ($limit > 0) {
			$this->db->limit($limit);
		}
		return $this->db->get(self::$table.' x1');
	}

	/**
	 * Get post by tag
	 * @param 	String
	 * @param 	Int
	 * @access 	public
	 * @return 	Resource
	 */
	public function get_post_by_tag($tag, $limit= 0) {
		$this->db->select('x1.id, x1.post_title, x2.user_full_name AS author, x1.post_content, LEFT(x1.created_at, 10) AS created_at, x1.post_image, x1.post_slug, x1.post_counter');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.post_status', 'publish');
		$this->db->like('x1.post_tags', $tag)	;
		$this->db->order_by('x1.created_at', 'DESC');
		if (! $this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		if ($limit > 0) {
			$this->db->limit($limit);
		}
		return $this->db->get(self::$table.' x1');
	}

	/**
	 * increase_viewer
	 * @param Int
	 * @return Void
	 */
	public function increase_viewer($id) {
		$query = $this->model->RowObject(self::$table, self::$pk, $id);
		$this->db->where(self::$pk, $id)->update(self::$table, ['post_counter' => ($query->post_counter + 1)]);
	}

	/**
	 * Search
	 * @param String
	 * @return Resource
	 */
	public function search($keyword) {
		$this->db->select('x1.id, x1.post_title, x2.user_full_name AS author, x1.post_content, LEFT(x1.created_at, 10) AS created_at, x1.post_slug, x1.post_counter');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_status', 'publish');
		if (! $this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where_in('x1.post_type', ['post', 'page']);
		$this->db->group_start();
		$this->db->like('LOWER(x1.post_title)', strtolower($keyword));
		$this->db->group_end();
		$this->db->limit(20);
		return $this->db->get(self::$table .' x1');
	}

	/**
	 * more_posts
	 * @param String
	 * @param Int
	 * @return Resource
	 */
	public function more_posts($category_slug = '', $offset = 0) {
		$id = 0;
		$query = $this->db
			->select('id')
			->where('category_slug', $category_slug)
			->where('category_type', 'post')
			->limit(1)
			->get('categories');
		if ($query->num_rows() == 1) {
			$res = $query->row();
			$id = $res->id;
		}
		$this->db->select('x1.id, x1.post_title, x1.post_content, LEFT(x1.created_at, 10) AS created_at, x1.post_image, x1.post_slug, x1.post_counter');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.post_status', 'publish');
		if (! $this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->group_start();
		$this->db->like('x1.post_categories', $id);
		$this->db->group_end();
		return $this->db->get(self::$table.' x1', 6, $offset);
	}

	/**
	 * Welcome | Sambutan Kepala Sekolah
	 * @access  Public
	 * @return String
	 */
	public function get_welcome() {
		$query = $this->db
			->select('post_content')
			->where('post_type', 'welcome')
			->limit(1)
			->get(self::$table);
		if ($query->num_rows() === 1) {
			$result = $query->row();
			return $result->post_content;
		}
		return '';
	}

	/**
	 * More Archive Posts
	 * @param 	Int
	 * @param 	Int
	 * @param 	Int
	 * @access 	public
	 * @return 	Resource
	 */
	public function more_archive_posts($offset = 0, $year, $month) {
		$this->db->select('id, post_title, post_content, LEFT(created_at, 10) AS created_at, post_image, post_slug');
		$this->db->where('post_type', 'post');
		$this->db->where('is_deleted', 'false');
		$this->db->where('post_status', 'publish');
		$this->db->where('LEFT(created_at, 4)=', $year)	;
		$this->db->where('SUBSTRING(created_at, 6, 2)=', $month);
		if (! $this->auth->is_logged_in()) {
			$this->db->where('post_visibility', 'public');
		}
		return $this->db->get(self::$table, 6, $offset);
	}

	/**
	 * More Posts by Tags
	 * @param String
	 * @param Int
	 * @return Resource
	 */
	public function more_posts_by_tag($tag = '', $offset = 0) {
		$this->db->select('x1.id, x1.post_title, x1.post_content, LEFT(x1.created_at, 10) AS created_at, x1.post_image, x1.post_slug, x1.post_counter');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.post_status', 'publish');
		if (! $this->auth->is_logged_in()) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->group_start();
		$this->db->like('x1.post_tags', $tag);
		$this->db->group_end();
		return $this->db->get(self::$table.' x1', 6, $offset);
	}

	/**
	 * Update Sambutan Kepala Sekolah
	 * @param 	Array
	 * @access 	Public
	 * @return 	Bool
	 */
	public function welcome_update($fill_data = []) {
		$count = $this->db->where('post_type', 'welcome')->count_all_results(self::$table);
		if ($count === 0) {
			return $this->db->insert(self::$table, $fill_data);
		}
		return $this->db->where('post_type', 'welcome')->update(self::$table, $fill_data);
	}
}
