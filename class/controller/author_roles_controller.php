<?php
namespace Communities\Author_Roles\Controller;

use Communities\Author_Roles\Model\Users_Model;
use Communities\Author_Roles\Plugin_Utils;


class Author_Roles_Controller {
	
	protected $_users;
	
	protected $_options;
	
	public function __construct(Users_Model $users) {
		
		
		$this->_users = $users;
		$this->_setOptions();
		
		add_filter('wp_dropdown_users', array(&$this, 'author_dropdown'));
		
	}
		
	public function author_dropdown($output) {
		
		
		global $post;
		
		$output = Plugin_Utils::view('form/input_select', array('name'		=>	'post_author_override',
															'id'		=>	'post_author_override',
															'options' 	=>	$this->_options,
															'selected'	=>	$post->post_author),
													true);
		
		return $output;
	}
	
	protected function _setOptions() {
		
		$this->_options['1'] = 'Administrator';
		
		foreach($this->_users->users as $user) {
			
			$this->_options[$user->ID] = $user->user_login;
		}
	}
}