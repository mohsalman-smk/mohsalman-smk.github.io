<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMA Karya Budi Cileunyi
 * @version    v4.6.2
 * @author     Ginanjar Restu U., S.Pd. | https://instagram.com/ginanjar_ru | lapakphp@gmail.com
 * @copyright  (c) 2017-2018
 * @link       http://sma-karyabudi.sch.id
 * @since      Version v4.6.2
 */

class M_educations extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'options';

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
		$this->db->select('id, option_group, option_name, is_deleted');
		$this->db->where('option_group', 'educations');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('option_name', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table, $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->where('option_group', 'educations');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('option_name', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table);
	}
}