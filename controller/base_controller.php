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

	public function __set($name, $value) {
		$this->viewBag[$name] = $value;
	}

	protected function onInit() {
		// Override this function to initialize the controller
	}

	public function index() {
		$this->renderView();
	}

	public function renderView($viewName = null, $isPartial = false) {
		if (!$this->viewRendered) {
			if ($viewName == null) {
				$viewName = $this->action;
			}
			extract($this->viewBag);
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
		$url = "/$controller/$action";
		$paramsUrlEncoded = array_map('urlencode', $params);
		$paramsJoined = implode('/', $paramsUrlEncoded);
		if ($paramsJoined != '') {
			$url = $url . '/' . $paramsJoined;
		}
		header("Location: $url");
		die;
	}
}
