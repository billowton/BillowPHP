<?php

//M()方法用于生成Model对象

use BillowPHP\Model;
/***
* 参数 $model 数据模型
*author:billowton@foxmail.com
*/
function M($model){
	return new Model($model);
}
