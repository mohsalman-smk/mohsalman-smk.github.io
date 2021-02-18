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
	 * Get Data
	 * @param 	String 
	 * @param 	Int
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_where($keyword = '', $limit = 10, $offset = 0) {
		$this->db->select('id, tag, slug, is_deleted');
		if (!empty($keyword)) {
			$this->db->like('tag', $keyword);
			$this->db->or_like('slug', $keyword);
		}
		return $this->db->get(self::$table, $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		if (!empty($keyword)) {
			$this->db->like('tag', $keyword);
			$this->db->or_like('slug', $keyword);
		}
		return $this->db->count_all_results(self::$table);
	}

	/**
	 * Create Tag from posts
	 * @param 	String
	 * @return 	Void
	 */
	public function create($str) {
		$tags = explode(',', $str);
		foreach ($tags as $tag) {
			$count = $this->db
				->where('tag', trim($tag))
				->count_all_results(self::$table);
			if ($count == 0 && trim($tag) != '') {
				$data = [
					'tag' => trim($tag),
					'slug' => url_title(trim($tag))
				];
				$this->db->insert(self::$table, $data);
			}
		}
	}
}