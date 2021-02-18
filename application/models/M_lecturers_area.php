<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_lecturers_area extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'lecturers_area';

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
		$this->db->select('id, title, caption, url, email, instagram, twitter, facebook, image, is_deleted');
		if (!empty($keyword)) {
			$this->db->like('title', $keyword);
			$this->db->like('caption', $keyword);
			$this->db->like('url', $keyword);
			$this->db->like('email', $keyword);
			$this->db->like('instagram', $keyword);
			$this->db->like('twitter', $keyword);
			$this->db->like('facebook', $keyword);
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
			$this->db->like('title', $keyword);
			$this->db->like('caption', $keyword);
			$this->db->like('url', $keyword);
			$this->db->like('email', $keyword);
			$this->db->like('instagram', $keyword);
			$this->db->like('twitter', $keyword);
			$this->db->like('facebook', $keyword);
		}
		return $this->db->count_all_results(self::$table);
	}
}