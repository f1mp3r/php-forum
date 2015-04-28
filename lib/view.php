<?php
class View
{
	protected $_data;
	protected $_file;
	protected $_options;

	public function __construct($file = null, $data = [], $options = ['views_dir' => 'view/']) {
		$this->_options = array_merge(array($this->_options), $options);
		$realfilepath = ROOT_DIR . $options['views_dir'] . $file . '.php';
		if (!file_exists($realfilepath))
		{
			throw new Exception("The file <strong>" . $realfilepath . ".php</strong> doesn't exist.");
		}

		extract($data);
		ob_start();
		include($realfilepath);
		$output = ob_get_contents();
		ob_end_clean();
		echo $output;
	}

	public function __get($name)
	{
		if (array_key_exists($name, $this->_data)) {
			return $this->_data[$name];
		}
	}

	public static function forge($file, $data = [], $views_dir = 'view/')
	{
		return new static($file, $data, ['views_dir' => $views_dir]);
	}
}