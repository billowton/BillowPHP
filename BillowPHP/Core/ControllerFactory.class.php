<?php
 
namespace BillowPHP;

 /**
 * 控制器类生成器
 * 用于实例化控制器
 */
 class ControllerFactory
 {
 	
 	function __construct()
 	{
 		# code...
 	}

 	public function getInstance($class_name,$moduleName,$controllerName)
 	{
 		try{
          $refl = new \ReflectionClass($class_name);
            //传递构造参数（模版地址等等信息）
		       $args = array(
					'moduleName'=> $moduleName,
					'controllerName'=>$controllerName,
		           );
               return $refl->newInstance($args);

        }catch(Exception $e){
            var_dump($e);
            die('error:  发生系统错误！');
        }
 	}
 }