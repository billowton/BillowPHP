<?php 
/**BillowPHP框架
*author：Billowton@foxmail.com
* v0.1
*/

define('ROOT_PATH',$_SERVER['DOCUMENT_ROOT'].'/');
//如果APP未定义则默认为App 
if(!defined('APP')) define('APP','App');
define('APP_PATH',ROOT_PATH.APP.'/');

define('RUNTIME_PATH',ROOT_PATH.'Runtime/');
define('CACHE_PATH',RUNTIME_PATH.'Cache/');
define('LIB_PATH',ROOT_PATH.'BillowPHP/Libs/');
define('CONFIG_PATH',ROOT_PATH.'BillowPHP/Conf/');
//核心代码 路径
define('CORE_PATH',ROOT_PATH.'BillowPHP/Core/');

/**根据url转发*/
//获取url参数
$query_string = $_SERVER['QUERY_STRING'];

/**默认的模块名/控制器名/方法名*/

if(!defined(DEFAULT_MODULE)){
  $moduleName = 'Home';
}else{
  $moduleName = DEFAULT_MODULE;
}



$controllerName = 'Index';
$methodName = 'index';

//如果没有目录创建初始结构
//
if($moduleName=='Home'){
   require_once(CORE_PATH.'Function/create_default_file.php');
 }

      




//把参数分割成数组  （根据‘/’）
$query_array = explode('/',substr($query_string,strpos($query_string,'=')+1));

//剔除第一个数组空项
if(!$query_array[0]){
  array_splice($query_array,0,1);
}

//根据数组第二个实例化控制器（/模块/控制器/方法）
//默认实例化Home模块下Index控制器
if(isset($query_array[0])){
       $moduleName = ucfirst($query_array[0]);
}
if(isset($query_array[1])){
       $controllerName = ucfirst($query_array[1]);
}
if(isset($query_array[2])){
	   //方法小写
       $methodName =  strtolower($query_array[2]);
}



//控制器类名 
$controller_classname = $controllerName.'Controller';

/***拼接成文件路径。*/

$module_path = APP.'/'.$moduleName;
$controller_class_file_path = $module_path.'/Controller/'.$controllerName.'Controller.class.php';

//echo $module_path;

//引入前检查文件是否存在(包括检查模块是否存在);
if(!file_exists($module_path)){
    echo "<h1 style='color:red'>:( 不存在该模块</h1>";
	exit;
}

if(!file_exists($controller_class_file_path)){
   echo "<h1 style='color:red'>:( 控制器不存在</h1>";
   exit;
}

//引入父控制器类
require_once(CORE_PATH.'Controller/Controller.class.php');
//引入父Model类
require_once(CORE_PATH.'Model/Model.class.php');
//引入父ViewModel类
require_once(CORE_PATH.'Model/ViewModel.class.php');
//引入核心函数 function
require_once(CORE_PATH.'Function/function.php');
//引入核心控制器生成器类库
require_once(CORE_PATH.'ControllerFactory.class.php');


//引入对应的控制器类
require_once($controller_class_file_path);


//实例化控制器，并调用方法
 $controller_classname_namespace = "$moduleName\\Controller\\".$controller_classname;

  //通过控制器生成器实例化控制器
   $controller_factory = new BillowPHP\ControllerFactory();
   $controller = $controller_factory->getInstance($controller_classname_namespace,$moduleName,$controllerName);

   //调用控制器对应的方法
   $controller->$methodName();

?>