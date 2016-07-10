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

		// if ( !isset($param['index']) ) {
		// 	$param['index'] = Conf::G('supertable/search/index');
		// }

		// if ( empty($param['index']) ) {
		// 	$param['index'] = 'tuanduimao';
		// }


		if ( !isset($param['_company_id'])) {
			$param['_company_id'] = 0;
		}
		
		$name = "com_{$param['_company_id']}";
		parent::__construct('datatype', $name);

	}





	/**
	 * 数据表结构
	 * @return [type] [description]
	 */
	function __schema() {
		// 数据结构
		try {
			
			$this->putColumn('typeid', $this->type('BaseString', ['screen_name'=>'类型ID','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('name', $this->type('BaseString', ['screen_name'=>'英文名称','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('cname', $this->type('BaseString', ['screen_name'=>'中文名称','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('fields', $this->type('BaseArray', ['screen_name'=>'字段清单','required'=>0, 'unique'=>0, 'matchable'=>0, 'schema'=>'string']) );

			$this->putColumn('domain', $this->type('BaseString', ['screen_name'=>'绑定域名','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('route', $this->type('BaseString', ['screen_name'=>'路由设置','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			
			// ["boss","admin","user","manager","{userid}"]
			$this->putColumn('readable', $this->type('BaseArray', [
				'screen_name'=>'默认阅读权限','default'=>["boss","admin","user","manager","vistor"],
				'required'=>0, 'unique'=>0, 'matchable'=>0,'schema'=>'string'])
			);

			$this->putColumn('createable', $this->type('BaseArray', [
				'screen_name'=>'默认创建权限','default'=>["boss","admin","user","manager"],
				'required'=>0, 'unique'=>0, 'matchable'=>0,'schema'=>'string']) 
			);
			$this->putColumn('writeable', $this->type('BaseArray', [
				'screen_name'=>'默认修改权限','default'=>["boss","admin","user","manager"],
				'required'=>0, 'unique'=>0, 'matchable'=>0,'schema'=>'string']) 
			);
			$this->putColumn('deleteable', $this->type('BaseArray', [
				'screen_name'=>'默认删除权限','default'=>["boss","admin","user","manager"],
				'required'=>0, 'unique'=>0, 'matchable'=>0,'schema'=>'string']) 
			);
			
			$this->putColumn('appcname', $this->type('BaseString', ['screen_name'=>'应用名称','required'=>0, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('appicon_path', $this->type('BaseString', ['screen_name'=>'应用图标','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('appdesp', $this->type('BaseString', ['screen_name'=>'应用简介','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			
			// ["dingtalk",'wechat']
			$this->putColumn('mplat', $this->type('BaseArray', ['screen_name'=>'移动平台','required'=>0, 'unique'=>0, 'matchable'=>0, 'schema'=>'string']) );

			// [{name:'手机扫码呈现模板', 'code':'xxxxxxxx'} ... ]
			$this->putColumn('code', $this->type('BaseArray', ['screen_name'=>'呈现模板','required'=>0, 'unique'=>0, 'matchable'=>0, 'schema'=>'object']) );

		} catch( Exception $e ) {
			Excp::elog($e);
			throw $e;
		}
	}

	/**
	 * 格式化数据
	 * @param  [type] $row [description]
	 * @return [type]      [description]
	 */
	function format( & $row ) {
		// 自定义格式化数据
		if ( isset($row['appicon_path'])  && !empty($row['appicon_path']) ) {
			$stor = new Stor;
			$row['appicon_url'] = $stor->getUrl($row['appicon_path']);
			if ( !is_string($row['appicon_url'])) {
				$row['appicon_url'] = "";
			}
		}

		return $row;
	}


	/**
	 * 生成一个唯一的ID
	 * @return [type] [description]
	 */
	function genid() {
		return (string) uniqid(mt_rand(),1);;
	}


	/**
	 * 重载数据入库逻辑 （自动创建 projectid)
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function create( $data ) {
		if( !isset($data['typeid']) && !isset($data['_id']) ) {
			$data['typeid'] = $this->genid();
		}
		
		return parent::create( $data );
	}
	

}