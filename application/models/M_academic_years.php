<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_academic_years extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'academic_years';

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
		$this->db->select('id, academic_year, semester, current_semester, admission_semester, is_deleted');
		if (!empty($keyword)) {
			$this->db->like('academic_year', $keyword);	
		}
		return $this->db->get(self::$table, $limit, $offset);
	}

	/**
	 * Get active admission semester ID
	 * @return Int
	 */
	public function get_active_academic_year() {
		$data = [];
		$admission_semester = $this->db
			->select('id, academic_year')
			->where('admission_semester', 'true')
			->where('is_deleted', 'false')
			->order_by('academic_year', 'DESC')
			->limit(1)
			->get(self::$table);
		if ($admission_semester->num_rows() === 1) {
			$res = $admission_semester->row();
			$data['admission_semester_id'] = $res->id;
			$data['admission_semester'] = $res->academic_year;
			$data['admission_year'] = substr($res->academic_year, 0, 4);
		}

		$current_semester = $this->db
			->select('id, academic_year, semester')
			->where('current_semester', 'true')
			->where('is_deleted', 'false')
			->order_by('academic_year', 'DESC')
			->limit(1)
			->get(self::$table);
		if ($current_semester->num_rows() === 1) {
			$res = $current_semester->row();
			$data['current_academic_year_id'] = $res->id;
			$data['current_academic_year'] = $res->academic_year;
			$data['current_academic_semester'] = $res->semester;
			// $data['academic_year'] = $res->academic_year . ' / ' . ($res->semester == 'odd' ? 'Ganjil':'Genap');
		}

		return $data;
	}

	/**
	 * Get Total Rows
	 * @param 	String
	 * @return 	Int
	 */
	public function total_rows($keyword = '') {
		if (!empty($keyword)) {
			$this->db->like('academic_year', $keyword);
		}
		return $this->db->count_all_results(self::$table);
	}

	/**
	 * Dropdown
	 * @access Public 
	 * @return Array
	 */
	public function dropdown($short = false) {
		$query = $this->db
			->select('id, academic_year')
			->where('is_deleted', 'false')
			->order_by('academic_year', 'ASC')
			->get(self::$table);
		$data = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$data[$row->id] = ($short ? substr($row->academic_year, 0, 4) : $row->academic_year);
			}
		}
		return $data;
	}

	/**
	 * Set not active
	 * @param Int
	 * @return Bool
	 */
	public function set_not_active($id = 0, $field = 'current_semester') {
		if ($id > 0) {
			$this->db->where(self::$pk . ' !=', $id);
		}
		return $this->db->update(self::$table, [$field => 'false']);
	}
}