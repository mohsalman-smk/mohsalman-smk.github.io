<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_student_groups extends CI_Model {

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
			, x4.academic_year
			, CONCAT(x5.class_group, IF((x6.major_short_name <> ''), CONCAT(' ', x6.major_short_name),''), IF((x5.sub_class_group <> ''),CONCAT(' - ', x5.sub_class_group),'')) AS class_name
			, COALESCE(x2.identity_number, '-') AS identity_number
			, x2.full_name
			, x2.birth_place
			, x2.birth_date
			, IF(x2.gender = 'M', 'L', 'P') AS gender
			, x1.is_class_president
			, x1.is_deleted
		");
		$this->db->join('students x2', 'x1.student_id = x2.id', 'LEFT');
		$this->db->join('class_group_settings x3', 'x1.class_group_setting_id = x3.id', 'LEFT');
		$this->db->join('academic_years x4', 'x3.academic_year_id = x4.id', 'LEFT');
		$this->db->join('class_groups x5', 'x3.class_group_id = x5.id', 'LEFT');
		$this->db->join('majors x6', 'x5.major_id = x6.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x4.academic_year', $keyword);
			$this->db->or_like('x2.identity_number', $keyword);
			$this->db->or_like('x2.full_name', $keyword);
			$this->db->or_like('x2.birth_place', $keyword);
			$this->db->or_like('x2.birth_date', $keyword);
			$this->db->or_like('x2.gender', $keyword);
			$this->db->or_like("CONCAT(x5.class_group, IF((x6.major_short_name <> ''), CONCAT(' ', x6.major_short_name),''), IF((x5.sub_class_group <> ''), CONCAT(' - ', x5.sub_class_group),''))", $keyword);
		}
		$this->db->order_by('x4.academic_year', 'ASC');
		$this->db->order_by('x5.class_group', 'ASC');
		$this->db->order_by('x5.major_id', 'ASC');
		$this->db->order_by('x5.sub_class_group', 'ASC');
		$this->db->order_by('x2.full_name', 'ASC');		
		return $this->db->get(self::$table.' x1', $limit, $offset);
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		$this->db->join('students x2', 'x1.student_id = x2.id', 'LEFT');
		$this->db->join('class_group_settings x3', 'x1.class_group_setting_id = x3.id', 'LEFT');
		$this->db->join('academic_years x4', 'x3.academic_year_id = x4.id', 'LEFT');
		$this->db->join('class_groups x5', 'x3.class_group_id = x5.id', 'LEFT');
		$this->db->join('majors x6', 'x5.major_id = x6.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x4.academic_year', $keyword);
			$this->db->or_like('x2.identity_number', $keyword);
			$this->db->or_like('x2.full_name', $keyword);
			$this->db->or_like('x2.birth_place', $keyword);
			$this->db->or_like('x2.birth_date', $keyword);
			$this->db->or_like('x2.gender', $keyword);
			$this->db->or_like("CONCAT(x5.class_group, IF((x6.major_short_name <> ''), CONCAT(' ', x6.major_short_name),''), IF((x5.sub_class_group <> ''), CONCAT(' - ', x5.sub_class_group),''))", $keyword);
		}
		return $this->db->count_all_results(self::$table.' x1');
	}

	/**
	 * Set Class President / Ketua Kelas
	 * @param Int
	 * @param Array
	 * @return Bool
	 */
	public function set_class_president($id, $fill_data) {
		$class_group_setting_id = 0;
		$query = $this->model->RowObject('class_group_students', 'id', $id);
		if (is_object($query)) {
			$class_group_setting_id = $query->class_group_setting_id;	
		}
		if ($class_group_setting_id > 0) {
			$this->db->trans_start();
			if ($fill_data['is_class_president'] == 'true') {
				$this->db
					->where('class_group_setting_id', $class_group_setting_id)
					->update(self::$table, ['is_class_president' => 'false']);
			}
			$this->db
				->where(self::$pk, $id)
				->update(self::$table, $fill_data);
			$this->db->trans_complete();
			return $this->db->trans_status();
		}
		return FALSE;
	}
}