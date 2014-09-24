BillowPHP 开源框架
author:billowton@foxmail.com
版本 ： 0.1beat版 (v0.1.1)

目录结构
  /---               根目录
   |-----   App            应用目录
   |----- Home         Home 模块（默认模块，可以自己添加模块）
				|-----Controller       控制器
				|-----View              页面模版（用于smarty）  
   |----- BillowPHP---    框架核心目录
	           |-----Core      核心文件
			   |-----Libs       核心库文件 (包含smarty)
			   |-----Conf       配置文件（用于配置数据库设置）
			   |-----BillowPHP.php  框架引导文件
			
	|----- index.php     入口文件
	   
	   
URL  访问方式   http://host/index.php?s=/模块/控制器/方法



注：Model  尚未完全完善，请慎用！  （ select() 方法没有问题 比如M('table')->select()）
	   