BillowPHP 开源框架
author:billowton@foxmail.com
个人主页：blog.billowton.com
版本 ： 0.1Release版 (v 0.1.7)

目录结构
  /--root               根目录
   |  |-App            应用目录
   |  |  |-Home         Home 模块（默认模块，可以自己添加模块）
   |  |  |  |-Controller       控制器
   |  |  |  |-View              页面模版（用于smarty）  
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
           6.find()  默认查找一个 也可以直接传主键 比如 M('table')->find(1) 查找主键为1的记录
           7.order()  可以根据字段排序 比如 M('table')->order('id desc')->select();
           8.limit()  限制查询的记录数  单个参数表示查询指定数目记录，两个参数（第一个参数表示查询的开始记录，第二个参数表示查询的数目）
          
    视图模型  ViewModel  已经完善
    只需继承ViewModel  
            
 

/**修改部分
*  添加自动创建App目录及目录下文件
**/

///下一版本将完善Model如下方法
            联合查询
              