<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_pollings extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'pollings';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Save
	 * @param Int
	 * @return Bool
	 */
	public function save($answer_id) {
		$count = $this->db
			->where('ip_address', $_SERVER['REMOTE_ADDR'])
			->where('LEFT(created_at, 10)=', date('Y-m-d'))
			->count_all_results(self::$table);
		if ($count === 0) {
			return $this->model->insert(self::$table, [
					'ip_address' => $_SERVER['REMOTE_ADDR'],
					'answer_id' => $answer_id,
					'created_at' => date('Y-m-d H:i:s')
				]
			);
		}
		return FALSE;
	}

	/**
	 * Polling Result
	 * @param Int
	 * @return Resource
	 */
	public function polling_result($question_id) {
		$id = (int) $question_id;
		return $this->db->query("
			SELECT x2.answer AS labels
			  , COUNT(*) AS data
			FROM pollings x1
			LEFT JOIN answers x2
			  ON x1.answer_id= x2.id
			WHERE x2.question_id = ?
			GROUP BY x1.answer_id
			ORDER BY 1 ASC
		", [$id]);
	}
}
