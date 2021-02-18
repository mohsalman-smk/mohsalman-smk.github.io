<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_academic_schedules extends CI_Model {

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
	 * Get Data
	 * @param 	String 
	 * @param 	Int
	 * @param 	Int
	 * @return 	Resource
	 */
	public function get_where($keyword = '', $limit = 10, $offset = 0) {
		$this->db->select("
			x1.id
			, x1.academic_year_id
			, x2.academic_year
			, IF(x1.semester = 'odd', 'Ganjil', 'Genap') semester
			, CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ', x4.major_short_name),''), IF((x3.sub_class_group <> ''), CONCAT(' - ', x3.sub_class_group), '')) AS class_name
			, x5.subject_name
			, x1.is_deleted
		");
		$this->db->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
		$this->db->join('class_groups x3', 'x1.class_group_id = x3.id', 'LEFT');
		$this->db->join('majors x4', 'x3.major_id = x4.id', 'LEFT');
		$this->db->join('subjects x5', 'x1.subject_id = x5.id', 'LEFT');
		$this->db->where('x1.employee_id', (int) $this->session->userdata('user_profile_id'));
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.academic_year', $keyword);
			$this->db->or_like("CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ', x4.major_short_name),''), IF((x3.sub_class_group <> ''), CONCAT(' - ', x3.sub_class_group), ''))", $keyword);
			$this->db->or_like('x5.subject_name', $keyword);
			$this->db->group_end();
		}
		$this->db->order_by('x2.academic_year', 'DESC');
		$this->db->order_by('x3.class_group', 'ASC');
		$this->db->order_by('x3.major_id', 'ASC');
		$this->db->order_by('x3.sub_class_group', 'ASC');
		return $this->db->get(self::$table .' x1', $limit, $offset);
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
		$this->db->join('subjects x5', 'x1.subject_id = x5.id', 'LEFT');
		$this->db->where('x1.employee_id', (int) $this->session->userdata('user_profile_id'));
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.academic_year', $keyword);
			$this->db->or_like("CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ', x4.major_short_name),''), IF((x3.sub_class_group <> ''), CONCAT(' - ', x3.sub_class_group), ''))", $keyword);
			$this->db->or_like('x5.subject_name', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table .' x1');
	}
}