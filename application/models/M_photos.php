<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_photos extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'photos';

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
		$this->db->select('x1.id, CONCAT("thumbnail/",x1.photo_name) AS photo_name, x2.album_title, x1.is_deleted');
		$this->db->join('albums x2', 'x1.photo_album_id=x2.id', 'left');
		if (!empty($keyword)) {
			$this->db->like('x2.album_title', $keyword);
		}
		return $this->db->get(self::$table.' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('albums x2', 'x1.photo_album_id=x2.id', 'left');
		if (!empty($keyword)) {
			$this->db->like('x2.album_title', $keyword);
		}
		return $this->db->count_all_results('photos x1');
	}

	/**
	 * @param Int
	 * @return Bool
	 */
	public function delete_permanently($id) {
		$this->db->trans_start();
		$this->db->where(self::$pk, $id)->delete(self::$table);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * Get Images By ALbum ID
	 * @param 	Int
	 * @return Resource
	 */
	public function get_image_by_album_id($id) {
		return $this->db
			->select('photo_name')
			->where('photo_album_id', $id)
			->get(self::$table);
	}
}