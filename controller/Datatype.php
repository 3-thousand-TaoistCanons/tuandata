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
		$allowkeys["first"] = (isset($_GET['first']))? trim($_GET['first']) : null;


		// 读取类型清单
		$dt = App::M('Datatype');
		$datatype = $dt->select('LIMIT 50', ['name','cname','typeid']);
		$datatype['data'] = (isset($datatype['data']) && is_array($datatype['data'])) ? $datatype['data'] : [];
		$datatype['total'] = (isset($datatype['total'])) ? intval($datatype['total']) : 0;

		// 默认: 如果有类型，first 参数不为空, 则选中第一个, 
		if ( $datatype['total'] > 0 && $allowkeys["first"] != null ) {
			$allowkeys["tid"] = current($datatype['data'])['typeid'];
		}


		$data = ['query'=>$allowkeys, 'datatype'=>$datatype ];
		App::render($data, 'h5/datatype','index');

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
		$data['host'] = $fd->getHost( $data['hosts'][0]['host'] );

		$data['fields'] = ["04ea55eaf475bdecb44fbe3149ef9a07"];  // 已选中Fields
		if ( empty($data['inst']) ) {

		}

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
	 * 类型管理入口菜单 
	 * @return [type] [description]
	 */
	function menulist() {

		// 读取类型清单
		$datatype = App::M('Datatype')->select('LIMIT 50', ['name','cname','typeid', 'readable', 'primary']);
		$datatype['data'] = (isset($datatype['data']) && is_array($datatype['data'])) ? $datatype['data'] : [];
		$datatype['total'] = (isset($datatype['total'])) ? intval($datatype['total']) : 0;

		$result = [];
		foreach ($datatype['data']  as $dt ) {

			$dt['readable'] = (is_array($dt['readable'])) ? $dt['readable'] : [];
			$dt['primary'] = ( isset($dt['primary'])) ? intval($dt['primary']) : 0;
			$permission = implode(',', $dt['readable']);
			$order = $dt['primary'];

			$result["data/index/{$dt['typeid']}"] = [
				"name" => $dt['cname'],
				"link" => '{data,index,[tid:'.$dt['typeid'].']}',
				"target" => "",
				"order"=>$order,
				"permission" => $permission
			];
		}
		//header('Content-Type: application/json');
		echo json_encode($result);

		// echo '{
		// 	"docs/menutest4":{
		// 		"name":"动态添加",
		// 		"link":"{defaults,index,[test:61]}",
		// 		"target":"",
		// 		"order":102,
		// 		"permission":"boss,admin,user,manager"
		// 	},
		// 	"docs/menutest5":{
		// 		"name":"动态添加A",
		// 		"link":"{defaults,index,[test:62]}",
		// 		"target":"",
		// 		"order":2,
		// 		"permission":"boss,admin,user,manager"
		// 	}
		// }';
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