<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_options extends CI_Model {

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
	public function get_options($option_group = 'student_status') {
		$query = $this->db
			->select('id, option_name')
			->where('option_group', $option_group)
			->where('is_deleted', 'false')
			->order_by('id', 'ASC')
			->get(self::$table);
		$data = [];
		foreach($query->result() as $row) {
			$data[$row->id] = $row->option_name;
		}
		return $data;
	}

	/**
	 * Get options id
	 * @param String 
	 * @param String 
	 * @return Int
	 */
	public function get_options_id($option_group = '', $option_name = '') {
		$query = $this->db
			->select('id')
			->where('option_group', $option_group)
			->where('LOWER(`option_name`)', $option_name)
			->limit(1)
			->get('options')
			->row();
		return $query->id;
	}
}