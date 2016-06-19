<?php
/**
 * 团队猫应用路由 
 * 
 * 所有请求将会通过本程序转发
 * @see 团队猫应用开发手册
 */

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE );
ini_set( 'display_errors' , true );
ini_set('date.timezone','Asia/Shanghai');
define('APP_ROOT', __DIR__);



require_once('loader/Loader.php');
require_once('lib/Excp.php');
require_once('lib/Utils.php');

use \Tuanduimao\Excp as Excp;
use \Tuanduimao\Utils as Utils;


// 传入数据
$json = file_get_contents('php://input');

// HTTP 头
$ut = new Utils;
$headers = $ut->getHeaders();
	$headers_error = "";
	if ( checkHeaders($headers, $headers_error ) === false ) {
		$e = new Excp("Header Error", '500', ['headers'=>$headers, 'headers_error'=>$headers_error] );
		$e->log();
		echo json_encode(['result'=>false, 'content'=>$e->getMessage(), 'data'=>$e ]);
		exit;
	}


// 请求信息
$request = json_decode($json, true);
	if( json_last_error() !== JSON_ERROR_NONE) {
		$e = new Excp("Parse Error: " . json_last_error_msg(), '500', ['json'=>$json, 'headers_error'=>json_last_error_msg()] );
		$e->log();
		echo json_encode(['result'=>false, 'content'=>$e->getMessage(), 'data'=>$e ]);
		exit;
		
	}
	if ( $request === null ) {
		$e =  new Excp("Parse Error: Unknown", '500');
		$e->log();
		echo json_encode(['result'=>false, 'content'=>$e->getMessage(), 'data'=>$e  ]);
		exit;
	}


// 调用方法
$type = (isset($headers['CONTENT_TYPE'])) ? $headers['CONTENT_TYPE'] : 'text';

try{
	if (in_array($type,["application/api","application/noframe","application/portal"])) {  // API/NOFRAME/PORTAL 直接返回
		echo \Tuanduimao\Loader\Auto::Run( $headers, $request );
		exit;
	}

	$resp = \Tuanduimao\Loader\Auto::Run( $headers, $request );
	echo json_encode($resp);

} catch( Excp $e ) {
	$e->log();
	echo json_encode(['result'=>false, 'content'=>$e->getMessage(), 'data'=>$e->error->extra ]);

} catch( Exception $e ) {
	Excp::elog($e);
	echo json_encode(['result'=>false, 'content'=>$e->getMessage(), 'data'=>$e ]);
}





/**
 * 校验HTTP请求头
 * @param  [type] $headers [description]
 * @param  [type] $errmsg  [description]
 * @return [type]          [description]
 */
function checkHeaders( $headers, & $errmsg ) {
	$errmsg = "";
	$checkList = ['Controller','Action','Home','Noframe','Static','Portal']; // 'Mid','Role','Tt','Oid','Otype',
	$ret = true;
	foreach ($checkList as $check) {
		if ( empty($headers["Tuanduimao-$check"]) ) {
			$errmsg .= "$check not set;";
			$ret = false;
		}
	}
	return $ret;
}


