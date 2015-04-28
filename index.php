<?php
	include_once('config/config.php');
	include_once('system/autoload.php');
	include_once('lib/view.php');
	include_once('controller/master_controller.php');

	define('DS', '/');
	define('ROOT_DIR', dirname( __FILE__ ) . DS);
	define('ROOT_PATH', basename( dirname( __FILE__ ) ) . DS);

	$request_home = DS . ROOT_PATH;
	$request = $_SERVER['REQUEST_URI'];
	$components = array();
	$controller = 'Master';
	$method = 'index';
	$admin_routing = false;
	$param = array();

	$master_controller = new \Controllers\Master_Controller();

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
				
				$param = isset( $components[2] ) ? $components[2] : array();
			}
		}
	}

	if (isset($controller) && file_exists('controller/' . $controller . '.php')) {
		$admin_folder = $admin_routing ? 'admin/' : '';
		include_once('controller/' . $admin_folder . $controller . '.php');

		$admin_namespace = $admin_routing ? '\Admin' : '';
		
		// Form the controller class
		$controller_class = $admin_namespace . '\Controllers\\' . ucfirst( $controller ) . '_Controller';
		$instance = new $controller_class();
		
		// Call the object and the method
		if( method_exists( $instance, $method ) ) {
			call_user_func_array(array($instance, $method), array($param));
		} else {
			call_user_func_array(array($instance,'index'),array());
		}
	} else {
		$master_controller->home();
	}