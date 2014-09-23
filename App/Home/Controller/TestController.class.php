<?php 

namespace Home\Controller;
use BillowPHP\Controller;
   class TestController extends Controller{
	    
		public function index(){
			 $this->assign('test','test..控制器');
			 $this->display();
		}
		
		public function __call($methodname,$argsarr){
		 // echo $methodname.'方法不存在';
		 echo ':( sorry,页面不存在';
		}
   }



?>