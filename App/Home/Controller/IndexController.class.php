<?php 

namespace Home\Controller;
use BillowPHP\Controller;
   class IndexController extends Controller{
	    
		public function index(){
			 $hello = M('hello')->select();
			 var_dump($hello);
			 $this->assign('test','hello smarty');
			 //$this->display();
		}
		
		public function __call($methodname,$argsarr){
		 // echo $methodname.'方法不存在';
		 echo ':( sorry,页面不存在';
		}
   }



?>