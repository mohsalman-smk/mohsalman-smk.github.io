<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_course_classes extends CI_Model {

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
	 * @param String
	 * @param Int
	 * @param Int
	 * @param Int
	 * @return Resource
	 */
	public function get_subjects($copy_data, $academic_year_id, $semester, $class_group_id) {
		$data = [];
		if ($copy_data == 'true') {
			$query = $this->db
				->select('x2.id, x2.subject_name')
				->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT')
				->where('x1.academic_year_id', (int) $academic_year_id)
				->where('x1.semester', $semester)
				->where('x1.class_group_id', (int) $class_group_id)
				->where('x1.is_deleted', 'false')
				->where('x2.is_deleted', 'false')
				->get(self::$table .' x1');
		} else {
			$query = $this->db
				->select('id, subject_name')
				->where('is_deleted', 'false')
				->get('subjects');
		}
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = [
					'id' => $row->id,
					'subject_name' => $row->subject_name
				];
			}	
		}
		return $data;
	}

	/**
	 * Get Course Classes
	 * @param Int
	 * @param Int
	 * @param Int
	 * @return Array
	 */
	public function get_course_classes($academic_year_id, $semester, $class_group_id) {
		$data = [];
		$query = $this->db
			->select("x1.id, x2.subject_name, COALESCE(x3.full_name, '[unset]') AS full_name, x1.is_deleted")
			->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT')
			->join('employees x3', 'x1.employee_id = x3.id', 'LEFT')
			->where('x1.academic_year_id', $academic_year_id)
			->where('x1.semester', $semester)
			->where('x1.class_group_id', (int) $class_group_id)
			->get(self::$table .' x1');
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[] = [
					'id' => $row->id,
					'subject_name' => $row->subject_name,
					'full_name' => $row->full_name,
					'is_deleted' => $row->is_deleted
				];
			}	
		}
		return $data;
	}

	/**
	 * Save to Destination Class
	 * @param Int
	 * @param Int
	 * @param Int
	 * @return Bool
	 */
	public function save($ids, $academic_year_id, $semester, $class_group_id) {
		$success = 0;
		foreach ($ids as $subject_id) {
			$count = $this->db
				->where('academic_year_id', (int) $academic_year_id)
				->where('semester', $semester)
				->where('class_group_id', (int) $class_group_id)
				->where('subject_id', (int) $subject_id)
				->count_all_results(self::$table);
			if ($count == 0) {
				$fill_data = [
					'academic_year_id' => (int) $academic_year_id,
					'semester' => $semester,
					'class_group_id' => (int) $class_group_id,
					'subject_id' => (int) $subject_id
				];
				if ($this->db->insert(self::$table, $fill_data)) {
					$success++;
				}
			}
		}
		return $success > 0;
	}

	/**
	 * Change Deleted Status
	 * @param array
	 * @param Int
	 * @param Int
	 * @return Bool
	 */
	public function change_deleted_status($ids, $academic_year_id, $semester, $class_group_id, $is_deleted = 'true') {
		$success = 0;
		foreach ($ids as $subject_id) {
			$course_class_id = 0;
			$course_class = $this->db
				->select('id')
				->where('academic_year_id', (int) $academic_year_id)
				->where('semester', $semester)
				->where('class_group_id', (int) $class_group_id)
				->where('subject_id', (int) $subject_id)
				->get(self::$table);
			if ($course_class->num_rows() == 1) {
				$res = $course_class->row();
				$course_class_id = $res->id;
			}

			$class_meeting_id = 0;
			if ($course_class_id > 0) {
				$class_meeting = $this->db
					->select('id')
					->where('course_class_id', (int) $course_class_id)
					->get('class_meetings');
				if ($class_meeting->num_rows() == 1) {
					$res = $class_meeting->row();
					$class_meeting_id = $res->id;
				}
			}
			// Delete Course Classes
			$query = $this->db
				->where('academic_year_id', (int) $academic_year_id)
				->where('semester', $semester)
				->where('class_group_id', (int) $class_group_id)
				->where('subject_id', (int) $subject_id)
				->update(self::$table, ['is_deleted' => $is_deleted]);
			if ($query && $course_class_id > 0) {
				$success++;
				// Delete Class Meetings
				$query = $this->db
					->where('course_class_id', (int) $course_class_id)
					->update('class_meetings', ['is_deleted' => $is_deleted]);
				if ($query && $class_meeting_id > 0) {
					$success++;
					// Delete Meeting Attendences
					$query = $this->db
						->where('class_meeting_id', (int) $class_meeting_id)
						->update('meeting_attendences', ['is_deleted' => $is_deleted]);
					if ($query) {
						$success++;
					}
				}
			}
		}

		return $success > 0;
	}

	/**
	 * Get Course Classes By ID
	 * @param Int
	 * @return Resource
	 */
	public function get_course_classes_by_id($id) {
		return $this->db
			->select("
				x1.id
				, x2.academic_year
				, IF(x1.semester = 'odd', 'Ganjil', 'Genap') AS semester
				, CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),'')) class_group
				, x5.subject_name
				, x6.full_name
			")
			->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT')
			->join('class_groups x3', 'x1.class_group_id = x3.id', 'LEFT')
			->join('majors x4', 'x3.major_id = x4.id', 'LEFT')
			->join('subjects x5', 'x1.subject_id = x5.id', 'LEFT')
			->join('employees x6', 'x1.employee_id = x6.id', 'LEFT')
			->where('x1.id', $id)
			->get('course_classes x1')
			->row();
	}

	/**
	 * Get Course Class ID
	 * @param Int
	 * @param String
	 * @param Int
	 * @param Int
	 * @return Int
	 */
	public function find_id($academic_year_id, $semester, $class_group_id, $subject_id, $employee_id) {
		$query = $this->db
			->select('id')
			->where('academic_year_id', (int) $academic_year_id)
			->where('semester', $semester)
			->where('class_group_id', (int) $class_group_id)
			->where('subject_id', (int) $subject_id)
			->where('employee_id', (int) $employee_id)
			->get('course_classes');
		if ($query->num_rows() === 1) {
			$res = $query->row();
			return $res->id;
		}
		return 0;
	}
}