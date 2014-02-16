<?php
namespace Communities\Author_Roles\Model;

use Communities\Author_Roles\Plugin_Utils;

class Users_Model {
	
	protected $_roles;
	
	private static $_cache_key = 'author_roles_users';
	
	public $users;
	
	public function __construct() {
		
		//get the plugin option containing roles
		$this->_roles = Plugin_Utils::options('author_roles_roles');
		
		$this->_getUsers();
	}
	
	public static function clearCache() {

		delete_option(self::$_cache_key);
	}
	
	protected function _getUsers() {
		
		if($users = $this->_getCache()) {
			
			$this->users = $users;
			
		} else {
			
			$this->_queryUsers();
			$this->_setCache();
		}
		
	}
	
	protected function _queryUsers() {
		
		$users = array();
		
		foreach($this->_roles as $role) {

			if($role != 'subscriber') { //Do not include subscibers
				
				$u = get_users('role=' . $role);
				$users = array_merge($users, $u);
			}
		}
		
		usort($users, array($this, '_sort'));
		$this->users = $users;
	}
	
	protected function _getCache() {
		
		return get_option(self::$_cache_key, null);
	}

	protected function _setCache() {
		
		update_option(self::$_cache_key, $this->users);
	}
	
	protected function _sort($a, $b) {
		
		return strcmp($a->user_login, $b->user_login);
	}
	
}