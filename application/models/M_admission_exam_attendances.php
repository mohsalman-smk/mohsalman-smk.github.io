<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_admission_exam_attendances extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'admission_exam_attendances';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}	

	/**
	 * Get Attendances List
	 * @param Int
	 * @return resource
	 */
	public function get_attendance_lists($exam_schedule_id) {
		$this->db->select('x1.id, x2.registration_number, x2.full_name, x1.presence');
		$this->db->join('students x2', 'x1.student_id = x2.id', 'LEFT');
		$this->db->where('x1.exam_schedule_id', $exam_schedule_id);
		$this->db->where('x1.is_deleted', 'false');
		$this->db->order_by('x2.registration_number', 'ASC');
		return $this->db->get(self::$table.' x1');
	}

	/**
	 * Save Attendances List
	 * @param Array
	 * @param Int
	 * @return Boolean
	 */
	public function save_attendance_lists($ids, $exam_schedule_id) {
		$success = 0; $error = 0;
		foreach ($ids as $id) {
			$fill_data = [
				'student_id' => (int) $id,
				'exam_schedule_id' => (int) $exam_schedule_id,
				'created_at' => NULL,
				'created_by' => (int) $this->session->userdata('id')
			];
			$this->db->insert(self::$table, $fill_data) ? $success++ : $error++;
		}
		return $success > 0;
	}

	/**
	 * Save Presences
	 * @param Array
	 * @return Boolean
	 */
	public function save_presences($presences) {
		$counter = 0;
		foreach ($presences as $row) {
			$fill_data = [
				'updated_by' => (int) $this->session->userdata('id'),
				'presence' => $row['presence']
			];
			$query = $this->db
				->where('id', $row['id'])
				->update('admission_exam_attendances', $fill_data);
			if ($query) {
				$counter++;
			}
		}

		return $counter > 0;
	}

	/**
	 * Delete Attendances List
	 * @param Array
	 * @return Boolean
	 */
	public function delete_attendance_lists($ids) {
		return $this->db
			->where_in('id', $ids)
			->delete(self::$table);
	}

	/**
	 * Get Prospective Students
	 * @param 	int
	 * @access 	public
	 * @return Resource
	 */
	public function get_prospective_students($subject_setting_detail_id) {
		$exam_schedule_ids = $this->db
			->select('id')
			->from('admission_exam_schedules')
			->where('subject_setting_detail_id', (int) $subject_setting_detail_id)
			->where('is_deleted', 'false')
			->get_compiled_select();
		$student_ids = $this->db
				->select('student_id')
				->from('admission_exam_attendances')
				->where('exam_schedule_id IN (' . $exam_schedule_ids . ')')
				->where('is_deleted', 'false')
				->get_compiled_select();
		return $this->db
			->select('x1.student_id, x4.registration_number, x4.full_name')
			->join('admission_subject_setting_details x2', 'x1.subject_setting_detail_id = x2.id', 'LEFT')
			->join('admission_subject_settings x3', 'x2.subject_setting_id = x3.id', 'LEFT')
			->join('students x4', 'x1.student_id = x4.id', 'LEFT')
			->where('x1.is_deleted', 'false')
			->where('x2.is_deleted', 'false')
			->where('x3.is_deleted', 'false')
			->where('x3.subject_type', 'exam_schedule')
			->where('x1.student_id NOT IN ( ' . $student_ids . ')')
			->group_by(['x1.student_id', 'x4.registration_number', 'x4.full_name'])
			->get('admission_subject_scores x1');
	}
}