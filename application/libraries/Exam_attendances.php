<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Exam_attendances extends TCPDF {

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
		parent::__construct('P', 'Cm', 'F4', true, 'UTF-8', false);
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
    	$content = '<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-top:1px solid #000000;">';
    	$content .= '<tbody>';
    	$content .= '<tr>';
    	$content .= '<td align="right">Dicetak tanggal '.indo_date(date('Y-m-d')).' pukul '.date('H:i:s').'</td>';
    	$content .= '</tr>';
    	$content .= '</tbody>';
    	$content .= '</table>';
    	$this->setY(-1);
    	$this->writeHTML($content, true, false, true, false, 'L');
	}

	/**
	 * Create PDF
	 * @param 	Object
	 * @param 	Resource
	 * @access 	public
	 */
	public function create_pdf($header, $students) {
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
		$this->SetMargins(1, 1, 1, true);
		$this->AddPage();
		$this->SetFont('freesans', '', 10);

		// Get HTML Template
		$content = file_get_contents(VIEWPATH.'admission/pdf_exam_attendance_template.html');
		// Header
		$content = str_replace('[LOGO]', base_url('media_library/images/'.$this->CI->session->userdata('logo')), $content);
		$content = str_replace('[SCHOOL_NAME]', strtoupper($this->CI->session->userdata('school_name')), $content);
		$content = str_replace('[SCHOOL_STREET_ADDRESS]', $this->CI->session->userdata('street_address'), $content);
		$content = str_replace('[SCHOOL_PHONE]', $this->CI->session->userdata('phone'), $content);
		$content = str_replace('[SCHOOL_FAX]', $this->CI->session->userdata('fax'), $content);
		$content = str_replace('[SCHOOL_POSTAL_CODE]', $this->CI->session->userdata('postal_code'), $content);
		$content = str_replace('[SCHOOL_EMAIL]', $this->CI->session->userdata('email'), $content);
		$content = str_replace('[SCHOOL_WEBSITE]', str_replace(['http://', 'https://', 'www.'], '', $this->CI->session->userdata('website')), $content);
		$content = str_replace('[TITLE]', 'DAFTAR HADIR UJIAN TES TULIS<br>PENERIMAAN ' . strtoupper($this->CI->session->userdata('_student')).' BARU TAHUN '.$this->CI->session->userdata('admission_year'), $content);
		// Registrasi Peserta Didik
		$content = str_replace('[ADMISSION_SEMESTER]', $header->academic_year, $content);
		$content = str_replace('[EXAM_DATE]', indo_date($header->exam_date), $content);
		$content = str_replace('[ADMISSION_TYPE]', $header->admission_type, $content);
		$content = str_replace('[EXAM_TIME]', substr($header->exam_start_time, 0, 5).' s.d '. substr($header->exam_end_time, 0, 5), $content);
		$content = str_replace('[SUBJECT_NAME]', $header->subject_name, $content);
		$content = str_replace('[EXAM_LOCATION]', 'Gedung ' . str_replace(['Gedung', 'gedung'], '', $header->building_name).' Ruang '. str_replace(['Ruang', 'ruang'], '', $header->room_name), $content);
		if (in_array($this->CI->session->userdata('school_level'), have_majors())) {
			$content = str_replace('[MAJOR_LABEL]', $this->CI->session->userdata('_major'), $content);
			$content = str_replace('[MAJOR_LABEL]', 'Program Keahlian', $content);
			$content = str_replace('[MAJOR_SEPARATOR]', ':', $content);
			$content = str_replace('[MAJOR_NAME]', (isset($header->major_name) ? $header->major_name : '-'), $content);
		} else {
			$content = str_replace('[MAJOR_LABEL]', '', $content);
			$content = str_replace('[MAJOR_SEPARATOR]', '', $content);
			$content = str_replace('[MAJOR_NAME]', '', $content);
		}
		$content = str_replace('[COUNT]', $students->num_rows().' orang', $content);
		if ($students->num_rows() > 0) {
			$str = '<table width="100%" border="1" cellpadding="8" cellspacing="0"><tbody>';
			$no = 1;
			foreach($students->result() as $row) {
				$str .= '<tr>';
				$str .= '<td width="8%" align="right">'.$no.'.</td>';
				$str .= '<td width="20%" align="center" valign="center">'.$row->registration_number.'</td>';
				$str .= '<td width="42%" align="left" valign="center">'.$row->full_name.'</td>';
				$str .= '<td width="15%"></td>';
				$str .= '<td width="15%"></td>';
				$str .= '</tr>';
				$no++;
			}
			$str .= '</table>';
			$content = str_replace('[STUDENTS]', $str, $content);
		}
		$file_name = 'daftar-hadir-ujian-tes-tulis-penerimaan-'. url_title(strtolower($this->CI->session->userdata('_student')), '-').'-baru-tahun-'.$this->CI->session->userdata('admission_year').'.pdf';
		$this->writeHTML($content, true, false, true, false, 'C');
		$this->Output(FCPATH . 'media_library/students/'.$file_name, 'F');
	}
}

/* End of file Exam_attendances.php */
/* Location: ./application/libraries/Exam_attendances.php */
