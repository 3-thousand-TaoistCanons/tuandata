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


class DatatypeController extends \Tuanduimao\Loader\Controller {
	
	function __construct() {
	}


	/**
	 * 所有类型列表
	 * @return [type] [description]
	 */
	function index() {

		$data = [];
		App::render($data, 'h5/datatype','index');
		return [
        	'js' => [
                'js/plugins/jquery-ui/jquery-ui.min.js',
                'js/plugins/jquery-validation/jquery.validate.min.js',
        		'js/plugins/dropzonejs/dropzone.min.js',
        		'js/plugins/cropper/cropper.min.js',
        		'js/plugins/bootstrap-treeview/bootstrap-treeview.js'
        	],

        	'css'=> [
        		'js/plugins/dropzonejs/dropzone.min.css',
        		'js/plugins/cropper/cropper.css',
        		'js/plugins/bootstrap-treeview/bootstrap-treeview.css',
        	],

            'crumb' => [
                "资料类型" => APP::R('datatype','index'),
                "类型设置" => "",
            ]
        ];
	}

}