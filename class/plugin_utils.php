<?php
namespace Communities\Author_Roles;

class Plugin_Utils {

	public static $_option_name;

	public static $_option_defaults = array('author_roles_roles' => array('author'));

	/**
	 * $_classes - Array of classes to load on init
	 * @var array
	 * @see init()
	 */
	public static $_classes = array('author_roles_settings'		=> 'Communities\Author_Roles\Controller\Admin_Settings_Controller');
		


	public static function option_name() {

		self::$_option_name = SHC_AUTH_ROLES_PREFIX . 'settings';
	}

	//class autoloader
	public static function autoload($class) {

		$class_dir = SHC_AUTH_ROLES_CLASS;

		$class_parts = explode('\\', $class);
		$index = count($class_parts) - 1;
		$file = strtolower(trim($class_parts[$index])) . '.php';


		//Check class root dir first
		if(file_exists($class_dir . $file)) {

			require_once $class_dir . $file;

		} else {

			//Get all sub-dirs in class root dir
			$dirs = scandir($class_dir);

			if($dirs) {
					
				$exclude = array('...', '..', '.');
					
				foreach($dirs as $dir) {

					if(is_dir($class_dir . $dir) && ! in_array($dir, $exclude)) {

						if(is_file($class_dir . $dir . '/' . $file)) {

							require_once $class_dir . $dir . '/' . $file;
							return;
								
						}
					}


				}

			}

		}

	}

	public static function view($view, array $args = null, $return = false) {

		$file = SHC_AUTH_ROLES_VIEW . $view . '.php';


		if($args !== null)
			extract($args, EXTR_SKIP);
			
		ob_start();

		if(is_file($file)) {

			include $file;
		}

		if(! $return) {

			echo ob_get_clean();

		} else {

			return ob_get_clean();
		}

	}

	/**
	 * options() - Sets and gets plugin options.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed - [array | string | NULL]
	 */
	public static function options($name = null, $value = null) {

		//Set option prefix property
		self::option_name();

		//Get plugin options
		$options = get_option(self::$_option_name);

		//Return entire settings array
		if($name === null && $value === null && $options) {

			return $options;
		}

		//Get a specific element from options
		if((($name !== null && ! is_array($name)) && $value === null) && isset($options[$name]) ) {

			return $options[$name];
		}

		//Set plugin options - all
		if(($name !== null && is_array($name)) && $value === null) {

			return update_option(self::$_option_name, $value);
		}

		//Set, update value of one element of options array
		if($name !== null && $value !== null) {

			$options[$name] = $value;

			return update_option(self::$_option_name, $options);
		}

		return null;
	}

	/**
	 * init() - Used to instantiate objects of classes with init hooks (ie. Admin stuff)
	 *
	 * @param void
	 * @return void
	 */
	public static function init() {

		foreach(self::$_classes as $var=>$class) {

			$$var = new $class();
		}
	}

	protected function _add_capabilities() {

		$role = get_role('administrator');

		$role->add_cap('author_roles_admin');

	}

	public static function install() {
			
		update_option(SHC_AUTH_ROLES_PREFIX . 'settings', self::$_option_defaults);

		self::_add_capabilities();

		flush_rewrite_rules();
	}

	public static function uninstall() {
			
		delete_option(SHC_AUTH_ROLES_PREFIX . 'settings');
	}




}