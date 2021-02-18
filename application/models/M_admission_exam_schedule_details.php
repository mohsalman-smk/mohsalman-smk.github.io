<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_admission_exam_schedule_details extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'admission_exam_schedules';

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
	 * @param Int
	 * @param String
	 * @param Int
	 * @param Int
	 * @return Resource
	 */
	public function get_where($subject_setting_detail_id = 0, $keyword = '', $limit = 0, $offset = 0) {
		$this->db->select('x1.id, x2.room_name, x2.room_capacity, x3.building_name, x1.exam_date, x1.exam_start_time, x1.exam_end_time, x1.is_deleted');
		$this->db->join('rooms x2', 'x1.room_id = x2.id', 'LEFT');
		$this->db->join('buildings x3', 'x2.building_id = x3.id', 'LEFT');
		$this->db->where('x1.subject_setting_detail_id', $subject_setting_detail_id);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.exam_date', $keyword);
			$this->db->or_like('x1.exam_start_time', $keyword);
			$this->db->or_like('x1.exam_end_time', $keyword);
			$this->db->or_like('x2.room_name', $keyword);
			$this->db->or_like('x2.room_capacity', $keyword);
			$this->db->or_like('x3.building_name', $keyword);
			$this->db->group_end();
		}
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		return $this->db->get(self::$table.' x1');
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($subject_setting_detail_id = 0, $keyword = '') {
		$this->db->join('rooms x2', 'x1.room_id = x2.id', 'LEFT');
		$this->db->join('buildings x3', 'x2.building_id = x3.id', 'LEFT');
		$this->db->where('x1.subject_setting_detail_id', $subject_setting_detail_id);
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.exam_date', $keyword);
			$this->db->or_like('x1.exam_start_time', $keyword);
			$this->db->or_like('x1.exam_end_time', $keyword);
			$this->db->or_like('x2.room_name', $keyword);
			$this->db->or_like('x2.room_capacity', $keyword);
			$this->db->or_like('x3.building_name', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table.' x1');
	}

	/**
	 * get_title
	 * @param Int
	 * @return Int
	 */
	public function get_title($id) {
		$fields = [
			'x1.id'
		  , 'x1.exam_date'
		  , 'x1.exam_start_time'
		  , 'x1.exam_end_time'
		  , 'x2.id AS room_id'
		  , 'x2.room_name'
		  , 'x2.room_capacity'
		  , 'x3.id AS building_id'
		  , 'x3.building_name'
		  , 'x5.id AS subject_id'
		  , 'x5.subject_name'
		  , 'x7.id AS academic_year_id'
		  , 'x7.academic_year'
		  , 'x8.id AS admission_type_id'
		  , 'x8.admission_type'
		];
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			array_push($fields, 'x9.id AS major_id', "COALESCE(x9.major_name, '-') AS major_name");
		}
		$this->db->select(implode(', ', $fields));
		$this->db->where('x1.id', $id);
		$this->db->join('rooms x2', 'x1.room_id = x2.id');
		$this->db->join('buildings x3', 'x2.building_id = x3.id');
		$this->db->join('admission_subject_setting_details x4', 'x1.subject_setting_detail_id = x4.id');
		$this->db->join('subjects x5', 'x4.subject_id = x5.id');
		$this->db->join('admission_subject_settings x6', 'x4.subject_setting_id = x6.id');
		$this->db->join('academic_years x7', 'x6.academic_year_id = x7.id');
		$this->db->join('admission_types x8', 'x6.admission_type_id= x8.id');
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->join('majors x9', 'x6.major_id = x9.id', 'LEFT');
		}
		$query = $this->db->get('admission_exam_schedules x1');
		if ($query->num_rows() === 1) {
			return $query->row();
		}
		return [];
	}

	/**
	 * Exam Schedule by Student ID
	 * @param Int
	 * @return Resource
	 */
	public function exam_schedule_by_student_id($student_id) {
		return $this->db
			->select('x2.exam_date, x2.exam_start_time, x2.exam_end_time, x6.subject_name, x4.building_name, x3.room_name')
			->join('admission_exam_schedules x2', 'x1.exam_schedule_id = x2.id', 'LEFT')
			->join('rooms x3', 'x2.room_id = x3.id', 'LEFT')
			->join('buildings x4', 'x3.building_id = x4.id', 'LEFT')
			->join('admission_subject_setting_details x5', 'x2.subject_setting_detail_id = x5.id', 'LEFT')
			->join('subjects x6', 'x5.subject_id = x6.id', 'LEFT')
			->where('x1.student_id', (int) $student_id)
			->where('x1.is_deleted', 'false')
			->where('x2.is_deleted', 'false')
			->where('x3.is_deleted', 'false')
			->where('x4.is_deleted', 'false')
			->where('x5.is_deleted', 'false')
			->where('x6.is_deleted', 'false')
			->order_by('x2.exam_date', 'ASC')
			->order_by('x2.exam_start_time', 'ASC')
			->get('admission_exam_attendances x1');
	}
}