<?php

namespace Controllers;

class Master_Controller
{
	protected $layout = 'template.php';

	protected $views_dir = '/view/master/';
	
	protected $model = null;
	
	protected $class_name = null;
	
	protected $logged_user = array();
	
	public function __construct( $class_name = '\Controller\Master_Controller', $model = 'master', $views_dir = '/view/' ) {
		// Get caller classes
		$this->class_name = $class_name;
		$this->views_dir = $views_dir;
		
		$this->views_dir = $views_dir;

		if ($model !== null) {
			$this->model = $model;
			include_once ROOT_DIR . "model/{$model}.php";
			$model_class = "\Models\\" . ucfirst( $model ) . "_Model";  
			
			$this->model = new $model_class( array( 'table' => 'none' ) );
		}
	}
	
	public function home() {
		$template_file = ROOT_DIR . $this->views_dir . 'home.php';
		
		include_once ROOT_DIR . 'view/' . $this->layout;
	}
	
	public function index() {
		echo "Default view from Master_Controller <br />";
	}
}