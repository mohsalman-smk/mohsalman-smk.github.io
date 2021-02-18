<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */


/**
 * copyright
 * @return string
 */
if (!function_exists('copyright')) {
	function copyright($year = '', $link = '', $company = '') {
		if ($year != '') {
			if (strlen($year) != 4 || !is_numeric($year)) {
				return;
			}
		}
		$start = $year == '' ? date('Y') : $year;
		define('CREATED', $start);
		$string = 'Copyright &copy; ';
		$string .= date('Y') > CREATED ? CREATED . ' - ' . date('Y') : CREATED;
		$string .= '<a href="';
		$string .= $link == '' ? base_url() : $link;
		$string .= '"> ';
		$string .= $company == '' ? str_replace(array('http://', 'https://'), '', rtrim(base_url(), '/')) : $company;
		$string .= '</a>';
		$string .= ' All right reserved.';
		return $string;
	}
}

/**
 * filesize_formatted
 * @return string
 */
if (!function_exists('filesize_formatted')) {
	function filesize_formatted($size) {
		$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
	}
}

/**
 * create_dir
 * @return string
 */
if (!function_exists('create_dir')) {
	function create_dir($dir) {
		if (!is_dir($dir)) {
			if (!mkdir($dir, 0777, true)) {
				die('Not create directory : ' . $dir);
			}
		}
	}
}

/**
 * extract_themes
 * @return string
 */
if (! function_exists('extract_themes')) {
	function extract_themes() {
		$zip = new ZipArchive;
		$zip->open('./views/themes/default.zip');
		@chmod(FCPATH . 'views/themes', 0775);
		$zip->extractTo('./views/themes/');
		@chmod(FCPATH . 'views/themes/default/', 0775);
		$zip->close();
	}
}

/**
 * datasource
 * @return string
 */
if (! function_exists('datasource')) {
	function datasource($group = '') {
		$CI = &get_instance();
		$query = $CI->db
			->select('id, option_name')
			->where('option_group', $group)
			->order_by('option_name', 'ASC')
			->get('options');
		$data = [];
		foreach($query->result() as $row) {
			$data[$row->id] = $row->option_name;
		}
		return json_encode($data);
	}
}

/**
 * Have Majors
 * @return Array
 */
if (! function_exists('have_majors')) {
	function have_majors() {
		// '1 - Sekolah Dasar (SD)/ Sederajat', // SD
		// '2 - Sekolah Menengah Pertama (SMP)/ Sederajat', // SMP
		// '3 - Sekolah Menengah Atas (SMA) / Aliyah', // SMA
		// '4 - Sekolah Menengah Kejuruan (SMK)', // SMK
		// '5 - Universitas', // Universitas
		// '6 - Sekolah Tinggi', // Sekolah Tinggi
		// '7 - Politeknik' // Politeknik
		return [3, 4, 5, 6, 7];
	}
}

/**
 * encode_str
 * @return string
 */
if (!function_exists('encode_str')) {
	function encode_str($str) {
		$CI = &get_instance();
		$CI->load->library('encrypt');
		$ret = $CI->encrypt->encode($str, $CI->config->item('encryption_key'));
		$ret = strtr($ret, array('+' => '.', '=' => '-', '/' => '~'));
		return $ret;
	}
}

/**
 * decode_str
 * @return string
 */
if (!function_exists('decode_str')) {
	function decode_str($str) {
		$CI = &get_instance();
		$CI->load->library('encrypt');
		$str = strtr($str, array('.' => '+', '-' => '=', '~' => '/'));
		return $CI->encrypt->decode($str, $CI->config->item('encryption_key'));
	}
}

/**
 * indo_date
 * @return string
 */
if (!function_exists('indo_date')) {
	function indo_date($date) {
		if (is_valid_date($date)) {
			$parts = explode("-", $date);
			$result = $parts[2] . ' ' . bulan($parts[1]) . ' ' . $parts[0];
			return $result;
		}
		return '';
	}
}

/**
 * english_date
 * @return string
 */
if (!function_exists('english_date')) {
	function english_date($date) {
		if (is_valid_date($date)) {
			$parts = explode("-", $date);
			$result = $parts[2] . ', ' . month($parts[1]) . ' ' . $parts[0];
			return $result;
		}
		return '';
	}
}

/**
 * Day Name
 * @return string
 */
if (! function_exists('day_name')) {
	function day_name($idx) {
		$arr = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu'];
		return $arr[$idx];
	}
}

/**
 * is_valid_date
 * @return string
 */
if (!function_exists('is_valid_date')) {
	function is_valid_date($date) {
		if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
			return checkdate($parts[2], $parts[3], $parts[1]) ? true : false;
		}
		return false;
	}
}

/**
 * bulan
 * @return string
 */
if (!function_exists('bulan')) {
	function bulan($key = '') {
		$data = [
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		];
		return $key === '' ? $data : $data[$key];
	}
}

/**
 * month
 * @return string
 */
if (!function_exists('month')) {
	function month($key = '') {
		$data = [
			'01' => 'January',
			'02' => 'February',
			'03' => 'March',
			'04' => 'April',
			'05' => 'May',
			'06' => 'June',
			'07' => 'July',
			'08' => 'August',
			'09' => 'September',
			'10' => 'October',
			'11' => 'November',
			'12' => 'December',
		];
		return $key === '' ? $data : $data[$key];
	}
}

/**
 * get_ip_address
 * @return string
 */
if (! function_exists('get_ip_address')) {
	function get_ip_address() {
		return getenv('HTTP_X_FORWARDED_FOR') ? getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');
	}
}

/**
 * check_internet_connection
 * @return bool
 */
if (! function_exists('check_internet_connection')) {
	function check_internet_connection() {
		return checkdnsrr('google.com');
	}
}

/**
 * array_date
 * @return array
 */
if ( ! function_exists('array_date')) {
   function array_date($start, $end) {
      $range = [];
      if (is_valid_date($start))
         $start = strtotime($start);
      if (is_valid_date($end))
         $end = strtotime($end);
      if ($start > $end)
         return array_date($end, $start);
      do {
         $range[] = date('Y-m-d', $start);
         $start = strtotime("+ 1 day", $start);
      }
      while($start <= $end);
      return $range;
   }
}

/**
 * delete_cache
 * @return void
 */
if (! function_exists('delete_cache')) {
	function delete_cache() {
		$CI = &get_instance();
		$CI->load->helper('directory');
		$path = APPPATH . 'cache';
		$files = directory_map($path, FALSE, TRUE);
		foreach ($files as $file) {
			if ($file !== 'index.html' && $file !== '.htaccess') {
				@chmod($path . '/' . $file, 0777);
				@unlink($path . '/' . $file);
			}
		}
	}
}

/**
 * Alpha Dash
 * Check if a-z or 0-9 or _-
 * @param String
 * @return Bool
 */
if (! function_exists('alpha_dash')) {
	function alpha_dash($string) {
		return (bool) preg_match('/^[A-Z0-9 ]+$/i', $string);
	}
}

if (! function_exists('presence')) {
	function presence($str) {
		if ($str == 'present') return 'H';
		if ($str == 'sick') return 'S';
		if ($str == 'permit') return 'I';
		if ($str == 'absent') return 'A';
		return '';
	}
}

/**
 * Slugify
 * @param String
 * @return String
 */
if (! function_exists('slugify')) {
	function slugify($string) {
		return preg_replace('/\s+/', '-', mb_strtolower(trim(strip_tags($string)), 'UTF-8'));
	}
}

/**
 * Timezone List
 * @return String
 */
if (! function_exists('timezone_list')) {
	function timezone_list() {
		static $regions = array(DateTimeZone::ASIA);
	    $timezones = array();
	    foreach( $regions as $region ) {
	        $timezones = array_merge($timezones, DateTimeZone::listIdentifiers($region));
	    }
	    $timezone_offsets = array();
	    foreach($timezones as $timezone) {
	        $tz = new DateTimeZone($timezone);
	        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
	    }
	    asort($timezone_offsets);
	    $timezone_list = array();
	    foreach( $timezone_offsets as $timezone => $offset ) {
	        $offset_prefix = $offset < 0 ? '-' : '+';
	        $offset_formatted = gmdate( 'H:i', abs($offset) );
	        $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
	        $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
	    }
	    return $timezone_list;
	}
}

if (! function_exists('subject_desc')) {
	function subject_desc($key = '') {
		$subject = [
			'semester_report' => 'Nilai Rapor Sekolah',
			'national_exam' => 'Nilai Ujian Nasional',
			'exam_schedule' => 'Nilai Tes Tulis'
		];

		return ! empty($key) ? $subject[ $key ] : '';
	}
}
