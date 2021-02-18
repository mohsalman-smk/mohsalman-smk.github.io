<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author     moh.salman| https://instagram.com/moh.salman | smk.nurja@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

/**
 * Get Active Theme
 */
if (! function_exists('theme_folder')) {
	function theme_folder() {
		$CI = &get_instance();
		return $CI->session->userdata('theme');
	}
}

/**
 * Get Links
 */
if (! function_exists('get_links')) {
	function get_links() {
		$CI = &get_instance();
		$CI->load->model('public/m_links');
		return $CI->m_links->get_links();
	}
}

/**
 * Get Tags
 */
if (! function_exists('get_tags')) {
	function get_tags() {
		$CI = &get_instance();
		$CI->load->model('public/m_tags');
		return $CI->m_tags->get_tags(10, TRUE);
	}
}

/**
 * Get Banners
 */
if (! function_exists('get_banners')) {
	function get_banners() {
		$CI = &get_instance();
		$CI->load->model('public/m_banners');
		return $CI->m_banners->get_banners();
	}
}

/**
 * Get Archive Year
 */
if (! function_exists('get_archive_year')) {
	function get_archive_year() {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_archive_year();
	}
}

/**
 * Get Archive
 * @param Int
 */
if (! function_exists('get_archives')) {
	function get_archives($year) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_archives($year);
	}
}

/**
 * Get Quotes
 */
if (! function_exists('get_quotes')) {
	function get_quotes() {
		$CI = &get_instance();
		$CI->load->model('public/m_quotes');
		return $CI->m_quotes->get_quotes();
	}
}

/**
 * Get service_area
 */
if (! function_exists('get_service_area')) {
	function get_service_area() {
		$CI = &get_instance();
		$CI->load->model('public/m_service_area');
		return $CI->m_service_area->get_service_area();
	}
}

/**
 * Get counter_area
 */
if (! function_exists('get_counter_area')) {
	function get_counter_area() {
		$CI = &get_instance();
		$CI->load->model('public/m_counter_area');
		return $CI->m_counter_area->get_counter_area();
	}
}

/**
 * Get Image Sliders
 */
if (! function_exists('get_image_sliders')) {
	function get_image_sliders() {
		$CI = &get_instance();
		$CI->load->model('public/m_image_sliders');
		return $CI->m_image_sliders->get_image_sliders();
	}
}

/**
 * Get Image Sliders
 */
if (! function_exists('get_student_say')) {
	function get_student_say() {
		$CI = &get_instance();
		$CI->load->model('public/m_student_say');
		return $CI->m_student_say->get_student_say();
	}
}

/**
 * Get Image Sliders
 */
if (! function_exists('get_lecturers_area')) {
	function get_lecturers_area() {
		$CI = &get_instance();
		$CI->load->model('public/m_lecturers_area');
		return $CI->m_lecturers_area->get_lecturers_area();
	}
}

/**
 * Get Question
 */
if (! function_exists('get_active_question')) {
	function get_active_question() {
		$CI = &get_instance();
		$CI->load->model('public/m_questions');
		return $CI->m_questions->get_active_question();
	}
}

/**
 * Get Answears
 * @param Int
 */
if (! function_exists('get_answers')) {
	function get_answers($question_id) {
		$CI = &get_instance();
		$CI->load->model('public/m_answers');
		return $CI->m_answers->get_answers($question_id);
	}
}

/**
 * Get Recent Posts
 * @access  Public
 * @param Int
 * @return Resource
 */
if (! function_exists('get_recent_posts')) {
	function get_recent_posts($limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_recent_posts($limit);
	}
}

/**
 * Get Recent event
 * @access  Public
 * @param Int
 * @return Resource
 */
if (! function_exists('get_recent_event')) {
	function get_recent_event($limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_event');
		return $CI->m_event->get_recent_event($limit);
	}
}

/**
 * Get Recent announcement
 * @access  Public
 * @param Int
 * @return Resource
 */
if (! function_exists('get_recent_announcement')) {
	function get_recent_announcement($limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_announcement');
		return $CI->m_announcement->get_recent_announcement($limit);
	}
}

/**
 * Get Popular Posts
 * @access  Public
 * @param Int
 * @return Resource
 */
if (! function_exists('get_popular_posts')) {
	function get_popular_posts($limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_popular_posts($limit);
	}
}

/**
 * Get Most Commented
 * @access  Public
 * @param Int
 * @return Resource
 */
if (! function_exists('get_most_commented')) {
	function get_most_commented($limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_most_commented($limit);
	}
}

/**
 * Get Random Posts
 * @access  Public
 * @param Int
 * @return Resource
 */
if (! function_exists('get_random_posts')) {
	function get_random_posts($limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_random_posts($limit);
	}
}

/**
 * Get post category
 * @param 	Int
 * @param 	Int
 * @access 	public
 * @return 	Resource
 */
if (! function_exists('get_post_category')) {
	function get_post_category($id, $limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_post_category($id, $limit);
	}
}

/**
 * Get All Post Categories
 * @access  Public
 * @return Resource
 */
if (! function_exists('get_post_categories')) {
	function get_post_categories($limit = 6) {
		$CI = &get_instance();
		$CI->load->model('m_post_categories');
		return $CI->m_post_categories->get_post_categories($limit);
	}
}

/**
 * Get Related Posts
 * @param 	String
 * @param 	Int
 * @access 	public
 * @return 	Query
 */
if (! function_exists('get_related_posts')) {
	function get_related_posts($get_categories, $id) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_related_posts($get_categories, $id);
	}
}

/**
 * Get Recent Comments
 * @access  Public
 * @param Int
 * @return Resource
 */
if (! function_exists('get_recent_comments')) {
	function get_recent_comments($limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_post_comments');
		return $CI->m_post_comments->get_recent_comments($limit);
	}
}

/**
 * Welcome | Sambutan Kepala Sekolah
 * @access  Public
 * @return String
 */
if (! function_exists('get_welcome')) {
	function get_welcome() {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_welcome();
	}
}

/**
 * Get Recent Videos
 * @access  Public
 * @return 	String
 */
if (! function_exists('get_videos')) {
	function get_videos($limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_videos');
		return $CI->m_videos->get_videos($limit);
	}
}

/**
 * Get All Albums
 * @access  Public
 * @return 	Resource
 */
if (! function_exists('get_albums')) {
	function get_albums($limit) {
		$CI = &get_instance();
		$CI->load->model('public/m_albums');
		return $CI->m_albums->get_albums($limit);
	}
}

/**
 * recursive list
 */
if (!function_exists('cosmo_recursive_list')) {
	function cosmo_recursive_list($menus) {
		$str = '';
		foreach ($menus as $menu) {
			$url = base_url() . $menu['menu_url'];
			if ($menu['menu_type'] == 'link') {
				$url = $menu['menu_url'];
			}
			$str .= '<li>';
			$subchild = cosmo_recursive_list($menu['child']);
			$str .= anchor($url, $menu['menu_title'].($subchild?' <span class="caret"></span>':''), 'target="'.$menu['menu_target'].'"');
			if ($subchild) {
				$str .= "<ul class='dropdown-menu'>" . $subchild . "</ul>";
			}
			$str .= "</li>";
		}
		return $str;
	}
}

/**
 * recursive list for magazine Template
 */
if (!function_exists('magazine_recursive_list')) {
	function magazine_recursive_list($menus) {
		$str = '';
		foreach ($menus as $menu) {
			$url = base_url() . $menu['menu_url'];
			if ($menu['menu_type'] == 'link') {
				$url = $menu['menu_url'];
			}
			$str .= '<li>';
			$subchild = magazine_recursive_list($menu['child']);
			$str .= anchor($url, $menu['menu_title'], 'target="'.$menu['menu_target'].'"');
			if ($subchild) {
				$str .= "<ul>" . $subchild . "</ul>";
			}
			$str .= "</li>";
		}
		return $str;
	}
}

/**
 * Routes | Forewords From Head
 * @access  Public
 * @return 	String
 */

if (! function_exists('forewords_route')) {
	function forewords_route() {
		$CI = &get_instance();
		$level = (int) $CI->session->userdata('school_level');
		if ( $level == 5 ) return 'sambutan-rektor';
		else if ( $level == 6 ) return 'sambutan-ketua';
		else if ( $level == 7 ) return 'sambutan-direktor';
		else return 'sambutan-kepala-sekolah';
	}
}
