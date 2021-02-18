<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_admission_subject_settings extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'admission_subject_settings';

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
	 * @param String
	 * @param Int
	 * @param Int
	 * @param String
	 * @return Resource
	 */
	public function get_where($keyword = '', $limit = 0, $offset = 0, $subject_type = 'semester_report') {
		$fields = ['x1.id', 'x2.academic_year', 'x3.admission_type', 'x1.is_deleted'];
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			array_push($fields, "COALESCE(x4.major_name, '-') AS major_name");
		}
		$this->db->select(implode(', ', $fields));
		$this->db->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
		$this->db->join('admission_types x3', 'x1.admission_type_id = x3.id', 'LEFT');
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->join('majors x4', 'x1.major_id = x4.id', 'LEFT');
		}
		$this->db->where('x1.subject_type', $subject_type); // Nilai Rapor Sekolah
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.academic_year', $keyword);
			$this->db->or_like('x3.admission_type', $keyword);
			if (in_array($this->session->userdata('school_level'), have_majors())) {
				$this->db->or_like('x4.major_name', $keyword);
			}
			$this->db->group_end();
		}
		if ($limit > 0) {
			$this->db->limit($limit, $offset);
		}
		return $this->db->get(self::$table.' x1');
	}

	/**
	 * Get Total Rows
	 * @param String
	 * @param String
	 * @return Int
	 */
	public function total_rows($keyword = '', $subject_type = 'semester_report') {
		$this->db->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
		$this->db->join('admission_types x3', 'x1.admission_type_id = x3.id', 'LEFT');
		if (in_array($this->session->userdata('school_level'), have_majors())) {
			$this->db->join('majors x4', 'x1.major_id = x4.id', 'LEFT');
		}
		$this->db->where('x1.subject_type', $subject_type); // Nilai Rapor Sekolah
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x2.academic_year', $keyword);
			$this->db->or_like('x3.admission_type', $keyword);
			if (in_array($this->session->userdata('school_level'), have_majors())) {
				$this->db->or_like('x4.major_name', $keyword);
			}
			$this->db->group_end();
		}
		return $this->db->count_all_results(self::$table.' x1');
	}




	/**
	 * Get Subject Settings
	 * @param Int
	 * @param Int
	 * @return Array
	 */
	public function get_subject_settings($admission_type_id, $major_id = 0, $subject_type = 'semester_report', $visibility = '') {
		$data = [];
		$this->db->select('id');
		$this->db->where('academic_year_id', $this->session->userdata('admission_semester_id'));
		$this->db->where('admission_type_id', $admission_type_id);		
		$this->db->where('major_id', $major_id);
		$this->db->where('subject_type', $subject_type);
		$this->db->where('is_deleted', 'false');
		$query = $this->db->get(self::$table);
		if ($query->num_rows() === 1) {
			$result = $query->row();
			$this->db->select('x1.id, x2.subject_name');
			$this->db->join('subjects x2', 'x1.subject_id = x2.id', 'LEFT');
			$this->db->where('x1.subject_setting_id', $result->id);
			$this->db->where('x1.is_deleted', 'false');
			// Jika Private, hanya diisi dari administrator saja
			if ($visibility == 'public') {
				$this->db->where('x1.visibility', 'public');	
			}
			$subjects = $this->db->get('admission_subject_setting_details x1');
			foreach ($subjects->result() as $row) {
				$data[] = [
					'subject_setting_detail_id' => $row->id,
					'subject_name' => $row->subject_name
				];
			}
		}
		return $data;
	}
}