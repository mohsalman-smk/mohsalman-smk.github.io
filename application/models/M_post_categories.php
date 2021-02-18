<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_post_categories extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'categories';

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
		$this->db->select('id, category_name, category_description, category_slug, is_deleted');
		$this->db->where('category_type', 'post');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('category_name', $keyword);
			$this->db->or_like('category_description', $keyword);
			$this->db->or_like('category_slug', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table, $limit, $offset);
	}

	/**
	 * Get All Post Categories
	 * @access  Public
	 * @return Resource
	 */
	public function get_post_categories($limit = 0) {
		$this->db->select('id, category_name, category_slug, category_description');
		$this->db->where('category_type', 'post');
		$this->db->where('is_deleted', 'false');
		if ($limit > 0) {
			$this->db->limit($limit);
		}
		return $this->db->get(self::$table);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->where('category_type', 'post');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('category_name', $keyword);
			$this->db->or_like('category_description', $keyword);
			$this->db->or_like('category_slug', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table);
	}

	/**
	 * Row Array
	 * @access Public 
	 * @return Array
	 */
	public function row_array() {
		$query = $this->get_post_categories();
		$data = [];
		foreach($query->result() as $row) {
			$data[$row->id] = $row->category_name;
		}
		return $data;
	}

	/**
	 * custom Save
	 * @param Array
	 * @return Int
	 */
	public function save($fill_data) {
		$query = $this->db->insert(self::$table, $fill_data);
		return $query ? $this->db->insert_id() : 0;
	}
}