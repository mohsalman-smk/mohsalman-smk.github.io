<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_users extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'users';

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
	public function get_where($keyword, $limit = 10, $offset = 0, $user_type = '') {
		if ($user_type == 'student') {
			$this->db->select('
				x1.id
				, x1.user_name
				, x2.full_name AS user_full_name
				, x2.email AS user_email
				, x1.is_deleted
			');
			$this->db->join('students x2', 'x1.user_profile_id = x2.id', 'LEFT');
			$this->db->where('user_type', 'student');
		}

		if ($user_type == 'employee') {
			$this->db->select('
				x1.id
				, x1.user_name
				, x2.full_name AS user_full_name
				, x2.email AS user_email
				, x1.is_deleted
			');
			$this->db->join('employees x2', 'x1.user_profile_id = x2.id', 'LEFT');
			$this->db->where('user_type', 'employee');
		}

		if ($user_type == 'administrator') {
			$this->db->select('
				x1.id
				, x1.user_name
				, x1.user_full_name
				, x1.user_email
				, x1.user_url
				, x2.user_group
				, x1.is_deleted
			');
			$this->db->join('user_groups x2', 'x1.user_group_id = x2.id', 'LEFT');
			$this->db->where('user_type', 'administrator');
		}

		if (in_array($user_type, ['student', 'employee']) && $keyword != '') {
			$this->db->group_start();
			$this->db->like('x1.user_name', $keyword);
			$this->db->or_like('x2.full_name', $keyword);
			$this->db->or_like('x2.email', $keyword);
			$this->db->group_end();
		}

		if ($user_type == 'administrator' && $keyword != '') {
			$this->db->group_start();
			$this->db->like('x1.user_name', $keyword);
			$this->db->or_like('x1.user_name', $keyword);
			$this->db->or_like('x1.user_full_name', $keyword);
			$this->db->or_like('x1.user_email', $keyword);
			$this->db->or_like('x1.user_url', $keyword);
			$this->db->or_like('x2.user_group', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table.' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('user_groups x2', 'x1.user_group_id = x2.id', 'left');
		$user_type = $this->session->userdata('user_type');
		if ($user_type == 'super_user') {
			$this->db->where('user_type !=', 'super_user');
		}
		if ($user_type == 'administrator') {
			$this->db->where_not_in('user_type', ['super_user', 'administrator']);
		}
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.user_name', $keyword);
			$this->db->or_like('x1.user_name', $keyword);
			$this->db->or_like('x1.user_full_name', $keyword);
			$this->db->or_like('x1.user_email', $keyword);
			$this->db->or_like('x1.user_url', $keyword);
			$this->db->or_like('x2.user_group', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table. ' x1');
	}

	/**
     * logged_in()
     * @access  Public
     * @param   string
     * @return  bool
     */
	public function logged_in($user_name) {
		return $this->db
			->select('id
				, user_name
				, user_password
				, user_type
				, user_group_id
				, user_profile_id
				, is_logged_in
			')
         ->where('user_name', $user_name)
         ->where('is_deleted', 'false')
         ->limit(1)
         ->get(self::$table);
	}

	/**
     * last_logged_in()
     * @access  Public
     * @param   int
     * @return  void
     */
	public function last_logged_in($id) {
		$fields = [
			'last_logged_in' => date('Y-m-d H:i:s'),
			'ip_address' => get_ip_address(),
			'is_logged_in' => 'true'
		];
		$this->db
			->where(self::$pk, $id)
			->update(self::$table, $fields);
	}

	/**
     * reset_logged_in
     * set is_logged_in to false
     * @access  Public
     * @param   int
     * @return  void
     */
	public function reset_logged_in($id) {
		$this->db
			->where(self::$pk, $id)
			->update(self::$table, ['is_logged_in' => 'false']);
	}

	/**
     * check_login_attempts
     * @access  Public
     * @param   string
     * @return  int
     */
	public function check_login_attempts($ip_address) {
		$query = $this->db
			->where('ip_address', $ip_address)
			->get('login_attempts');
		if ($query->num_rows() === 1) {
			return $query->row();
		}
		return NULL;
	}

	/**
     * increase_login_attempts
     * @access  Public
     * @param   string
     * @return  void
     */
	public function increase_login_attempts($ip_address) {
		$query = $this->db
			->where('ip_address', $ip_address)
			->get('login_attempts');
		if ($query->num_rows() === 1) {
			$result = $query->row();
			$this->db
				->where('ip_address', $ip_address)
				->update('login_attempts', ['counter' => ($result->counter + 1)]);
		} else {
			$this->db
				->insert('login_attempts', ['ip_address' => $ip_address, 'counter' => 1]);
		}
	}

	/**
     * clear_login_attempts
     * @access  Public
     * @param   string
     * @return  void
     */
	public function clear_login_attempts($ip_address) {
		$this->db
			->where('ip_address', $ip_address)
			->delete('login_attempts');
	}

	/**
     * get last logged in
     * @access  Public
     * @return  query
     */
	public function get_last_logged_in() {
		return $this->db
			->select("
				CASE WHEN x1.user_type = 'administrator' THEN x1.user_full_name
    			WHEN x1.user_type = 'student' THEN x2.full_name
    			WHEN x1.user_type = 'employee' THEN x3.full_name
      			END AS full_name
  				, x1.last_logged_in
			")
			->join('students x2', 'x1.user_profile_id = x2.id', 'LEFT')
			->join('employees x3', 'x1.user_profile_id = x3.id', 'LEFT')
			->where('x1.user_type !=', 'super_user')
			->where('x1.last_logged_in IS NOT NULL')
			->order_by('x1.last_logged_in', 'DESC')
			->limit(10)
			->get(self::$table.' x1');
	}

	/**
     * is_user_exist
     * @param 	String
     * @access  Public
     * @return  Int
     */
	public function is_exist($field, $value) {
		return $this->db
			->where($field, $value)
			->count_all_results(self::$table);
	}

	/**
     * change_user_name
     * @param 	String
     * @access  Public
     * @return  Boolean
     */
	public function change_user_name($user_name) {
		return $this->db
			->where('user_name', $this->session->userdata('user_name'))
			->update(self::$table, ['user_name' => $user_name]);
	}

	/**
     * set_forgot_password_key
     * @param 	String
     * @param 	String
     * @access  Public
     * @return  Boolean
     */
	public function set_forgot_password_key($user_email, $user_forgot_password_key) {
		$fill_data = [
			'user_forgot_password_key' => $user_forgot_password_key,
			'user_forgot_password_request_date' => date('Y-m-d H:i:s')
		];
		return $this->db
			->where('user_email', $user_email)
			->update(self::$table, $fill_data);
	}

	/**
     * remove activation key
     * @param 	String
     * @access  Public
     * @return Bool
     */
	public function remove_forgot_password_key($id) {
		return $this->db
			->where(self::$pk, $id)
			->update(self::$table, ['user_forgot_password_key' => NULL, 'user_forgot_password_request_date' => NULL]);
	}

	/**
     * Reset Password
     * @param 	String
     * @access  Public
     * @return 	Bool
     */
	public function reset_password($id, array $fill_data) {
		return $this->db
			->where(self::$pk, $id)
			->update(self::$table, $fill_data);
	}

	/**
     * Get user by email
     * @param 	String
     * @access  Public
     * @return 	Array|NULL
     */
	public function get_user_by_email($user_email) {
		$query = $this->db
			->where('user_email', $user_email)
			->get(self::$table);
		if ($query->num_rows() === 1) {
			$result = $query->row();
			return [
				'user_email' => $result->user_email,
				'user_full_name' => $result->user_full_name
			];
		}

		return NULL;
	}
}
