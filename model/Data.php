<?php
// 本程序自动生成 @2016-06-28 20:47:11
require_once('loader/App.php');
include_once( 'lib/Tab.php');
include_once( 'lib/Mem.php');
include_once( 'lib/Excp.php');
include_once( 'lib/Err.php');
include_once( 'lib/Conf.php');
include_once( 'lib/Stor.php');
include_once( 'lib/Utils.php');


use \Tuanduimao\Tab as Tab;
use \Tuanduimao\Mem as Mem;
use \Tuanduimao\Excp as Excp;
use \Tuanduimao\Err as Err;
use \Tuanduimao\Conf as Conf;
use \Tuanduimao\Stor as Stor;
use \Tuanduimao\Utils as Utils;

use \Tuanduimao\Loader\App as App;



class DataModel extends Tab {

	private $_typename = null;
	private $_datatype = [];


	/**
	 * 构造函数
	 * @param array $param [description]
	 */
	function __construct( $typename ) {

		$param=[];
		if ( !isset($param['_company_id'])) {
			$param['_company_id'] = 0;
		}
		$name = "com_{$param['_company_id']}";

		$dt = App::M('Datatype');
		$_dtid  = $dt->uniqueToID('name', $typename );
		if ( $_dtid === null ) {
			throw new Excp('无法获取数据类型信息( _id is null )', 404, ['typename'=>$typename]);
		}

		$this->_datatype = $dt->get( $_dtid );
		$this->_typename = $typename;
		

		parent::__construct( $typename, $name);
	}


	/**
	 * 数据表结构
	 * @return [type] [description]
	 */
	function __schema() {
	}


	/**
	 * 格式化数据
	 * @param  [type] $row [description]
	 * @return [type]      [description]
	 */
	function format( & $row ) {
		// 自定义格式化数据
		return $row;
	}

}