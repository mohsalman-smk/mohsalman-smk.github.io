<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMK Nurul Jadid
 * @version    v4.6.2
 * @author      Moh salman| https://instagram.com/mohsalman | mohsalman@gmail.com
 * @copyright  (c) 2021-2022
 * @link       http://smknj.sch.id
 * @since      Version v4.6.2
 */

class M_menus extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'menus';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Fungsi untuk menu recursive
	 * @param  Int
	 * @return Array
	 */
	public function parent_menus($parent_id = 0) {
		$menus = [];
		$this->db->where('menu_parent_id', $parent_id);
		$this->db->order_by('menu_position', 'ASC');
		$result = $this->db->get(self::$table);
		foreach ($result->result() as $row) {
			$menus[] = [
				'id' => $row->id,
				'menu_title' => $row->menu_title,
				'child' => $this->parent_menus($row->id),
			];
		}
		return $menus;
	}

	/**
	 * Recursive function for save menu position
	 * @return Void
	 */
	public function update_position($parent_id, $children) {
		$i = 1;
		foreach ($children as $key => $value) {
			$id = $children[$key]['id'];
			$fill_data = [
				'menu_parent_id' => $parent_id,
				'menu_position' => $i
			];
			$this->db->where(self::$pk, $id)->update(self::$table, $fill_data);
			if (isset($children[$key]['children'][0])) {
				$this->update_position($id, $children[$key]['children']);
			}
			$i++;
		}
	}

	/**
	 * Get All Menus
	 * @return Resource
	 */
	public function get_all() {
		return $this->db
			->select('id, menu_title, menu_url, menu_type, is_deleted')
			->order_by('menu_parent_id', 'ASC')
			->order_by('menu_position', 'ASC')
			->get(self::$table);
	}

	/**
	 * Check if child exist
	 * @param Int
	 * @return Bool
	 */
	public function is_child_exist($parent_id) {
		$query = $this->db
			->where('menu_parent_id', $parent_id)
			->count_all_results(self::$table);
		return $query > 0;
	}

	/**
	 * Fungsi untuk menu recursive : TOP Navigasi
	 */
	public function get_parent_menu($parent_id = 0) {
		$menu = [];
		$this->db->select('
			id
			, menu_title
			, menu_url
			, menu_target
			, menu_type
	  	');
		$this->db->from('menus');
		$this->db->where('menu_parent_id', $parent_id);
		$this->db->where('is_deleted', 'false');
		$this->db->order_by('menu_position', 'ASC');
		$this->db->order_by('menu_title', 'ASC');
		$result = $this->db->get();
		foreach ($result->result() as $row) {
			$menu[] = [
				'id' => $row->id,
				'menu_title' => $row->menu_title,
				'menu_url' => $row->menu_url,
				'menu_target' => $row->menu_target,
				'menu_type' => $row->menu_type,
				'child' => $this->get_parent_menu($row->id),
			];
		}
		return $menu;
	}
}