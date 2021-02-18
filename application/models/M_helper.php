<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_helper extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @param String
	 * @param array
	 * @return Bool
	 */
	public function insert($table, array $fill_data) {
		$this->db->trans_start();
		$this->db->insert($table, $fill_data);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * @param String
	 * @param String
	 * @param String
	 * @param array
	 * @return Bool
	 */
	public function update($id, $table, array $fill_data) {
		$this->db->trans_start();
		$this->db->where(self::$pk, $id)->update($table, $fill_data);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * @param String
	 * @param String
	 * @param String
	 * @return Bool
	 */
	public function delete_permanently($key, $value, $table) {
		$this->db->trans_start();
		$this->db->where_in($key, $value)->delete($table);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * @param String
	 * @param String
	 * @param String
	 * @return Bool
	 */
	public function delete(array $ids, $table) {
		$this->db->trans_start();
		$this->db->where_in(self::$pk, $ids)
			->update($table, [
				'is_deleted' => 'true',
				'deleted_by' => $this->session->userdata('id'),
				'deleted_at' => date('Y-m-d H:i:s')
			]
		);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * @param String
	 * @return Bool
	 */
	public function truncate($table) {
		$this->db->trans_start();
		$this->db->truncate($table);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * @param String
	 * @param String
	 * @param String
	 * @return Bool
	 */
	public function restore(array $ids, $table) {
		$this->db->trans_start();
		$this->db->where_in(self::$pk, $ids)
			->update($table, [
				'is_deleted' => 'false',
				'restored_by' => $this->session->userdata('id'),
				'restored_at' => date('Y-m-d H:i:s')
			]
		);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	* isValExist
	 * @param String
	 * @param String
	 * @param String
	 * @return Bool
	 */
	public function isValExist($key, $value, $table) {
		$count = $this->db
			->where($key, $value)
			->count_all_results($table);
		return $count > 0;
	}

	/**
	 * Row Object
	 * @return Object
	 */
	public function RowObject($table, $key, $value) {
		return $this->db
			->where($key, $value)
			->get($table)
			->row();
	}

	/**
	 * Results Object
	 * @return Array of Object
	 */
	public function ResultsObject($table) {
		return $this->db->get($table)->result();
	}

	/**
	 * Row Array
	 * @return Array
	 */
	public function RowArray($table, $key, $value) {
		return $this->db
			->where($key, $value)
			->get($table)
			->row_array();
	}

	/**
	 * Results Array
	 * @return Array of Array
	 */
	public function ResultsArray($table) {
		return $this->db->get($table)->result_array();
	}

	/**
	 * Chek if email exist
	 * @param String
	 * @param String
	 * @param String
	 * @return Bool
	 */
	public function is_email_exist($email, $id) {
		// Var Initialize
		$response['is_exist'] = false;
		$response['used_by'] = '';

		// Check From students
		$student = $this->db
			->where('email', $email)
			->where('id !=', $id)
			->count_all_results('students');
		if ($student > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Peserta Didik';
			return $response;
		}

		// Check From employees
		$employee = $this->db
			->where('email', $email)
			->where('id !=', $id)
			->count_all_results('employees');
		if ($employee > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Guru dan Tenaga Kependidikan';
			return $response;
		}

		// Check from users students
		$user_student = $this->db
			->where('user_type', 'student')
			->where('user_email', $email)
			->where('user_profile_id !=', $id)
			->count_all_results('users');
		if ($user_student > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Peserta Didik';
			return $response;
		}

		// Check from users employees
		$user_employee = $this->db
			->where('user_type', 'employee')
			->where('user_email', $email)
			->where('user_profile_id !=', $id)
			->count_all_results('users');
		if ($user_employee > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Guru dan Tenaga Kependidikan';
			return $response;
		}

		// Check from users administrator or super users
		$user = $this->db
			->where('user_email', $email)
			->where('id !=', $id)
			->where_in('user_type', ['administrator', 'super_user'])
			->count_all_results('users');
		if ($user > 0) {
			$response['is_exist'] = true;
			$response['used_by'] = 'Administrator';
			return $response;
		}
		return $response;
	}

	/**
	 * Clear Expired Session and Login Attemps
	 * @return Void
	 */
	public function clear_expired_data() {
		$this->db->query("DELETE FROM `_sessions` WHERE DATE_FORMAT(FROM_UNIXTIME(timestamp), '%Y-%m-%d') < CURRENT_DATE");
		$this->db->query("DELETE FROM `login_attempts` WHERE DATE_FORMAT(created_at, '%Y-%m-%d') < CURRENT_DATE");
	}

	/**
	 * Get Admission Year
	 * @access 	public
	 * @param 	Int
	 * @return 	Int
	 */
	public function admission_year($admission_year_id = 0) {
		$admission_year = date('Y');
		$query = $this->db
			->select('academic_year')
			->where('id', $admission_year_id)
			->get('academic_years');
		if ($query->num_rows() === 1) {
			$res = $query->row();
			$admission_year = substr($res->academic_year, 0, 4);
		}
		return $admission_year;
	}
}