BillowPHP 开源框架
author:billowton@foxmail.com
版本 ： v0.1beat版

目录结构
  /---               根目录
      App ---            应用目录
	        Home---         Home 模块（可以自己添加模块）
				Controller       控制器
				 View              页面模版（用于smarty）  
	  BillowPHP---    框架核心目录
	        Core      核心文件
			Libs       核心库文件 (包含smarty)
			Conf       配置文件（用于配置数据库设置）
			BillowPHP.php  框架引导文件
			
	   index.php     入口文件