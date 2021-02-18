<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_students extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'students';

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
			, COALESCE(x1.identity_number, '') identity_number
			, x1.full_name
			, x2.option_name AS student_status
			, x1.gender
			, COALESCE(x1.birth_place, '') birth_place
			, x1.birth_date
			, x1.photo
			, x1.is_deleted
			");
		$this->db->join('options x2', 'x1.student_status_id = x2.id', 'LEFT');
		$this->db->where('x1.is_student', 'true');
		$this->db->where('x1.is_alumni', 'false');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.identity_number', $keyword);
			$this->db->or_like('x2.option_name', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_place', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
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
		$this->db->join('options x2', 'x1.student_status_id = x2.id', 'LEFT');
		$this->db->where('x1.is_student', 'true');
		$this->db->where('x1.is_alumni', 'false');
		if (!empty($keyword)) {
			$this->db->group_start();
			$this->db->like('x1.identity_number', $keyword);
			$this->db->or_like('x2.option_name', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_place', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->group_end();
		}
		return $this->db->count_all_results('students x1');
	}

	/**
	 * Chart by Student Status
	 * @param 	Int
	 * @return 	Array
	 */
	public function chart_by_student_status($academic_year_id) {
		return $this->db->query("
			SELECT x2.`option_name` AS labels
				, COUNT(*) AS data
			FROM students x1
			JOIN `options` x2 ON x1.student_status_id = x2.id
			WHERE x1.is_student='true'
			AND x1.is_alumni='false'
			AND x1.id IN (
				SELECT DISTINCT x1.student_id
				FROM class_group_students x1
				LEFT JOIN class_group_settings x2
					ON x1.class_group_setting_id = x2.id
				WHERE x2.academic_year_id = ?
			)
			GROUP BY 1
			ORDER BY 1 ASC
		", [(int) $academic_year_id]);
	}

	/**
	 * Chart by Class Group
	 * @param 	Int
	 * @return 	Array
	 */
	public function chart_by_class_groups($academic_year_id) {
		return $this->db->query("
			SELECT CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ', x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),'')) AS labels
				, COUNT(*) AS data
			FROM class_group_students x1
			LEFT JOIN class_group_settings x2
				ON x1.class_group_setting_id = x2.id
			LEFT JOIN class_groups x3
				ON x2.class_group_id = x3.id
			LEFT JOIN majors x4
				ON x3.major_id = x4.id
			WHERE x2.academic_year_id = ?
			GROUP BY 1
		", [(int) $academic_year_id]);
	}

	/**
	 * Chart by End Date
	 * @param 	Int
	 * @return 	Array
	 */
	public function chart_by_end_date() {
		return $this->db->query("
			SELECT LEFT(x1.end_date, 4) AS labels
				, COUNT(*) AS data
			FROM students x1
			WHERE x1.is_alumni='true'
			AND x1.is_deleted='false'
			GROUP BY 1
			ORDER BY 1 ASC
		");
	}

	/**
	 * Update student to Alumni
	 * @param 	Array
	 * @param 	String
	 * @return 	Bool
	 */
	public function set_as_alumni($ids, $end_date) {
		$this->load->model('m_student_status');
		$student_status_id = (int) $this->m_student_status->find_student_status_id('lulus');
		$fill_data = [];
		$fill_data['is_alumni'] = 'true';
		$fill_data['end_date'] = $end_date.'-05-01';
		if ($student_status_id > 0) {
			$fill_data['student_status_id'] = $student_status_id;
		}
		return $this->db
			->where_in('id', $ids)
			->update(self::$table, $fill_data);
	}

	/**
	 * Student Reports
	 * @access 	public
	 * @return 	Resource
	 */
	public function student_reports() {
		$query = $this->student_query();
		$query .= "
			AND x1.is_student='true'
			AND x1.is_alumni='false'
			AND x1.is_prospective_student='false'
			ORDER BY x1.identity_number ASC
		";
		return $this->db->query($query);
	}

	/**
	 * Student Query
	 * @return String
	 */
	public function student_query() {
		return "
			SELECT x1.id
				, x2.major_name AS program_keahlian
				, x3.major_name AS pilihan_1
				, x4.major_name AS pilihan_2
				, x1.registration_number AS nomor_pendaftaran
				, x1.created_at AS tanggal_pendaftaran
				, x1.admission_exam_number AS admission_exam_number
				, x1.prev_exam_number AS nomor_peserta_ujian
				, CASE WHEN x1.selection_result IS NULL THEN 'Belum Diseleksi'
					WHEN x1.selection_result = 'approved' THEN 'Diterima'
					WHEN x1.selection_result = 'unapproved' THEN 'Tidak Diterima'
					WHEN x1.selection_result > 0 THEN (SELECT major_name FROM majors WHERE id = x1.selection_result)
					ELSE '-'
					END AS hasil_seleksi
				, x5.phase_name AS gelombang_pendaftaran
				, x6.admission_type AS jalur_pendaftaran
				, x1.photo
				, IF(x1.is_transfer = 'true', 'Pindahan', 'Baru') AS jenis_pendaftaran
				, x1.achievement AS prestasi
				, IF(x1.re_registration = 'true', 'Ya', 'Tidak') AS daftar_ulang
				, x1.start_date AS tanggal_masuk
				, x1.identity_number
				, x1.nisn
				, x1.nik
				, x1.prev_diploma_number AS nomor_ijazah_sebelumnya
				, IF(x1.paud = 'true', 'Ya', 'Tidak') AS paud
				, IF(x1.tk = 'true', 'Ya', 'Tidak') AS tk
				, x1.skhun
				, x1.prev_school_name AS nama_sekolah_asal
				, x1.prev_school_address AS alamat_sekolah_asal
				, x1.hobby AS hobi
				, x1.ambition AS cita_cita
				, x1.full_name AS nama_lengkap
				, IF(x1.gender = 'M', 'Laki-laki', 'Perempuan') AS jenis_kelamin
				, x1.birth_place AS tempat_lahir
				, x1.birth_date AS tanggal_lahir
				, x7.option_name AS agama
				, x8.option_name AS kebutuhan_khusus
				, x1.street_address AS alamat_jalan
				, x1.rt
				, x1.rw
				, x1.sub_village AS nama_dusun
				, x1.village AS kelurahan
				, x1.sub_district AS kecamatan
				, x1.district AS kabupaten
				, x1.postal_code AS kode_pos
				, x9.option_name AS jenis_tinggal
				, x10.option_name AS moda_transportasi
				, x1.phone AS telp
				, x1.mobile_phone AS handphone
				, x1.email
				, x1.sktm
				, x1.kks
				, x1.kps
				, x1.kip
				, x1.kis
				, x1.citizenship AS kewarganegaraan
				, x1.country AS nama_negara
				, x1.father_name AS nama_ayah
				, x1.father_birth_year AS tahun_lahir_ayah
				, x11.option_name AS pendidikan_ayah
				, x12.option_name AS pekerjaan_ayah
				, x13.option_name AS penghasilan_ayah
				, x14.option_name AS kebutuhan_khusus_ayah
				, x1.mother_name AS nama_ibu
				, x1.mother_birth_year AS tahun_lahir_ibu
				, x15.option_name AS pendidikan_ibu
				, x16.option_name AS pekerjaan_ibu
				, x17.option_name AS penghasilan_ibu
				, x18.option_name AS kebutuhan_khusus_ibu
				, x1.guardian_name AS nama_wali
				, x1.guardian_birth_year AS tahun_lahir_wali
				, x19.option_name AS pendidikan_wali
				, x20.option_name AS pekerjaan_wali
				, x21.option_name AS penghasilan_wali
				, x1.mileage AS jarak_tempuh_sekolah
				, x1.traveling_time AS waktu_tempuh_sekolah
				, x1.height AS tinggi_badan
				, x1.weight AS berat_badan
				, x1.sibling_number AS jumlah_saudara_kandung
				, x22.option_name AS status_siswa
				, x1.end_date AS tanggal_keluar
				, x1.reason AS alasan_keluar
			FROM students x1
			LEFT JOIN majors x2 ON x1.major_id = x2.id
			LEFT JOIN majors x3 ON x1.first_choice_id = x3.id
			LEFT JOIN majors x4 ON x1.second_choice_id = x4.id
			LEFT JOIN admission_phases x5 ON x1.admission_phase_id = x5.id
			LEFT JOIN admission_types x6 ON x1.admission_type_id = x6.id
			LEFT JOIN options x7 ON x1.religion_id = x7.id
			LEFT JOIN options x8 ON x1.special_need_id = x8.id
			LEFT JOIN options x9 ON x1.residence_id = x9.id
			LEFT JOIN options x10 ON x1.transportation_id = x10.id
			LEFT JOIN options x11 ON x1.father_education_id = x11.id
			LEFT JOIN options x12 ON x1.father_employment_id = x12.id
			LEFT JOIN options x13 ON x1.father_monthly_income_id = x13.id
			LEFT JOIN options x14 ON x1.father_special_need_id = x14.id
			LEFT JOIN options x15 ON x1.mother_education_id = x15.id
			LEFT JOIN options x16 ON x1.mother_employment_id = x16.id
			LEFT JOIN options x17 ON x1.mother_monthly_income_id = x17.id
			LEFT JOIN options x18 ON x1.mother_special_need_id = x18.id
			LEFT JOIN options x19 ON x1.guardian_education_id = x19.id
			LEFT JOIN options x20 ON x1.guardian_employment_id = x20.id
			LEFT JOIN options x21 ON x1.guardian_monthly_income_id = x21.id
			LEFT JOIN options x22 ON x1.student_status_id = x22.id
			WHERE 1=1
		";
	}

	/**
	 * get_active_students
	 * @return Resource
	 */
	public function get_active_students() {
		return $this->db
			->select('id, identity_number, full_name, email')
			->where('is_student', 'true')
			->where('is_deleted', 'false')
			->get(self::$table);
	}

	/**
	 * Student Profile
	 * @param 	Int
	 * @return 	Resource
	 */
	public function profile($id) {
		$query = $this->student_query();
		$query .= '
		AND x1.id = ?
		';
		return $this->db->query($query, [(int) $id])->row();
	}
}
