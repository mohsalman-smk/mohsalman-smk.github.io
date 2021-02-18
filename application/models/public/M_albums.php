<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_albums extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'albums';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Albums
	 * @return 	Resource
	 */
	public function get_albums($limit = 0, $offset = 0) {
		$this->db->select("
			id
			, album_title
			, album_description
			, COALESCE(album_cover, 'no-image.jpg') AS album_cover
			, album_slug
		");
		$this->db->where('is_deleted', 'false');
		$this->db->order_by('created_at', 'DESC');
		return $this->db->get(self::$table, $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @return 	Int
	 */
	public function total_rows() {
      return $this->db
         ->where('is_deleted', 'false')
         ->count_all_results(self::$table);
	}
}
