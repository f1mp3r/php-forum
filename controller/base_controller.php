<?php
namespace Controllers;

abstract class Base_Controller {
	protected $controller;
	protected $action;
	protected $layout = DEFAULT_LAYOUT;
	protected $viewBag = [];
	protected $viewRendered = false;

	public function __construct($controller, $action) {
		$this->controller = $controller;
		$this->action = $action;
		$this->onInit();
	}

	public function __get($name) {
		if (array_key_exists($name, $this->viewBag)) {
			return $this->viewBag[$name];
		}
		if (property_exists($this, $name)) {
			return $this->$name;
		}
		return null;
	}

	public function user() {
		return \Lib\Auth::get_instance();
	}

	public function __set($name, $value) {
		$this->viewBag[$name] = $value;
	}

	public function index() {
		$this->renderView('front/error', ['message' => '404 Page not found']);
	}

	protected function onInit() {
		// Override this function to initialize the controller
	}

	public function renderView($viewName = null, $data = [], $isPartial = false) {
		if (!isset($this->viewBag['user'])) {
			$this->viewBag['user'] = $this->user();
		}

		if (!$this->viewRendered) {
			if ($viewName == null) {
				$viewName = $this->action;
			}
			
			extract($this->viewBag);
			if (count($data)) {
				extract($data);
			}

			if (!$isPartial) {
				include_once(ROOT_DIR . 'view/layouts/' . $this->layout . '/header.php');
			}

			include_once(ROOT_DIR . 'view/' . $viewName . '.php');

			if (!$isPartial) {
				include_once(ROOT_DIR . 'view/layouts/' . $this->layout . '/footer.php');
			}

			$this->viewRendered = true;
		}
	}

	protected function redirect($controller = null, $action = null, $params = []) {
		if ($controller == null) {
			$controller = $this->controller;
		}
		$url = "$controller/$action";
		$paramsUrlEncoded = array_map('urlencode', $params);
		$paramsJoined = implode('/', $paramsUrlEncoded);
		if ($paramsJoined != '') {
			$url .= '/' . $paramsJoined;
		}
		
		header("Location: " . BASE_URL . $url);
		die;
	}

	public function load_models($models = []) {
		if (!empty($models)) {
			if (is_array($models)) {
				foreach ($models as $modelName) {
					include_once(ROOT_DIR . 'model/' . $modelName . '.php');

					$model_class_name = '\Models\\' . ucfirst($modelName) . '_Model';
					$this->$modelName = new $model_class_name();
				}
			}
		}
	}

	public function load_asset($asset = [], $options = []) {
		if (!isset($this->viewBag) || $this->viewBag == null) {
			$this->viewBag = [];
		}
		if (!isset($this->viewBag['_auto_load_css'])) {
			$this->viewBag['_auto_load_css'] = [];
		}
		if (!isset($this->viewBag['_auto_load_js'])) {
			$this->viewBag['_auto_load_js'] = [];
		}
		if (is_array($asset)) {
			foreach ($asset as $file) {
				$ext = get_ext($file);

				//TODO: implement $options
				switch ($ext) {
					case 'js':
						$html = '<script type="text/javascript" src="' . BASE_URL . 'assets/js/' . $file . '"></script>';
						if (!in_array($html, $this->viewBag['_auto_load_js'])) {
							$this->viewBag['_auto_load_js'][] = $html;
						}
						break;
					case 'css':
						$html = '<link rel="stylesheet" href="' . BASE_URL . 'assets/css/' . $file . '">';
						if (!in_array($html, $this->viewBag['_auto_load_css'])) {
							$this->viewBag['_auto_load_css'][] = $html;
						}
						break;
					
					default:
						die('Not supported asset format.');
						break;
				}
			}
		} else {
			$ext = get_ext($asset);

			//TODO: implement $options
			switch ($ex) {
				case 'js':
					$html = '<script type="text/javascript" src="assets/js/' . $asset . '"></script>';
					if (!in_array($html, $this->viewBag['_auto_load_js'])) {
						$this->viewBag['_auto_load_js'][] = $html;
					}
					break;
				case 'css':
					$html = '<link rel="stylesheet" href="assets/css/' . $asset . '">';
					if (!in_array($html, $this->viewBag['_auto_load_css'])) {
						$this->viewBag['_auto_load_css'][] = $html;
					}
					break;
				
				default:
					die('Not supported asset format.');
					break;
			}
		}
	}
}
