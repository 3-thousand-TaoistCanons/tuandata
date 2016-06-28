<?php
// 本程序自动生成 @2016-06-28 20:47:11
include_once( 'lib/Tab.php');
include_once( 'lib/Mem.php');
include_once( 'lib/Excp.php');
include_once( 'lib/Err.php');
include_once( 'lib/Conf.php');
include_once( 'lib/Stor.php');


use \Tuanduimao\Tab as Tab;
use \Tuanduimao\Mem as Mem;
use \Tuanduimao\Excp as Excp;
use \Tuanduimao\Err as Err;
use \Tuanduimao\Conf as Conf;
use \Tuanduimao\Stor as Stor;


class DatatypeModel extends Tab {

	/**
	 * 构造函数
	 * @param array $param [description]
	 */
	function __construct( $param=[] ) {

		if ( !isset($param['_company_id'])) {
			$param['_company_id'] = 0;
		}

		$name = "com_{$param['_company_id']}";
		parent::__construct('datatype', $name);
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


	/**
	 * 数据表结构
	 * @return [type] [description]
	 */
	function __schema() {
		// 数据结构
		try {
			
			$this->putColumn('cname', $this->type('BaseString', ['screen_name'=>'中文名称','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('name', $this->type('BaseString', ['screen_name'=>'英文名称','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('fields', $this->type('BaseString', ['screen_name'=>'字段清单','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('domain', $this->type('BaseString', ['screen_name'=>'绑定域名','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('ID_14671159589237747', $this->type('BaseString', ['screen_name'=>'前端服务器IP','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('route', $this->type('BaseString', ['screen_name'=>'路由设置','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('readable', $this->type('BaseString', ['screen_name'=>'默认阅读权限','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('createable', $this->type('BaseString', ['screen_name'=>'默认创建权限','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('writeable', $this->type('BaseString', ['screen_name'=>'默认修改权限','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('deleteable', $this->type('BaseString', ['screen_name'=>'默认删除权限','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('ID_14671172870094912', $this->type('BaseString', ['screen_name'=>'','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('appcname', $this->type('BaseString', ['screen_name'=>'应用名称','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('appicon', $this->type('BaseString', ['screen_name'=>'应用图标','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('appdesp', $this->type('BaseString', ['screen_name'=>'应用简介','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('mplat', $this->type('BaseString', ['screen_name'=>'移动平台','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('ID_14671170672571327', $this->type('BaseString', ['screen_name'=>'按钮组','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('file', $this->type('BaseString', ['screen_name'=>'选择模板','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('code', $this->type('BaseString', ['screen_name'=>'模板正文','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('ID_14671177757401923', $this->type('BaseString', ['screen_name'=>'','required'=>0, 'unique'=>0, 'matchable'=>0]) );

		} catch( Exception $e ) {
			Excp::elog($e);
			throw $e;
		}
	}

}