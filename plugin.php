<?php
/*
 	Plugin Name: Author Roles

	Description: Configure other user roles to populate the author dropdown on posts edit page.
	Author: Dan Crimmins
	Version: 0.1
*/
use Communities\Author_Roles\Plugin_Utils;
use Communities\Author_Roles\Controller\Author_Roles_Controller;
use Communities\Author_Roles\Model\Users_Model;

//Plugin paths
define('SHC_AUTH_ROLES_PATH', WP_PLUGIN_DIR . '/author-roles/');
define('SHC_AUTH_ROLES_CLASS', SHC_AUTH_ROLES_PATH . 'class/');
define('SHC_AUTH_ROLES_VIEW', SHC_AUTH_ROLES_PATH . 'view/');

define('SHC_AUTH_ROLES_PREFIX', 'shc-author-roles');

require_once SHC_AUTH_ROLES_CLASS . 'plugin_utils.php';

spl_autoload_register(array('Communities\Author_Roles\Plugin_Utils', 'autoload'));


//Install / Uninstall
register_activation_hook(__FILE__, array('Communities\Author_Roles\Plugin_Utils', 'install'));
register_deactivation_hook(__FILE__, array('Communities\Author_Roles\Plugin_Utils', 'uninstall'));


Plugin_Utils::init();
new Author_Roles_Controller(new Users_Model);



