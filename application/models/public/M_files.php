<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_files extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'files';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Files
	 * @param String
	 * @param Int
	 * @return Resource
	 */
	public function get_files($slug = '', $offset = 0) {
		$this->db->select('
			x1.id
			, x1.file_title
			, x1.file_name
			, x1.file_ext
			, x1.file_size
			, x2.category_name
			, x1.file_counter
			, x1.file_visibility
		');
		$this->db->join('categories x2', 'x1.file_category_id = x2.id',  'LEFT');
		$this->db->where('x1.is_deleted', 'false');
		if (!empty($slug)) {
			$this->db->where('x2.category_slug', $slug);
		}
		if (! $this->auth->is_logged_in()) {
			$this->db->where('x1.file_visibility', 'public');
		}
		return $this->db->get(self::$table.' x1', 20, $offset);
	}

	/**
	 * Total Files
	 * @param String
	 * @return Int
	 */
	public function total_rows($slug = '') {
		$this->db->join('categories x2', 'x1.file_category_id = x2.id', 'LEFT');
		$this->db->where('x1.is_deleted', 'false');
		if (!empty($slug)) {
			$this->db->where('x2.category_slug', $slug);
		}
		if (! $this->auth->is_logged_in()) {
			$this->db->where('x1.file_visibility', 'public');
		}
		return $this->db->count_all_results(self::$table.' x1');
	}
}
