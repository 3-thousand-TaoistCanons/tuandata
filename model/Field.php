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



class FieldModel extends Tab {

	/**
	 * 构造函数
	 * @param array $param [description]
	 */
	function __construct( $param=[] ) {

		

		if ( !isset($param['_company_id'])) {
			$param['_company_id'] = 0;
		}
		
		$name = "com_{$param['_company_id']}";
		parent::__construct('field', $name);
		
	}

	/**
	 * 数据表结构
	 * @return [type] [description]
	 */
	function __schema() {
		// 数据结构
		try {
			
			$this->putColumn('fid', $this->type('BaseString', ['screen_name'=>'字段ID','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('uuid',  $this->type('BaseString', ['screen_name'=>'字段UUID','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('name', $this->type('BaseString', ['screen_name'=>'字段英文名称','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('cname', $this->type('BaseString', ['screen_name'=>'字段中文名称','required'=>1, 'unique'=>1, 'matchable'=>0]) );
			$this->putColumn('icon', $this->type('BaseString', ['screen_name'=>'显示图标','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('screen_name', $this->type('BaseString', ['screen_name'=>'字段显示名称','required'=>1, 'unique'=>0, 'matchable'=>0]) );

			// $this->putColumn('gid',  $this->type('BaseString', ['screen_name'=>'字段分组ID','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('group',  $this->type('BaseString', ['screen_name'=>'字段分组名称','required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('host',  $this->type('BaseString', ['screen_name'=>'字段来源', 'required'=>0, 'default'=>'localhost', 'unique'=>0, 'matchable'=>0]) );

			$this->putColumn('source', $this->type('BaseString', ['screen_name'=>'字段代码','required'=>1, 'unique'=>0, 'matchable'=>0,'maxlength'=>65000]) );
			$this->putColumn('option', $this->type('BaseArray', ['screen_name'=>'字段选项', 'default'=>[], 'required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('width', $this->type('BaseInt', ['screen_name'=>'字段宽度', 'default'=>12, 'required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('script', $this->type('BaseString', ['screen_name'=>'初始脚本', 'required'=>0, 'unique'=>0, 'matchable'=>0,'maxlength'=>65000]) );

			
			$this->putColumn('source_mobile', $this->type('BaseString', [
				'screen_name'=>'字段代码(手机)','required'=>1, 'unique'=>0, 'matchable'=>0,'maxlength'=>65000]) );
			$this->putColumn('option_mobile', $this->type('BaseArray', [
				'screen_name'=>'字段选项(手机)', 'default'=>[], 'required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('width_mobile', $this->type('BaseInt', [
				'screen_name'=>'字段宽度(手机)', 'default'=>12, 'required'=>0, 'unique'=>0, 'matchable'=>0]) );
			$this->putColumn('script_mobile', $this->type('BaseString', [
				'screen_name'=>'初始脚本(手机)', 'required'=>0, 'unique'=>0, 'matchable'=>0,'maxlength'=>65000]) );

			$this->putColumn('sync_at',  $this->type('BaseDate', ['screen_name'=>'最后同步时间','required'=>1]) );

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
		return $row;
	}

	
	/**
	 * 读取配置文件
	 * @param  boolean $no_cache  [description]
	 * @param  [type]  $file_name [description]
	 * @return [type]             [description]
	 */
	function loadConf( $no_cache = false, $file_name = null ) {
		$file_name = ( $file_name == null ) ?  App::$APP_ROOT . '/config/field-setting.json'  : App::$APP_ROOT . $file_name;
		$mem = new Mem(true, 'Field:');
		$cache_name = "config:{$file_name}";
		$config = $mem->getJSON( $cache_name );
		if ( $config !== false  && $no_cache === false ) {
			return $config;
		}

		if ( !file_exists($file_name) ) {
			throw new Excp('字段配置文件不存在', 404, ['file_name'=>$file_name]);
		}

		$json_text = file_get_contents($file_name);
		$config = json_decode($json_text, true);
		$config['data'] = [];
		foreach ($config['hosts'] as $idx=>$host) {
			array_push($config['data'], $host['host']);
		}

		if ( json_last_error() != JSON_ERROR_NONE ) {
			throw new Excp('字段配置文件解析错误', 500, ['file_name'=>$file_name, 'json_last_error'=>json_last_error_msg(), 'json_text'=>$json_text]);
		}

		$mem->setJSON($cache_name, $config);
		return $config;
	}



	
	/**
	 * 读取字段仓库
	 * @param  [type]  $file_name [description]
	 * @param  boolean $no_cache  [description]
	 * @return [type]             [description]
	 */
	function loadHost( $host = null, $option=[], $no_cache=false) {

		$file_name = null;
		if ( strpos($host, 'http://') === false  && strpos($host, 'https://') === false ) {
			$file_name = ( $host == null ) ?  App::$APP_ROOT . '/config/field-'.$host.'.json'  : App::$APP_ROOT . '/config/field-default.json';
		}


		$mem = new Mem(true, 'Field:');
		if ( $file_name != null ) {
			$cache_name = "host:local:{$file_name}";
		} else {
			$cache_name = "host:{$host}";
		}

		$config = $mem->getJSON( $cache_name );
		if ( $config !== false  && $no_cache === false ) {
			return $config;
		}

		if ( $file_name != null ) {
			if ( !file_exists($file_name) ) {
				throw new Excp('字段配置文件不存在', 404, ['file_name'=>$file_name]);
			}

			$json_text = file_get_contents($file_name);
			$config = json_decode($json_text, true);

			if ( json_last_error() != JSON_ERROR_NONE ) {
				throw new Excp('字段配置文件解析错误', 500, ['file_name'=>$file_name, 'json_last_error'=>json_last_error_msg(), 'json_text'=>$json_text]);
			}

		} else {
			$ut = new Utils;
			$query = [];
			if ( !empty($option['user']) && !empty($option['secret']) ) {
				$query['user'] = $option['user'];
				$query['time'] = time();
				$query['str'] = $ut->genString(6);
				$query['sign'] = md5( $query['user'] . $query['str'] .  $query['t'] );
			}

			$config = $ut->Request('POST', $host, ['data'=>$query]);
			if ( isset($config['code']) && $config['code'] != 0 ) {
				throw new Excp('HOST返回结果异常', 500, ['host'=>$host, 'data'=>$query, 'resp'=>$config]);
			}
		}

		$mem->setJSON($cache_name, $config);
		return $config;
	}


	/**
	 * 解析字段仓库
	 * @param  Array $hostData 字段数据项 ( loadHost 返回结果 )
	 * @return Array 解析后的 HostData 
	 */
	function parseHost( $hostData  ) {

		$host = (isset($hostData['server']['host'])) ? $hostData['server']['host'] : null;
		$hostData['fields'] = (is_array($hostData['fields'])) ? $hostData['fields'] : [];
		$hostData['map'] = [];

		foreach ($hostData['fields'] as $idx => $field) {
			$remote = !empty( $field['remote'] ) ?  $field['remote']  : null;
			$uuid = !empty( $field['uuid'] ) ?  $field['uuid']  : null;
			if ( !empty($remote['host']) && !empty($remote['uuid'] ) ) {

				$ruuid = $remote['uuid'];

				// 读取引用其他Host的代码
				if ( $remote['host'] != $host ) {

					$ref = $this->getHost($remote['host']);
					$refField = $ref['map'][$ruuid]; unset($refField['uuid']); unset($refField['remote']);

					if ( is_array($refField) ) {

						$field = array_merge($refField, $field );
						unset($field['host']);
						$hostData['fields'][$idx] = $field;
					}
				}
			}

			// 自动生成UUID
			if ( $uuid === null ) {
				ksort($field);
				$uuid = md5(json_encode($field));
				$hostData['fields'][$idx]['uuid'] = $uuid;
			}

			$hostData['map'][$uuid] = $field;
		}

		return $hostData;
	}


	/**
	 * 读取并解析字段仓库
	 * @param  [type]  $host     [description]
	 * @param  array   $option   [description]
	 * @param  boolean $no_cache [description]
	 * @return [type]            [description]
	 */
	function getHost( $host = null, $no_cache=false ) {

		$conf = $this->loadConf( $no_cache );
		$idx = array_search( $host, $conf['data'] );
		if ( $idx === false ) {
			throw new Excp('HOST未配置', 500, ['host'=>$host, 'no_cache'=>$no_cache, 'conf'=>$conf]);
		}

		$option = array_merge($conf, $conf['hosts'][$idx]);  unset($option['hosts']);
		return $this->parseHost($this->loadHost($host, $option, $no_cache) );
	}



	/**
	 * 读取配置文件(别名)
	 * @param  boolean $no_cache  [description]
	 * @param  [type]  $file_name [description]
	 * @return [type]             [description]
	 */
	function getConf( $no_cache = false, $file_name = null ) {
		return $this->loadConf($no_cache, $file_name);
	}
	

	/**
	 * 从远程同步字段信息
	 * @param  [type] $host 字段服务器地址
	 * @return [type]       [description]
	 */
	function sync( $host ) {

	}


	/**
	 * 生成一个唯一的ID
	 * @return [type] [description]
	 */
	function genid() {
		return (string) $this->nextid();
	}


	/**
	 * 重载数据入库逻辑 （自动创建 id)
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function create( $data ) {
		if( !isset($data['fid']) && !isset($data['_id']) ) {
			$data['fid'] = $this->genid();
		}
		return parent::create( $data );
	}
	

}