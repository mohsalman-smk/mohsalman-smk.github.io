<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class Admission extends TCPDF {

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
    	$content .= '<td align="left" width="60%">Simpanlah lembar pendaftaran ini sebagai bukti pendaftaran Anda.</td>';
    	$content .= '<td align="right" width="40%">Dicetak tanggal '.indo_date(date('Y-m-d')).' pukul '.date('H:i:s').'</td>';
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
	public function create_pdf(array $result) {
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$this->SetAutoPageBreak(TRUE, 1);
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// Set Properties
		$this->SetTitle('FORMULIR PENERIMAAN '.strtoupper($this->CI->session->userdata('_student')).' BARU TAHUN '.$this->CI->session->userdata('admission_year'));
		$this->SetAuthor('PANITIA PPDB '.strtoupper($this->CI->session->userdata('school_name')).' TAHUN '.$this->CI->session->userdata('admission_year'));
		$this->SetSubject($this->CI->session->userdata('school_name'));
		$this->SetKeywords($this->CI->session->userdata('school_name'));
		$this->SetCreator('PANITIA PPDB '.strtoupper($this->CI->session->userdata('school_name')).' TAHUN '.$this->CI->session->userdata('admission_year'));
		$this->SetMargins(2, 1, 2, true);
		$this->AddPage();
		$this->SetFont('freesans', '', 10);

		// Get HTML Template
		$content = file_get_contents(VIEWPATH.'admission/pdf_admission_template.html');
		// Header
		$content = str_replace('[LOGO]', base_url('media_library/images/'.$this->CI->session->userdata('logo')), $content);
		$content = str_replace('[SCHOOL_NAME]', strtoupper($this->CI->session->userdata('school_name')), $content);
		$content = str_replace('[SCHOOL_STREET_ADDRESS]', $this->CI->session->userdata('street_address'), $content);
		$content = str_replace('[SCHOOL_PHONE]', $this->CI->session->userdata('phone'), $content);
		$content = str_replace('[SCHOOL_FAX]', $this->CI->session->userdata('fax'), $content);
		$content = str_replace('[SCHOOL_POSTAL_CODE]', $this->CI->session->userdata('postal_code'), $content);
		$content = str_replace('[SCHOOL_EMAIL]', $this->CI->session->userdata('email'), $content);
		$content = str_replace('[SCHOOL_WEBSITE]', str_replace(['http://', 'https://', 'www.'], '', $this->CI->session->userdata('website')), $content);
		$content = str_replace('[TITLE]', 'Formulir Penerimaan ' . ucfirst(strtolower($this->CI->session->userdata('_student'))) .' Baru Tahun '.$this->CI->session->userdata('admission_year'), $content);
		// Registrasi Peserta Didik
		$content = str_replace('[STUDENT_TYPE]', ($this->CI->session->userdata('school_level') >= 5 ? 'Calon Mahasiswa' : 'Calon Peserta Didik'), $content);
		$content = str_replace('[IS_TRANSFER]', ($result['is_transfer'] == 'true' ? 'Pindahan':'Baru'), $content);
		$content = str_replace('[ADMISSION_TYPE]', $result['admission_type'], $content);
		$content = str_replace('[REGISTRATION_NUMBER]', $result['registration_number'], $content);
		$content = str_replace('[CREATED_AT]', $result['created_at'], $content);
		if (!filter_var($this->CI->session->userdata('form_first_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">Pilihan I</td><td align="center">:</td><td align="left">[FIRST_CHOICE]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[FIRST_CHOICE]', $result['first_choice'], $content);
		}
		if (!filter_var($this->CI->session->userdata('form_second_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">Pilihan II</td><td align="center">:</td><td align="left">[SECOND_CHOICE]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[SECOND_CHOICE]', $result['second_choice'], $content);
		}
		if (!filter_var($this->CI->session->userdata('form_prev_school_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">Nama Sekolah Asal</td><td align="center">:</td><td align="left">[PREV_SCHOOL_NAME]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[PREV_SCHOOL_NAME]', $result['prev_school_name'], $content);
		}
		if (!filter_var($this->CI->session->userdata('form_prev_school_address')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">Alamat Sekolah Asal</td><td align="center">:</td><td align="left">[PREV_SCHOOL_ADDRESS]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[PREV_SCHOOL_ADDRESS]', $result['prev_school_address'], $content);
		}

		// Profile
		$content = str_replace('[FULL_NAME]', $result['full_name'], $content);
		$content = str_replace('[GENDER]', $result['gender'], $content);
		if (!filter_var($this->CI->session->userdata('form_nisn')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">NISN</td><td align="center">:</td><td align="left">[NISN]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[NISN]', $result['nisn'], $content);
		}
		if (!filter_var($this->CI->session->userdata('form_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">NIK</td><td align="center">:</td><td align="left">[NIK]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[NIK]', $result['nik'], $content);
		}
		$content = str_replace('[BIRTH_PLACE]', $result['birth_place'], $content);
		$content = str_replace('[BIRTH_DATE]', indo_date($result['birth_date']), $content);
		$content = str_replace('[RELIGION]', $result['religion'], $content);
		$content = str_replace('[SPECIAL_NEEDS]', $result['special_needs'], $content);
		// Alamat
		$content = str_replace('[STREET_ADDRESS]', $result['street_address'], $content);
		$content = str_replace('[RT]', $result['rt'], $content);
		$content = str_replace('[RW]', $result['rw'], $content);
		$content = str_replace('[SUB_VILLAGE]', $result['sub_village'], $content);
		$content = str_replace('[VILLAGE]', $result['village'], $content);
		$content = str_replace('[SUB_DISTRICT]', $result['sub_district'], $content);
		$content = str_replace('[DISTRICT]', $result['district'], $content);
		$content = str_replace('[POSTAL_CODE]', $result['postal_code'], $content);
		$content = str_replace('[EMAIL]', $result['email'], $content);
		$content = str_replace('[FOOTER_DATE]', $result['district'].', '. indo_date(substr($result['created_at'], 0, 10)), $content);
		$content = str_replace('[FOOTER_FULL_NAME]', $result['full_name'], $content);
		$file_name = 'formulir-penerimaan-'. ($this->CI->session->userdata('school_level') >= 5 ? 'mahasiswa' : 'peserta-didik').'-baru-tahun-'.$this->CI->session->userdata('admission_year');
		$file_name .= '-'.$result['birth_date'].'-'.$result['registration_number'].'.pdf';
		$this->writeHTML($content, true, false, true, false, 'C');
		$this->Output(FCPATH . 'media_library/students/'.$file_name, 'F');
	}

	/**
	 * Generating PDF
	 * @param 	Array
	 * @access 	public
	 */
	public function blank_pdf(array $params) {
		$CI = &get_instance();
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$this->SetAutoPageBreak(TRUE, 1);
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// Set Properties
		$this->SetTitle('FORMULIR PENERIMAAN '.strtoupper($this->CI->session->userdata('_student')).' BARU TAHUN '.$this->CI->session->userdata('admission_year'));
		$this->SetAuthor('PANITIA PPDB '.strtoupper($this->CI->session->userdata('school_name')).' TAHUN '.$this->CI->session->userdata('admission_year'));
		$this->SetSubject($this->CI->session->userdata('school_name'));
		$this->SetKeywords($this->CI->session->userdata('school_name'));
		$this->SetCreator('PANITIA PPDB '.strtoupper($this->CI->session->userdata('school_name')).' TAHUN '.$this->CI->session->userdata('admission_year'));
		$this->SetMargins(2, 1, 2, true);
		$this->AddPage();
		$this->SetFont('freesans', '', 10);

		// Get HTML Template
		$content = file_get_contents(VIEWPATH.'admission/pdf_admission_template.html');
		// Header
		$content = str_replace('[LOGO]', base_url('media_library/images/'.$this->CI->session->userdata('logo')), $content);
		$content = str_replace('[SCHOOL_NAME]', strtoupper($this->CI->session->userdata('school_name')), $content);
		$content = str_replace('[SCHOOL_STREET_ADDRESS]', $this->CI->session->userdata('street_address'), $content);
		$content = str_replace('[SCHOOL_PHONE]', $this->CI->session->userdata('phone'), $content);
		$content = str_replace('[SCHOOL_FAX]', $this->CI->session->userdata('fax'), $content);
		$content = str_replace('[SCHOOL_POSTAL_CODE]', $this->CI->session->userdata('postal_code'), $content);
		$content = str_replace('[SCHOOL_EMAIL]', $this->CI->session->userdata('email'), $content);
		$content = str_replace('[SCHOOL_WEBSITE]', str_replace(['http://', 'https://', 'www.'], '', $this->CI->session->userdata('website')), $content);
		$content = str_replace('[TITLE]', 'Formulir Penerimaan ' . strtoupper($this->CI->session->userdata('_student')).' Baru Tahun '.$this->CI->session->userdata('admission_year'), $content);
		$dotted = '.................................................................................................................';
		$content = str_replace('[STUDENT_TYPE]', strtoupper($this->CI->session->userdata('_student')), $content);
		$content = str_replace('[IS_TRANSFER]', 'Baru / Pindahan', $content);
		$content = str_replace('[ADMISSION_TYPE]', (count($params['admission_types']) > 0 ? implode(' / ', $params['admission_types']) : $dotted), $content);
		$content = str_replace('[REGISTRATION_NUMBER]', $dotted, $content);
		$content = str_replace('[CREATED_AT]', $dotted, $content);
		// Registrasi Peserta Didik
		if (!filter_var($this->CI->session->userdata('form_first_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">Pilihan I</td><td align="center">:</td><td align="left">[FIRST_CHOICE]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[FIRST_CHOICE]', $dotted, $content);
		}

		if (!filter_var($this->CI->session->userdata('form_second_choice_id')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">Pilihan II</td><td align="center">:</td><td align="left">[SECOND_CHOICE]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[SECOND_CHOICE]', $dotted, $content);
		}

		// Sekolah Asal
		if (!filter_var($this->CI->session->userdata('form_prev_school_name')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">Nama Sekolah Asal</td><td align="center">:</td><td align="left">[PREV_SCHOOL_NAME]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[PREV_SCHOOL_NAME]', $dotted, $content);
		}

		// Alamat Sekolah Asal
		if (!filter_var($this->CI->session->userdata('form_prev_school_address')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">Alamat Sekolah Asal</td><td align="center">:</td><td align="left">[PREV_SCHOOL_ADDRESS]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[PREV_SCHOOL_ADDRESS]', $dotted, $content);
		}

		// Profile
		$content = str_replace('[FULL_NAME]', $dotted, $content);
		$content = str_replace('[GENDER]', 'Laki-laki / Perempuan', $content);
		if (!filter_var($this->CI->session->userdata('form_nisn')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">NISN</td><td align="center">:</td><td align="left">[NISN]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[NISN]', $dotted, $content);
		}
		if (!filter_var($this->CI->session->userdata('form_nik')['admission'], FILTER_VALIDATE_BOOLEAN)) {
			$replace = '<tr><td align="right">NIK</td><td align="center">:</td><td align="left">[NIK]</td></tr>';
			$content = str_replace($replace, '', $content);
		} else {
			$content = str_replace('[NIK]', $dotted, $content);
		}
		$content = str_replace('[BIRTH_PLACE]', $dotted, $content);
		$content = str_replace('[BIRTH_DATE]', $dotted, $content);
		$content = str_replace('[RELIGION]', (count($params['religions']) > 0 ? implode(' / ', $params['religions']) : $dotted), $content);
		$content = str_replace('[SPECIAL_NEEDS]', (count($params['special_needs']) > 0 ? implode(' / ', $params['special_needs']) : $dotted), $content);
		// Alamat
		$content = str_replace('[STREET_ADDRESS]', $dotted, $content);
		$content = str_replace('[RT]', $dotted, $content);
		$content = str_replace('[RW]', $dotted, $content);
		$content = str_replace('[SUB_VILLAGE]', $dotted, $content);
		$content = str_replace('[VILLAGE]', $dotted, $content);
		$content = str_replace('[SUB_DISTRICT]', $dotted, $content);
		$content = str_replace('[DISTRICT]', $dotted, $content);
		$content = str_replace('[POSTAL_CODE]', $dotted, $content);
		$content = str_replace('[EMAIL]', $dotted, $content);
		$content = str_replace('[FOOTER_DATE]', '.............................................., ............. .................................... ' . $this->CI->session->userdata('admission_year'), $content);
		$content = str_replace('[FOOTER_FULL_NAME]', '....................................................................', $content);
		$file_name = 'formulir-penerimaan-'. ($this->CI->session->userdata('school_level') >= 5 ? 'mahasiswa' : 'peserta-didik').'-baru-tahun-'.$this->CI->session->userdata('admission_year');
		$file_name = strtoupper(str_replace(' ', '-', $file_name)).'.pdf';
		$this->writeHTML($content, true, false, true, false, 'C');
		$this->Output(__DIR__.'../../media_library/students/'.$file_name, 'I');
	}
}

/* End of file Admission.php */
/* Location: ./application/libraries/Admission.php */
