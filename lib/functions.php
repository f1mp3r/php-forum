<?php
function time_ago($time)
{
	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
	$lengths = array("60","60","24","7","4.35","12","10");

	$now = time();
	$difference = $now - $time;

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
	}

	$difference = round($difference);

	if($difference != 1) {
		 $periods[$j].= "s";
	}

	return $difference . ' ' . $periods[$j];
}

function is_valid($var, $format = null, $min_len = 0, $max_len = PHP_INT_MAX, $var_name = 'The value') {
	$errors = [];

	if ($min_len == null) {
		$min_len = 0;
	}
	if ($max_len == null) {
		$max_len = PHP_INT_MAX;
	}

	switch ($format) {
		case 'email':
			if (filter_var($var, FILTER_VALIDATE_EMAIL) === false) {
				$errors[] = $var_name . ' is not a valid email.';
			}
			break;

		case 'number':
			if (!is_numeric($var)){
				$errors[] = $var_name . ' is not a number.';
			}
			break;

		default:
			if ($format != null) {
				if (!preg_match($format, $var)) {
					$errors[] = $var_name . ' does not match the requirements.';
				}
			}
			break;
	}

	if (strlen($var) < $min_len) {
		$errors[] = $var_name . ' should be at least ' . $min_len . ' symbols.';
	}

	if (strlen($var) > $max_len) {
		$errors[] = $var_name . ' should be no longer than ' . $max_len . ' symbols.';
	}

	return $errors;
}

function get_ext($file) {
	$expl = explode('.', $file);
	$ext = end($expl);
	return $ext;
}

function clean($var) {
	return addslashes(htmlspecialchars($var));
}