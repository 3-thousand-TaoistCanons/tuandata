<?php
// 本程序自动生成 @2016-06-28 20:47:11
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
	 * 表单主框架
	 * @return
	 */
	function index() {
		
		$allowkeys = [];

		$allowkeys["id"] = (isset($_GET['id']))? trim($_GET['id']) : null;
		$allowkeys["tid"] = (isset($_GET['tid']))? trim($_GET['tid']) : null;

		
		$data = ['query'=>$allowkeys];
		App::render($data, 'H5/datatype','index');

		return [
        	'js' => [
                'js/plugins/jquery-ui/jquery-ui.min.js',
                'js/plugins/jquery-validation/jquery.validate.min.js',
        		'js/plugins/jquery-tags-input/jquery.tagsinput.min.js',
        		'js/plugins/dropzonejs/dropzone.min.js',
        		'js/plugins/cropper/cropper.min.js',
        		'js/plugins/select2/select2.full.js',
        		'js/plugins/jquery-quicksearch/jquery.quicksearch.js',
        		'js/plugins/multi-select/js/jquery.multi-select.js',
        	],

        	'css'=> [
        		'js/plugins/jquery-tags-input/jquery.tagsinput.min.css',
        		'js/plugins/dropzonejs/dropzone.min.css',
        		'js/plugins/cropper/cropper.css',
        		'js/plugins/select2/select2.min.css',
        		'js/plugins/multi-select/css/multi-select.css',
        	],

            'crumb' => [
                "资料类型" => APP::R('Datatype','index'),
                "类型设置" => "",
            ]
        ];
	}

	
	/**
	 * 标签页( 基本信息general  )
	 * @param $_GET['id'] Datatype::_id
	 * @param $_GET['tid'] Datatype::typeid
	 */
	function general(){
		
		$data = [];
		$datatype = App::M('Datatype');

		$_id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : null;
		if ( $_id !== null && !isset($data['inst']) ) {
			$data['inst'] = $datatype->getLine("where _id='$_id' LIMIT 1");
			if ( $data['inst'] == null ) {
				$e = new Excp( '系统错误,请联系管理员。', '500', ['_id'=>$_id]);
				$e->log();
				echo $e->toJSON();
				return;
			}

			if ( method_exists($datatype, 'format') ) {
				$datatype->format($data['inst']);
			}
		} 

		$typeid = (isset($_GET['tid']) && !empty($_GET['tid'])) ? $_GET['tid'] : null;
		if ( $typeid !== null && !isset($data['inst']) ) {
			$data['inst'] = $datatype->getLine("where typeid='$typeid' LIMIT 1");
			if ( $data['inst'] == null ) {
				$e = new Excp( '系统错误,请联系管理员。', '500', ['typeid'=>$typeid]);
				$e->log();
				echo $e->toJSON();
				return;
			}

			if ( method_exists($datatype, 'format') ) {
				$datatype->format($data['inst']);
			}
		}


		// FieldList 
		$fd = App::M('Field');
		$fdConf = $fd->getConf();
		$data['hosts'] = $fdConf['hosts'];


		App::render($data, 'H5/datatype/tabs','general');
	}


	/**
	 * 保存或者更新数据: 标签页( 基本信息general  )
	 */
	function generalDataSave(){

		$model  = (isset($_POST['_model']) ) ? $_POST['_model'] : 'Datatype';
		$md = App::M($model);
		
		
		$_id = (isset($_POST['id'])) ? $_POST['id'] : null;

		$typeid = (isset($_POST['tid'])) ? $_POST['tid'] : null;

		if ($_id === null ) {
	
			if ( !empty($typeid) && $_id==null  ) {
				$_id = $md->getVar('_id', "WHERE typeid='$typeid' LIMIT 1");
			}
		}


		if ( $_id == null ) {
			$resp = $md->create($_POST);
		} else {
			$resp = $md->update($_id, $_POST);
		}

		if ( $resp === false ) {
			$extra = [];
			$errors = (is_array($md->errors)) ? $md->errors : [];

			foreach ($errors as $cname=>$error ) {
				$error = (is_array($error)) ? end($error) : [];
				$field = (isset($error['field'])) ? $error['field'] : 'error';
				$message = (isset($error['message'])) ? $error['message'] : '系统错误,请联系管理员。';
				$extra[] = ['_FIELD'=>$field,'message'=>$message];
			}

			$e = new Excp( '系统错误,请联系管理员。', '500', $extra);
				$e->log();
				echo $e->error->toJSON(); 

			return ;
		}
		
		echo json_encode($resp);
	}

	/**
	 * 标签页( 移动应用app  )
	 * @param $_GET['id'] Datatype::_id
	 * @param $_GET['tid'] Datatype::typeid
	 */
	function app(){
		
		$data = [];
		$datatype = App::M('Datatype');

		$_id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : null;
		if ( $_id !== null && !isset($data['inst']) ) {
			$data['inst'] = $datatype->getLine("where _id='$_id' LIMIT 1");
			if ( $data['inst'] == null ) {
				$e = new Excp( '系统错误,请联系管理员。', '500', ['_id'=>$_id]);
				$e->log();
				echo $e->toJSON();
				return;
			}

			if ( method_exists($datatype, 'format') ) {
				$datatype->format($data['inst']);
			}
		} 

		$typeid = (isset($_GET['tid']) && !empty($_GET['tid'])) ? $_GET['tid'] : null;
		if ( $typeid !== null && !isset($data['inst']) ) {
			$data['inst'] = $datatype->getLine("where typeid='$typeid' LIMIT 1");
			if ( $data['inst'] == null ) {
				$e = new Excp( '系统错误,请联系管理员。', '500', ['typeid'=>$typeid]);
				$e->log();
				echo $e->toJSON();
				return;
			}

			if ( method_exists($datatype, 'format') ) {
				$datatype->format($data['inst']);
			}
		} 


		App::render($data, 'H5/datatype/tabs','app');
	}


	/**
	 * 保存或者更新数据: 标签页( 移动应用app  )
	 *
	 */
	function appDataSave(){

		$model  = (isset($_POST['_model']) ) ? $_POST['_model'] : 'Datatype';
		$md = App::M($model);
		
		
		$_id = (isset($_POST['id'])) ? $_POST['id'] : null;

		$typeid = (isset($_POST['tid'])) ? $_POST['tid'] : null;

		if ($_id === null ) {
	
			if ( !empty($typeid) && $_id==null  ) {
				$_id = $md->getVar('_id', "WHERE typeid='$typeid' LIMIT 1");
			}
		}


		if ( $_id == null ) {
			$resp = $md->create($_POST);
		} else {
			$resp = $md->update($_id, $_POST);
		}

		if ( $resp === false ) {
			$extra = [];
			$errors = (is_array($md->errors)) ? $md->errors : [];

			foreach ($errors as $cname=>$error ) {
				$error = (is_array($error)) ? end($error) : [];
				$field = (isset($error['field'])) ? $error['field'] : 'error';
				$message = (isset($error['message'])) ? $error['message'] : '系统错误,请联系管理员。';
				$extra[] = ['_FIELD'=>$field,'message'=>$message];
			}

			$e = new Excp( '系统错误,请联系管理员。', '500', $extra);
				$e->log();
				echo $e->error->toJSON(); 

			return ;
		}
		
		echo json_encode($resp);
	}

	/**
	 * 标签页( 呈现模板template  )
	 * @param $_GET['id'] Datatype::_id
	 * @param $_GET['tid'] Datatype::typeid
	 */
	function template(){
		
		$data = [];
		$datatype = App::M('Datatype');

		$_id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : null;
		if ( $_id !== null && !isset($data['inst']) ) {
			$data['inst'] = $datatype->getLine("where _id='$_id' LIMIT 1");
			if ( $data['inst'] == null ) {
				$e = new Excp( '系统错误,请联系管理员。', '500', ['_id'=>$_id]);
				$e->log();
				echo $e->toJSON();
				return;
			}

			if ( method_exists($datatype, 'format') ) {
				$datatype->format($data['inst']);
			}
		} 

		$typeid = (isset($_GET['tid']) && !empty($_GET['tid'])) ? $_GET['tid'] : null;
		if ( $typeid !== null && !isset($data['inst']) ) {
			$data['inst'] = $datatype->getLine("where typeid='$typeid' LIMIT 1");
			if ( $data['inst'] == null ) {
				$e = new Excp( '系统错误,请联系管理员。', '500', ['typeid'=>$typeid]);
				$e->log();
				echo $e->toJSON();
				return;
			}

			if ( method_exists($datatype, 'format') ) {
				$datatype->format($data['inst']);
			}
		} 


		App::render($data, 'H5/datatype/tabs','template');
	}


	/**
	 * 保存或者更新数据: 标签页( 呈现模板template  )
	 *
	 */
	function templateDataSave(){

		$model  = (isset($_POST['_model']) ) ? $_POST['_model'] : 'Datatype';
		$md = App::M($model);
		
		
		$_id = (isset($_POST['id'])) ? $_POST['id'] : null;

		$typeid = (isset($_POST['tid'])) ? $_POST['tid'] : null;

		if ($_id === null ) {
	
			if ( !empty($typeid) && $_id==null  ) {
				$_id = $md->getVar('_id', "WHERE typeid='$typeid' LIMIT 1");
			}
		}


		if ( $_id == null ) {
			$resp = $md->create($_POST);
		} else {
			$resp = $md->update($_id, $_POST);
		}

		if ( $resp === false ) {
			$extra = [];
			$errors = (is_array($md->errors)) ? $md->errors : [];

			foreach ($errors as $cname=>$error ) {
				$error = (is_array($error)) ? end($error) : [];
				$field = (isset($error['field'])) ? $error['field'] : 'error';
				$message = (isset($error['message'])) ? $error['message'] : '系统错误,请联系管理员。';
				$extra[] = ['_FIELD'=>$field,'message'=>$message];
			}

			$e = new Excp( '系统错误,请联系管理员。', '500', $extra);
				$e->log();
				echo $e->error->toJSON(); 

			return ;
		}
		
		echo json_encode($resp);
	}



	/**
	 * 读取字段列表
	 * @return [type] [description]
	 */
	function getfield() {

		$fd = App::M('Field');
		$conf = $fd->getConf();

		$hid = isset($_GET['hid'] ) ? intval( $_GET['hid'] ) : null;
		$host = null; $option = [];
		if ( $hid !== null && isset( $conf['hosts'][$hid]) && is_array($conf['hosts'][$hid]) ) {
			$host = $fd->getHost($conf['hosts'][$hid]['host']);
		}
		header('Content-Type: application/json');
		echo json_encode($host);
	}

}