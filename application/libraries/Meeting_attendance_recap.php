<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Meeting_attendance_recap extends TCPDF {

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
    	$content = '<table width="100%" border="0" cellpadding="4" cellspacing="0">';
    	$content .= '<tbody>';
    	$content .= '<tr>';
    	$content .= '<td align="left"><b>'.strtoupper($this->CI->session->userdata('school_name')).'</b> | Dicetak tanggal '.indo_date(date('Y-m-d')).' pukul '.date('H:i:s').'</td>';
    	$content .= '</tr>';
    	$content .= '</tbody>';
    	$content .= '</table>';
    	$this->setY(-1);
    	$this->writeHTML($content, true, false, true, false, 'L');
	}

	/**
	 * Create PDF
	 * @param 	Array
	 * @access 	public
	 */
	public function create_pdf(array $params = []) {
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$this->SetAutoPageBreak(TRUE, 1.3);
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// Set Properties
		$this->SetTitle('LAPORAN DATA KEHADIRAN SISWA');
		$this->SetAuthor('PANITIA PPDB TAHUN '.$this->CI->session->userdata('admission_year');
		$this->SetSubject($this->CI->session->userdata('school_name'));
		$this->SetKeywords($this->CI->session->userdata('school_name'));
		$this->SetCreator('PANITIA PPDB TAHUN '.$this->CI->session->userdata('admission_year');
		$this->SetMargins(1, 0, 1, true);
		$this->AddPage();
		$this->SetFont('freesans', '', 10);
		// Get HTML Template
		$content = file_get_contents(VIEWPATH.'teacher/pdf_meeting_attendance_template.html');
		// Header
		$content = str_replace('[TITLE]', 'REKAPITULASI DATA KEHADIRAN SISWA', $content);
		$content = str_replace('[ACADEMIC_SEMESTER]', $params['academic_year'], $content);
		$content = str_replace('[SEMESTER]', $params['semester'], $content);
		$content = str_replace('[DATE]', $params['date'], $content);
		$content = str_replace('[TIME]', $params['time'], $content);
		$content = str_replace('[SUBJECT_NAME]', $params['subject_name'], $content);
		$content = str_replace('[CLASS_GROUP]', $params['class_group'], $content);
		$content = str_replace('[TEACHER]', $params['full_name'], $content);
		$content = str_replace('[DISCUSSION]', $params['discussion'], $content);
		// Meeting Attendances
		$H = $S = $I = $A = 0;
		$no = 1;
		$str = '';
		foreach($params['students'] as $row) {
			$str .= '<tr>';
			$str .= '<td align="center">' . $no . '.</td>';
			$str .= '<td align="center">' . $row['identity_number'] . '</td>';
			$str .= '<td align="left">' . $row['full_name'] . '</td>';
			$str .= '<td align="center">' . $row['gender'] . '</td>';
			$str .= '<td align="center">' . presence($row['presence']) . '</td>';
			$str .= '</tr>';
			$no++;
			if ($row['presence'] == 'present') $H++;
			if ($row['presence'] == 'sick') $S++;
			if ($row['presence'] == 'permit') $I++;
			if ($row['presence'] == 'absent') $A++;
		}
		$str .= '<tr>';
		$str .= '<td align="left" colspan="5">Total : Hadir = '.$H.', Sakit = '.$S.', Izin = '.$I.', Alpa = '.$A.'</td>';
		$str .= '</tr>';
		$content = str_replace('[PESERTA_DIDIK]', $str, $content);
		$this->writeHTML($content, true, false, true, false, 'C');
		$this->Output(FCPATH . 'media_library/meeting_attendances/'.$params['file_name'], 'F');
	}
}

/* End of file Meeting_attendances.php */
/* Location: ./application/libraries/Meeting_attendances.php */
