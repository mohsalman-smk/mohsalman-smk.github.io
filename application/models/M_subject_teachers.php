<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_subject_teachers extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'course_classes';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Subjects
	 * @param 	Int
	 * @param 	Int
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_subjects($academic_year_id, $semester, $class_group_id) {
		$data = [];
		$query = $this->db
			->select('x1.id, x2.subject_name, x1.employee_id')
			->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT')
			->where('x1.academic_year_id', $academic_year_id)
			->where('x1.semester', $semester)
			->where('x1.class_group_id', $class_group_id)
			->get(self::$table .' x1');
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = [
					'id' => $row->id,
					'subject_name' => $row->subject_name,
					'employee_id' => $row->employee_id
				];
			}	
		}
		return $data;
	}

	/**
	 * Save Subject Teachers
	 * @param 	Array
	 * @return 	Bool
	 */
	public function save($course_classes) {
		$counter = 0;
		foreach ($course_classes as $row) {
			$fill_data = [
				'updated_by' => $this->session->userdata('id'),
				'employee_id' => $row['employee_id']
			];
			$id = $row['id'];
			$query = $this->db
				->where('id', $id)
				->update(self::$table, $fill_data);
			if ($query) {
				$counter++;
			}
		}
		return $counter > 0;
	}
}