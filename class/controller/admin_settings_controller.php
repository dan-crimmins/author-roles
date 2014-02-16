<?php
namespace Communities\Author_Roles\Controller;

use Communities\Author_Roles\Plugin_Utils;
use Communities\Author_Roles\Model\Users_Model;


class Admin_Settings_Controller {
	
	public $settings_field;
	
	public $options;
	
	public $prefix;
	
	
	public function __construct() {
		
		$this->prefix = SHC_AUTH_ROLES_PREFIX;
		$this->settings_field = $this->prefix . 'settings';
		$this->options = Plugin_Utils::options();
		
		add_action('admin_menu', array(&$this, 'menu'));
		add_action('admin_init', array(&$this, 'register_settings'));
		
		//since we are using a custom capability (not manage_options) for this settings page this is necessary.
		add_filter('option_page_capability_' . $this->settings_field, create_function(null, 'return "activity_report_admin";'));
	}
	
	public function menu() {
	
		add_options_page('Author Roles Settings', 'Author Roles Settings', 'author_roles_admin', 'author-roles-settings', array(&$this, 'settings_page'));
	}
	
	public function register_settings() {
	
		register_setting($this->settings_field, $this->settings_field, array($this, 'settings_save'));
	
		add_settings_section($this->prefix . 'roles_section', __('Author Roles Settings'), array(&$this, 'roles_section'), 'author-roles-settings');
		add_settings_field('roles_dropdown', __('Roles for Authors (choose multiple)'), array(&$this, 'roles_dropdown'), 'author-roles-settings', $this->prefix . 'roles_section');
		
	}
	
	public function settings_page() {
	
		Plugin_Utils::view('admin/settings', array('settings_field' => $this->settings_field,
													'settings_section' => 'author-roles-settings',
													'form_action'		=> 'options.php'));
	}
	
	public function roles_section() {
		
		echo '<p>' . __('Select Author Roles') . '</p>';
	}
	
	public function roles_dropdown($output) {
		
		global $wp_roles;
		$roles = $wp_roles->get_names();
		
		//Get rid of subscriber as an option
		unset($roles['subscriber']);
		
		Plugin_Utils::view('form/input_select', array('name'		=> $this->settings_field . '[author_roles_roles][]',
														'id'		=> $this->prefix . 'author-roles',
														'options'	=> $roles,
														'selected'	=> Plugin_Utils::options('author_roles_roles'),
														'multiple'	=> true));
		
	} 
	
	public function settings_save($settings) {
		
		//Clear users cache
		Users_Model::clearCache();
		
		return $settings;
	}
	
}