<?php 

namespace Home\Controller;
use BillowPHP\Controller;
   class IndexController extends Controller{
	    
		public function index(){
			// $hello = M('hello')->select();
			 //var_dump($hello);
			 $this->assign('hello','hello BillowPHP<br/>欢迎使用BillowPHP v0.1Beat版<br/>如有问题欢迎指正<br/>Author:billowton@foxmail.com');
			 $this->display();
		}
		
		public function __call($methodname,$argsarr){
		 // echo $methodname.'方法不存在';
		 echo ':( sorry,页面不存在';
		}
   }



?>