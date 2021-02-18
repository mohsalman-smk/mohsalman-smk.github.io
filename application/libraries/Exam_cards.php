<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Exam_cards extends TCPDF {

	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var object
	 */
	protected $CI;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct('P', 'Cm', 'A4', true, 'UTF-8', false);
		$this->CI = &get_instance();
	}

	/**
	 * Overide Header
	 */
	public function Header() {

	}

	/**
	 * Overide Footer
	 */
	public function Footer() {

	}

	/**
	 * Create PDF
	 * @param 	String
	 * @param 	Resource
	 * @param 	Resource
	 * @access 	public
	 */
	public function create_pdf($file_name, $students, $schedules) {
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$this->SetAutoPageBreak(TRUE, 1.6);
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// Set Properties
		$this->SetTitle('DAFTAR HADIR UJIAN TES TULIS PENERIMAAN '.strtoupper($this->CI->session->userdata('_student')).' BARU TAHUN '.$this->CI->session->userdata('admission_year'));
		$this->SetAuthor('PANITIA PPDB TAHUN '.$this->CI->session->userdata('admission_year');
		$this->SetSubject($this->CI->session->userdata('school_name'));
		$this->SetKeywords($this->CI->session->userdata('school_name'));
		$this->SetCreator('PANITIA PPDB TAHUN '.$this->CI->session->userdata('admission_year');
		$this->SetMargins(0.3, 0.3, 0.3, true);
		$this->AddPage();
		$this->SetFont('freesans', '', 10);

		// Get HTML Template
		$content = file_get_contents(VIEWPATH.'admission/pdf_exam_card_template.html');
		// Header
		$content = str_replace('[LOGO]', base_url('media_library/images/'.$this->CI->session->userdata('logo')), $content);
		$content = str_replace('[SCHOOL_NAME]', strtoupper($this->CI->session->userdata('school_name')), $content);
		$content = str_replace('[SCHOOL_STREET_ADDRESS]', $this->CI->session->userdata('street_address'), $content);
		$content = str_replace('[SCHOOL_PHONE]', $this->CI->session->userdata('phone'), $content);
		$content = str_replace('[STUDENT_TYPE]', $this->CI->session->userdata('school_level') >= 5 ? 'MAHASISWA' : 'PESERTA DIDIK', $content);
		$content = str_replace('[SCHOOL_FAX]', $this->CI->session->userdata('fax'), $content);
		$content = str_replace('[SCHOOL_POSTAL_CODE]', $this->CI->session->userdata('postal_code'), $content);
		$content = str_replace('[SCHOOL_EMAIL]', $this->CI->session->userdata('email'), $content);
		$content = str_replace('[SCHOOL_WEBSITE]', str_replace(['http://', 'https://', 'www.'], '', $this->CI->session->userdata('website')), $content);
		$content = str_replace('[REGISTRATION_NUMBER]', $students->registration_number, $content);
		$content = str_replace('[YEAR]', substr($students->registration_number, 0, 4), $content);
		$content = str_replace('[FULL_NAME]', $students->full_name, $content);
		$content = str_replace('[GENDER]', $students->gender == 'M' ? 'Laki-laki' : 'Perempuan', $content);
		$content = str_replace('[TTL]', $students->birth_place .', '. indo_date($students->birth_date), $content);
		$content = str_replace('[FOOTER_DATE]', $this->CI->session->userdata('district').', '. indo_date(date('Y-m-d')), $content);
		$content = str_replace('[HEADMASTER]', $this->CI->session->userdata('headmaster'), $content);
		$content = str_replace('[ANNOUNCEMENT_START_DATE]', indo_date($this->CI->session->userdata('announcement_start_date')), $content);
		$content = str_replace('[ANNOUNCEMENT_END_DATE]', indo_date($this->CI->session->userdata('announcement_end_date')), $content);
		$content = str_replace('[WEBSITE]', $this->CI->session->userdata('website'), $content);
		// Foto
		if ($students->photo && file_exists('./media_library/students/'.$students->photo)) {
  			$content = str_replace('[FOTO]', '<img src="'.base_url('media_library/students/'.$students->photo).'" width="80px">', $content);
		} else {
			$content = str_replace('[FOTO]', '<table border="1" cellspacing="0" cellpadding="0" width="80px" align="center"><tr><td><br><br><br><br>Foto<br>2 x 3<br><br><br></td></tr></table>', $content);
		}
		$str = '';
		foreach($schedules->result() as $row) {
			$str .= '<tr>';
			$str .= '<td align="left">'.date('d - m - Y', strtotime($row->exam_date)).'</td>';
			$str .= '<td align="left">'.substr($row->exam_start_time, 0, 5).' s.d '.substr($row->exam_end_time, 0, 5).'</td>';
			$str .= '<td align="left">'.$row->subject_name.'</td>';
			$str .= '<td align="left">Gedung ' . str_replace(['Gedung', 'gedung'], '', $row->building_name).' Ruang '. str_replace(['Ruang', 'ruang'], '', $row->room_name).'</td>';
			$str .= '</tr>';
		}
		$content = str_replace('[SCHEDULES]', $str, $content);
		$this->writeHTML($content, true, false, true, false, 'C');
		$this->Output(FCPATH . 'media_library/students/'.$file_name, 'F');
	}
}

/* End of file Exam_cards.php */
/* Location: ./application/libraries/Exam_cards.php */
