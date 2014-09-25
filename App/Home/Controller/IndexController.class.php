<?php 

namespace Home\Controller;
use BillowPHP\Controller;
   class IndexController extends Controller{
	    
		public function index(){
			 
			 $this->assign('hello','hello BillowPHP<br/>欢迎使用BillowPHP v0.1Release版<br/>如有问题欢迎指正<br/>Author:billowton@foxmail.com');
			 $this->display();
		}

		
   }



?>