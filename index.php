<?php
	include_once('config/config.php');
	include_once('system/autoload.php');
	include_once('controller/base_controller.php');
	include_once('controller/home.php');
	date_default_timezone_set ( DEFAULT_TIMEZONE );
	define('DS', '/');
	define('ROOT_DIR', dirname(__FILE__) . DS);
	define('ROOT_PATH', basename(dirname(__FILE__)) . DS);

	// include stuff from autoload
	foreach ($packages as $name => $location) {
		try {
			include_once($location);
		}
		catch (Exception $e) {
			throw new Exception('Could not load ' . $name . ':<br />' . $e);
		}
	}

	//FC pattern
	$request_home = DS . ROOT_PATH;
	$request = $_SERVER['REQUEST_URI'];
	$components = array();
	$controller = 'Home';
	$method = 'index';
	$admin_routing = false;
	$param = array();

	$db_object = \Lib\Database::get_instance();
	$db_conn = $db_object::get_db();
	include_once('model/master.php');

	if (!empty($request)) {
		if (strpos($request, $request_home) !== false) {
			$request = substr($request, strpos($request, $request_home) + strlen($request_home));
			
			if( 0 === strpos( $request, 'admin' ) ) {
				$admin_routing = true;
				include_once 'controllers/admin/admin_controller.php';
				$request = substr( $request, strlen( 'admin/' ) );
			}
			
			$components = explode( DS, $request, 3 );
			if (count($components) > 1) {
				list($controller, $method) = $components;
				
				$param = isset( $components[2] ) ? explode('/', urldecode($components[2])) : array();
			}
		}
	}

	if (isset($controller) && file_exists('controller/' . $controller . '.php')) {
		$admin_folder = $admin_routing ? 'admin/' : '';
		include_once('controller/' . $admin_folder . $controller . '.php');

		$admin_namespace = $admin_routing ? '\Admin' : '';
		
		// Form the controller class
		$controller_class = $admin_namespace . '\Controllers\\' . ucfirst( $controller ) . '_Controller';
		$instance = new $controller_class($controller_class, $method);
		
		// Call the object and the method
		if( method_exists( $instance, $method ) ) {
			call_user_func_array(array($instance, $method), $param);
		} else {
			call_user_func_array(array($instance,'index'),array());
		}
	} else {
		$controller_class = '\Controllers\\' . HOME_CONTROLLER;
		$instance = new $controller_class($controller_class, DEFAULT_METHOD);
		call_user_func_array(array($instance, DEFAULT_METHOD), []);
	}