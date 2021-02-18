<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_class_meetings extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'class_meetings';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get class meetings by course class ID and date
	 * @param Int
	 * @param String
	 * @return Resource
	 */
	public function get_class_meetings($course_class_id, $date) {
		// If Not Exist, then insert this
		if (! $this->is_exist($course_class_id, $date)) {
			$this->insert($course_class_id, $date);
		}
		$query = $this->db
			->where('course_class_id', (int) $course_class_id)
			->where('date', $date)
			->limit(1)
			->get(self::$table);
		if ($query->num_rows() === 1) {
			return $query->row();
		}
		return NULL;
	}

	/**
	 * Is Exist class meetings by course class ID and date
	 * @param Int
	 * @param String
	 * @return Bool
	 */
	public function is_exist($course_class_id, $date) {
		$count = $this->db
			->where('course_class_id', (int) $course_class_id)
			->where('date', $date)
			->count_all_results(self::$table);
		return $count > 0;
	}

	/**
	 * Insert class meetings by course class ID and date
	 * @param Int
	 * @param String
	 * @return Bool
	 */
	public function insert($course_class_id, $date) {
		if (! $this->is_exist($course_class_id, $date)) {
			$fill_data = [
				'course_class_id' => (int) $course_class_id,
				'date' => $date,
				'start_time' => date('H:i:s'),
				'end_time' => date('H:i:s')
			];
			return $this->db->insert(self::$table, $fill_data);
		}
		return FALSE;
	}

	/**
	 * Insert class meetings by course class ID and date
	 * @param array
	 * @param String
	 * @return Bool
	 */
	public function update($fill_data, $course_class_id, $date) {
		return $this->db
			->where('course_class_id', (int) $course_class_id)
			->where('date', $date)
			->update(self::$table, $fill_data);
	}

	/**
	 * Is Exist class meetings by course class ID and date
	 * @param Int
	 * @param String
	 * @return Bool
	 */
	public function class_meeting_id($course_class_id, $date) {
		$query = $this->db
			->select('id')
			->where('course_class_id', (int) $course_class_id)
			->where('date', $date)
			->get(self::$table);
		if ($query->num_rows() === 1) {
			$result = $query->row();
			return $result->id;
		}
		return 0;
	}
}
