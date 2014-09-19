<?php
/**
*控制器基类，用于在控制器初始化时加载配置
*/
namespace BillowPHP;
define('SMARTY_PATH',LIB_PATH.'Smarty/');
require_once(SMARTY_PATH.'Smarty.class.php');
//控制器基类
class Controller extends \Smarty{
    public $templates_dir;//模版文件路径
	public $templates_c_dir;

	public function __construct($args){
		    //调用基类Samrty构造函数
			parent::__construct();
	       //'基类控制器初始化';
		   $this->templates_dir = APP_PATH.$args['moduleName'].'/View/';
		   $this->templates_c_dir = CACHE_PATH.$args['moduleName'].'/';
			
			//如果模版文件夹不存在怎创建
			if(!file_exists( $this->templates_dir)){
				  mkdir( $this->templates_dir);
			}

			//如果缓存文件夹不存在则创建
		   if(!file_exists( $this->templates_c_dir)){
			   if(!file_exists(CACHE_PATH)){
			      if(!file_exists(RUNTIME_PATH)){
				      mkdir(RUNTIME_PATH);
				  }
				  mkdir(CACHE_PATH);
			   }
			   mkdir( $this->templates_c_dir);
		   }

		   //设置模版文件路径和编译文件路径
		   parent::setTemplateDir( $this->templates_dir);
		   parent::setCompileDir( $this->templates_c_dir);
	}

	//重写smarty中dispaly方法,添加默认参数（模版地址）
	//默认后缀为html
	public function display($template=null,$ext='.html'){
		if(!$template){  //如果模版未设置，则调用函数名
			//从堆栈中获取上一级函数名
			$trace_array = debug_backtrace();
			$template = $trace_array[1]['function'];
		}
		//var_dump($this->templates_dir);
		$template_file = $this->templates_dir.$template.$ext;
		parent::display($template_file);
	}


}