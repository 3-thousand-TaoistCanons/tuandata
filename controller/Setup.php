<?php
require_once('loader/Controller.php');
require_once('loader/App.php');
require_once('lib/Utils.php');
require_once('lib/Tuan.php');

use \Tuanduimao\Loader\App as App;
use \Tuanduimao\Utils as Utils;
use \Tuanduimao\Tuan as Tuan;
use \Tuanduimao\Excp as Excp;
use \Tuanduimao\Conf as Conf;


class SetupController extends \Tuanduimao\Loader\Controller {
	
	function __construct() {
	}

	function install() {
		echo json_encode('ok');
	}

	function upgrade(){
		echo json_encode('ok');	
	}

	function repair() {
		echo json_encode('ok');		
	}

	function uninstall() {
		echo json_encode('ok');		
	}
	
}