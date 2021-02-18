<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_messages extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'comments';

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
		$this->db->select('id, comment_author, comment_email, created_at, comment_content, is_deleted');
		$this->db->where('comment_type', 'message');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('comment_author', $keyword);
			$this->db->or_like('comment_email', $keyword);
			$this->db->or_like('created_at', $keyword);
			$this->db->or_like('comment_content', $keyword);
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
		$this->db->where('comment_type', 'message');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('comment_author', $keyword);
			$this->db->or_like('comment_email', $keyword);
			$this->db->or_like('created_at', $keyword);
			$this->db->or_like('comment_content', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table);
	}

	/**
	 * Reply Inbox
	 * @param Int
	 * @param Array
	 * @return Int
	 */
	public function reply($id, $fill_data) {
		$this->model->update($id, self::$table, $fill_data);
		$query = $this->model->RowObject(self::$table, self::$pk, $id);
      $sendgrid_api_key = $this->m_settings->get_sendgrid_api_key();
		$from = new \SendGrid\Email($this->session->userdata('school_name'), $this->session->userdata('email'));
		$to = new SendGrid\Email($query->comment_author, $query->comment_email);
		$content = new SendGrid\Content("text/plain", $fill_data['comment_reply']);
		$mail = new SendGrid\Mail($from, $fill_data['comment_subject'], $to, $content);
		$sendgrid = new \SendGrid($sendgrid_api_key);
		$response = $sendgrid->client->mail()->send()->post($mail);
		return $response->statusCode();
	}
}
