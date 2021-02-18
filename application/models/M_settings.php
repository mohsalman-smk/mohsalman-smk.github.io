<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_settings extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'settings';

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
	public function get_where($keyword = '', $limit = 0, $offset = 0, $setting_group = 'general') {
		$this->db->select('id, setting_variable, COALESCE(`setting_value`, `setting_default_value`) AS setting_value, setting_description, is_deleted');
		$this->db->where('setting_group', $setting_group);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('setting_description', $keyword);
			$this->db->or_like('setting_value', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table, $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '', $setting_group) {
		$this->db->where('setting_group', $setting_group);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('setting_description', $keyword);
			$this->db->or_like('setting_value', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table);
	}

	/**
	 * Get Setting Values
	 * @param array
	 * @return array
	 */
	public function get_setting_values($setting_access_group = 'public') {
		$query = $this->db
			->select('setting_variable, COALESCE(`setting_value`, `setting_default_value`) AS `setting_value`')
			->where('setting_group !=', 'mail_server')
			->group_start()
			->like('setting_access_group', $setting_access_group)
			->group_end()
			->get(self::$table);
		$settings = [];
		foreach($query->result() as $row) {
			$settings[$row->setting_variable] = $row->setting_value;
		}
		return $settings;
	}

	/**
	 * mail_server_settings
	 * @return array
	 */
	function mail_server_settings() {
		$query = $this->db
			->select("setting_variable, COALESCE(setting_value, setting_default_value, '') setting_value")
			->where('setting_group', 'mail_server')
			->get('settings');
		$data = [];
		foreach($query->result() as $row) {
			$data[$row->setting_variable] = $row->setting_value;
		}
		return $data;
	}

	/**
	 * Recaptcha
	 * @return array
	 */
	function get_recaptcha() {
		$query = $this->db
			->select("setting_variable, setting_value")
			->where_in('setting_variable', ['recaptcha_site_key', 'recaptcha_secret_key'])
			->get('settings');
		$data = [];
		$data['recaptcha_site_key'] = NULL;
		$data['recaptcha_secret_key'] = NULL;
		foreach($query->result() as $row) {
			if ($row->setting_variable == 'recaptcha_site_key') {
				$data['recaptcha_site_key'] = $row->setting_value;
			}
			if ($row->setting_variable == 'recaptcha_secret_key') {
				$data['recaptcha_secret_key'] = $row->setting_value;
			}
		}
		return $data;
	}

	/**
	 * Get Sendgrid API Key
	 * @return Array
	 */
	public function get_sendgrid_api_key() {
		$query = $this->db
			->select("setting_variable, setting_value, setting_default_value")
			->where('setting_variable', 'sendgrid_api_key')
			->get('settings');
		if ($query->num_rows() === 1) {
			$res = $query->row();
			return $res->setting_value ? $res->setting_value : $res->setting_default_value;
		}
		return NULL;
	}
}
