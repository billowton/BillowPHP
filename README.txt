BillowPHP 开源框架
author:billowton@foxmail.com
版本 ： 0.1Release版 (v 0.1.3)

目录结构
  /--root               根目录
   |  |-App            应用目录
   |  |-Home         Home 模块（默认模块，可以自己添加模块）
   |  |	 |-Controller       控制器
   |  |	 |-View              页面模版（用于smarty）  
   |  |-BillowPHP    框架核心目录
   |  |	 |-Core      核心文件
   |  |	 |  |-Controller   父类控制器(用于控制器初始化前加载配置等)
   |  |  |  |-Model        模型类
   |  |  |  |-Function     核心函数库
   |  |  |-Libs       核心库文件 (包含smarty)
   |  |  |-Conf       配置文件（用于配置数据库设置）
   |  |	 |-BillowPHP.php  框架引导文件
   |  |-index.php     入口文件
	   
	   
URL  访问方式   http://host/index.php?s=/模块/控制器/方法



注：Model  尚未完全完善，请慎用！  
	   1.select() 方法没有问题               比如 M('table')->select()
           2.where() 方法暂时只支持 数组传参     如M('table')->where(array('id'=>1,'name'=>'BillowtonPHP'))->select() 
	   3.delete() 方法暂时支持通过where()和单主键删除  比如：M('table')->where(array('id'=>1,'name'=>'BillowtonPHP'))->delete()   M('table')->delete(1) 《删除主键为1的记录，暂不支持联合主键》
           4.add()   方法暂时只支持数组  比如M('table')->add(array('name'=>'BillowPHP'));
           5.save() 方法暂时只支持 数组传参     如M('table')->where(array('id'=>1,'name'=>'BillowtonPHP'))->save(array('name'=>'BillowPHP'));
           
          
///下一版本将完善Model如下方法
              1.find()
	      2.order()
              3.limit()
            联合查询等d
              