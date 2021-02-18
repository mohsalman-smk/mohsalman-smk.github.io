<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_answers extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'answers';

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
		$this->db->select('x1.id, x2.question,	x1.answer, x1.is_deleted');
		$this->db->join('questions x2', 'x1.question_id = x2.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x2.question', $keyword);
			$this->db->or_like('x1.answer', $keyword);
		}
		return $this->db->get(self::$table.' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('questions x2', 'x1.question_id = x2.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x2.question', $keyword);
			$this->db->or_like('x1.answer', $keyword);
		}			
		return $this->db->count_all_results(self::$table.' x1');
	}
}