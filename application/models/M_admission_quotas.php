<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_admission_quotas extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'admission_quotas';

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
		$this->db->select("x1.id, x2.academic_year, x3.admission_type, COALESCE(x4.major_name, '-') AS major_name, x1.quota, x1.is_deleted");
		$this->db->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
		$this->db->join('admission_types x3', 'x1.admission_type_id = x3.id', 'LEFT');
		$this->db->join('majors x4', 'x1.major_id = x4.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x2.academic_year', $keyword);
			$this->db->or_like('x3.admission_type', $keyword);
			$this->db->or_like('x4.major_name', $keyword);
			$this->db->or_like('x1.quota', $keyword);
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
		$this->db->join('admission_types x3', 'x1.admission_type_id = x3.id', 'LEFT');
		$this->db->join('majors x4', 'x1.major_id = x4.id', 'LEFT');
		if (!empty($keyword)) {
			$this->db->like('x2.academic_year', $keyword);
			$this->db->or_like('x3.admission_type', $keyword);
			$this->db->or_like('x4.major_name', $keyword);
			$this->db->or_like('x1.quota', $keyword);
		}			
		return $this->db->count_all_results(self::$table.' x1');
	}
}