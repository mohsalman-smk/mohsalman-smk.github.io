<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_class_group_students extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'class_group_students';

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
		$this->db->select("
			x1.id
			, x2.academic_year
			, CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),'')) AS class_group
			, COALESCE(x5.identity_number, '') AS identity_number
			, COALESCE(x5.nisn, '') AS nisn
			, x5.full_name
			, x5.gender
			, COALESCE(x5.birth_place, '') AS birth_place
			, COALESCE(x5.birth_date, '') AS birth_date
			,	x1.is_deleted
			");
		$this->db->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
		$this->db->join('class_groups x3', 'x1.class_group_id = x3.id', 'LEFT');
		$this->db->join('majors x4', 'x3.major_id = x4.id', 'LEFT');
		$this->db->join('students x5', 'x1.student_id = x5.id', 'LEFT');
		$this->db->where('x5.is_student', 'true');
		$this->db->where('x5.is_alumni', 'false');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.academic_year', $keyword);
			$this->db->or_like("CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),''))", $keyword);
			$this->db->or_like('x5.identity_number', $keyword);
			$this->db->or_like('x5.nisn', $keyword);
			$this->db->or_like('x5.full_name', $keyword);
			$this->db->or_like('x5.gender', $keyword);
			$this->db->or_like('x5.birth_place', $keyword);
			$this->db->or_like('x5.birth_date', $keyword);
			$this->db->group_end();
		}
		return $this->db->get(self::$table.' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
		$this->db->join('class_groups x3', 'x1.class_group_id = x3.id', 'LEFT');
		$this->db->join('majors x4', 'x3.major_id = x4.id', 'LEFT');
		$this->db->join('students x5', 'x1.student_id = x5.id', 'LEFT');
		$this->db->where('x5.is_student', 'true');
		$this->db->where('x5.is_alumni', 'false');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.academic_year', $keyword);
			$this->db->or_like("CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),''))", $keyword);
			$this->db->or_like('x5.identity_number', $keyword);
			$this->db->or_like('x5.nisn', $keyword);
			$this->db->or_like('x5.full_name', $keyword);
			$this->db->or_like('x5.gender', $keyword);
			$this->db->or_like('x5.birth_place', $keyword);
			$this->db->or_like('x5.birth_date', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table.' x1');
	}

	/**
	 * Get Students
	 * @param Int
	 * @param Int
	 * @return Resource
	 */
	public function get_students($academic_year_id, $class_group_id) {
		// Get "Aktif" Student Status ID
		$this->load->model('m_student_status');
		$student_status_id = (int) $this->m_student_status->find_student_status_id('aktif');
		if ($class_group_id == 'unset') {
			$student_ids = $this->db
				->select('student_id')
				->from('class_group_students')
				->group_by('student_id')
				->get_compiled_select();
			$query = $this->db
				->select('id, identity_number, full_name')
				->where('id NOT IN(' . $student_ids . ')')
				->where('is_student', 'true')
				->where('is_alumni', 'false')
				->where('student_status_id', (int) $student_status_id)
				->get('students');
		} else if ($class_group_id == 'show_all') {
			$query = $this->db
				->select('id, identity_number, full_name')
				->where('is_student', 'true')
				->where('is_alumni', 'false')
				->where('student_status_id', (int) $student_status_id)
				->get('students');
		} else {
			$class_group_setting = $this->db
				->select('id')
				->where('academic_year_id', $academic_year_id)
				->where('class_group_id', $class_group_id)
				->get('class_group_settings');
			if ($class_group_setting->num_rows() === 1) {
				$res = $class_group_setting->row();
				$class_group_setting_id = $res->id;
				$query = $this->db
					->select('x2.id, x2.identity_number, x2.full_name')
					->join('students x2', 'x1.student_id = x2.id', 'LEFT')
					->where('x1.class_group_setting_id', $class_group_setting_id)
					->where('x1.is_deleted', 'false')
					->where('x2.is_deleted', 'false')
					->where('x2.is_alumni', 'false')
					->where('x2.is_student', 'true')
					->get(self::$table.' x1');
			}
		}

		$data = [];
		if (isset($query) && is_object($query)) {
			foreach($query->result() as $row) {
				$data[] = [
					'id' => $row->id,
					'identity_number' => $row->identity_number,
					'full_name' => $row->full_name
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
	public function save_to_destination_class($ids, $academic_year_id, $class_group_id) {
		// Get Active student Status ID
		$this->load->model('m_student_status');
		$student_status_id = (int) $this->m_student_status->find_student_status_id('aktif');
		// Get Class Group Setting
		$class_group_setting_id = 0;
		$class_group_setting = $this->db
			->select('id')
			->where('academic_year_id', $academic_year_id)
			->where('class_group_id', $class_group_id)
			->get('class_group_settings');
		if ($class_group_setting->num_rows() === 1) {
			$res = $class_group_setting->row();
			$class_group_setting_id = $res->id;
		}
		$success = 0;
		if ($class_group_setting_id > 0) {
			foreach ($ids as $id) {
				$fill_data = [
					'student_id' => $id,
					'class_group_setting_id' => $class_group_setting_id
				];
				if ($this->db->insert(self::$table, $fill_data)) {
					// if Success, update student status to "Aktif"
					$this->db->where('id', $id)->update('students', ['student_status_id' => $student_status_id]);
					$success++;
				}
			}
		}
		return $success > 0;
	}

	/**
	 * Delete Permanently
	 * @param array
	 * @param Int
	 * @param Int
	 * @return Bool
	 */
	public function delete_permanently($ids, $academic_year_id, $class_group_id) {
		$class_group_setting_id = 0;
		$class_group_setting = $this->db
			->select('id')
			->where('academic_year_id', $academic_year_id)
			->where('class_group_id', $class_group_id)
			->get('class_group_settings');
		if ($class_group_setting->num_rows() === 1) {
			$res = $class_group_setting->row();
			$class_group_setting_id = $res->id;
		}
		return $this->db
			->where('class_group_setting_id', $class_group_setting_id)
			->where_in('student_id', $ids)
			->delete(self::$table);
	}

	/**
	 * Search Students
	 * @param Int
	 * @param Int
	 * @return Resource
	 */
	public function search_students($academic_year_id, $class_group_id) {
		$this->db->select("
			x1.id
			, x3.identity_number
			, x3.nisn
			, x3.full_name
			, x3.birth_place
			, x3.birth_date
			, IF(x3.gender = 'M', 'L', 'P') AS gender
			, x3.photo
		");
		$this->db->join("class_group_settings x2", "x1.class_group_setting_id = x2.id", "LEFT");
		$this->db->join("students x3", "x1.student_id = x3.id", "LEFT");
		$this->db->where("x1.is_deleted", "false");
		$this->db->where("x2.academic_year_id", (int) $academic_year_id);
		$this->db->where("x2.class_group_id", (int) $class_group_id);
		$this->db->order_by("x3.full_name", 'ASC');
		return $this->db->get("class_group_students x1");
	}
}
