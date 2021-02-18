<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_form_settings extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'field_settings';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get all data
	 * @return Resource
	 */
	public function get_all() {
		return $this->db->get(self::$table);
	}

	/**
	 * Get Field Setting
	 * @return 	Array
	 */
	public function get_field_setting() {
		$query = $this->get_all();
		$data = [];
		foreach ($query->result() as $row) {
			$field_setting = json_decode($row->field_setting);
			$data[$row->field_name] = [
				'admission' => $field_setting->admission,
				'admission_required' => $field_setting->admission_required
			];
		}
		return $data;
	}
}
