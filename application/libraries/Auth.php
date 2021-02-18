<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Auth {

	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var object
	 */
	protected $CI;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		$this->CI = &get_instance();
		$this->CI->load->model(['m_users', 'm_user_privileges']);
	}

	/**
	 * Logged In()
	 * @access  public
	 * @param   string
	 * @param   string
	 * @return  bool
	 */
	public function logged_in($user_name, $user_password, $ip_address) {
		$login_attempts = $this->check_login_attempts($ip_address);
		if ($login_attempts) {
			$query = $this->CI->m_users->logged_in($user_name, $user_password);
			if ($query->num_rows() === 1) {
				$data = $query->row();
				if (password_verify($user_password, $data->user_password)) {
					$session_data = [
						'id' => $data->id,
						'user_name' => $data->user_name,
						'user_type' => $data->user_type,
						'user_profile_id' => $data->user_profile_id,
						'is_logged_in' => true,
						'user_privileges' => $this->CI->m_user_privileges->module_by_user_group_id($data->user_group_id, $data->user_type)
					];

					// If Student
					if ($data->user_type == 'student') {
						$student = $this->CI->model->RowObject('students', 'id', $data->user_profile_id);
						$session_data['is_student'] = filter_var((string) $student->is_student, FILTER_VALIDATE_BOOLEAN);
						$session_data['is_prospective_student'] = filter_var((string) $student->is_prospective_student, FILTER_VALIDATE_BOOLEAN);
						$session_data['is_alumni'] = filter_var((string) $student->is_alumni, FILTER_VALIDATE_BOOLEAN);
					}
					$this->CI->session->set_userdata($session_data);
					$this->last_logged_in($data->id);
					return true;
				}
				return false;
			}
			$this->increase_login_attempts($ip_address);
			return false;
		}
		return false;
	}

	/**
	 * Get User ID
	 * @access  public
	 * @return integer
	 **/
	public function get_user_id() {
		$id = (int) $this->CI->session->userdata('id');
		return !empty($id) ? $id : NULL;
	}

	/**
	 * Last Logged In
	 * Fungsi untuk mengupdate data login terakhir
	 * @access   public
	 * @return   void
	 */
	private function last_logged_in($id) {
		$this->CI->m_users->last_logged_in($id);
	}

	/**
	 * Is Logged In
	 * Fungsi untuk mengecek apakah data session user id kosong / tidak
	 * @access   public
	 * @return   bool
	 */
	public function is_logged_in() {
		return (bool) $this->CI->session->userdata('is_logged_in');
	}

	/**
	 * Restrict
	 * Fungsi untuk validasi status login
	 * @access   public
	 * @return   bool
	 */
	public function restrict() {
		if (!$this->is_logged_in()) {
			redirect('login', 'refresh');
		}
	}

	/**
	 * Check Login Attempts
	 * Fungsi untuk mengecek apakah bisa login atau di blokir
	 * @access   public
	 * @return   void
	 */
	public function check_login_attempts($ip_address) {
		$max_login_attempts = 3;
		$max_locked_time = 600; // locked at 30 minutes
		$login_attempts = $this->CI->m_users->check_login_attempts($ip_address);
		if ($login_attempts) {
			if ($login_attempts->counter >= $max_login_attempts) {
				$datetime = strtotime($login_attempts->updated_at);
				$difference = time() - $datetime;
				if ($difference >= $max_locked_time) {
					$this->clear_login_attempts($ip_address);
					return true;
				}
				return false;
			}
			return true;
		}
		return true;
	}

	/**
	 * Increase Login Attempts
	 * Fungsi untuk menginputkan ip address ketika gagal login
	 * @access   private
	 * @return   void
	 */
	private function increase_login_attempts($ip_address) {
		$this->CI->m_users->increase_login_attempts($ip_address);
	}

	/**
	 * Clear Login Attempts
	 * Fungsi untuk menghapus data login attempts
	 * @access   private
	 * @return   void
	 */
	private function clear_login_attempts($ip_address) {
		$this->CI->m_users->clear_login_attempts($ip_address);
	}
}
