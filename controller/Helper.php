<?php
require_once('loader/Scaffold.php');

use \Tuanduimao\Loader\App as App;
use \Tuanduimao\Utils as Utils;
use \Tuanduimao\Tuan as Tuan;
use \Tuanduimao\Excp as Excp;
use \Tuanduimao\Conf as Conf;


class HelperController extends \Tuanduimao\Loader\Scaffold {
	
	function __construct() {
		$md = (isset($_GET['model_name']) && !empty($_GET['model_name']))? trim($_GET['model_name']) : 'datatype';
		parent::__construct("$md");
	}


	function tmp_field_general() {
		$json_text = file_get_contents(App::$APP_ROOT . "/config/field-server-sample.json");
		header('Content-Type: application/json');
		echo $json_text;
	}

	function tmp_field_estate() {
		$json_text = file_get_contents(App::$APP_ROOT . "/config/field-server-sample.json");
		header('Content-Type: application/json');
		echo $json_text;
	}
}