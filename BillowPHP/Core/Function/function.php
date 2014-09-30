<?php

//M()方法用于生成Model对象

use BillowPHP\Model;
/***
* 参数 $model 数据模型
*author:billowton@foxmail.com
*/
function M($model=null){
	return new Model($model);
}

function D($model=null){

   
    if(!$model){
    	die('error: D()方法参数不得为空');
    }
     global $module_path;
	 global $moduleName;
    //引入对应模块下的Model
require_once($module_path.'/Model/'.$model.'Model.class.php');

    //根据模型名 返回普通Model 还是ViewModel
    if(strpos($model,'View')){
    	$model_class_name_namespace = "$moduleName\\ViewModel\\$model"."Model";
    }else{
    	$model_class_name_namespace = "$moduleName\\Model\\$model"."Model";
    }

    

    //返回对应的
    return new $model_class_name_namespace();
}


