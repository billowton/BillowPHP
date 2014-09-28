<?php

namespace BillowPHP;



/**
*模型类 (用于操作数据库)
*author billowton@foxmail.com
*/
class Model{
    public $db_conf_array= array(); //数据库配置项
	public $conn;    //数据库连接资源
    public $condition_arr; //查询条件
	public $model_name;   ///模型名（数据库表名）
	public $limit_sql;    //限制语句
 	public $conditon_sql;  //查询条件语句
 	public $order_sql;  //排序语句
 

	public function __construct($model_name=null){
		//加载配置项
		$this->db_conf_array =require_once(CONFIG_PATH.'config.php');
			
		//数据模型名字  （如果表前缀存在则加入）

		if(!$model_name){  //如果模型名字未传入 则置空
		  $this->model_name = null;
		}else{
		  $this->model_name = $this->db_conf_array['DB_PREFIX'].$model_name;
		}

		//操作数据库之前连接

	}
    
	//增
	/**
	*@param $data_arr  要插入数据库的数组
	*/
	public function add($data_arr=null){
        //连接数据库等一系列操作
		$this->pre_dml();
		
		//判断插入的数组data_arr是否为空
		if(!$data_arr){
			echo "error:插入数组不得为空 比如M('table')->add(array('name'=>'BillowPHP'))";
			exit;
		}

		/***拼接sql*/
		  //拼接field (字段名) 和 values
		  $field_sql = '(';
		  $values_sql = 'values(';
			foreach($data_arr as $field=>$value){
			  $field_sql = $field_sql.$field.',';
			  $values_sql= $values_sql."'".$value."',"; 
			}
			
		  $field_sql = substr($field_sql,0,-1);
		  $values_sql = substr($values_sql,0,-1);
		  $field_sql =$field_sql.')';
		  $values_sql=$values_sql.')';
		 
		  $sql = 'insert into '.$this->model_name.$field_sql." ".$values_sql;

		  return  mysql_query($sql,$this->conn);
	}

	//删
	/**
	*@param $id  删除的主键（可以为空，则从条件where中判断）
	*/
	public function delete($id=null){
		$this->pre_dml();
		$conditon_sql = $this->conditon_sql;
		
		//如果直接通过id删除
		if($id){
			//需要手动找到primary key通过desc table
			$find_primary_key_sql = "desc ".$this->model_name;
			$find_primary_key_rs = mysql_query($find_primary_key_sql,$this->conn);
			
			//表主键名;
			$primary_key_name = null;
			while($row=mysql_fetch_array($find_primary_key_rs)){
			    if($row['Key']=='PRI'){
				   $primary_key_name = $row['Field'];
				   break;
				}
			
			}
			if(!$primary_key_name){
			   echo ':( sorry 删除出错了，数据库未指定主键';
			   exit;
			}
          $conditon_sql = " where ".$primary_key_name."=".$id;


		}else{
			//如果删除id为空 ，且查询条件也为空
			//则报错
			if(!$conditon_sql){
			    echo 'error :删除操作，条件不足';
				exit;
			}

		}
		$sql = 'delete from '.$this->model_name.$conditon_sql;
		return  mysql_query($sql,$this->conn);
		
	}


	//改

	public function save($data_arr=null){
		//连接数据库等一系列操作
		$this->pre_dml();
		$conditon_sql = $this->conditon_sql;
		//
		if(!$conditon_sql){
		   echo 'error:  修改条件不足，请完善where()';
		   exit;
		}
		
		//判断修改的数组data_arr是否为空
		if(!$data_arr){
			echo "error:修改数组不得为空 比如M('table')->add(array('name'=>'BillowPHP'))";
			exit;
		}

		/***拼接sql*/
		  //拼接set 语句
		  $set_sql = ' set ';
			foreach($data_arr as $field=>$value){
			  $set_sql =  $set_sql.$field."='".$value."',";
			}
			
		  $set_sql = substr($set_sql,0,-1);
		 
		  $sql = 'update '.$this->model_name.$set_sql.$conditon_sql;
		  return  mysql_query($sql,$this->conn);
	}

	//查
	public function select(){
		$this->pre_dml();
		$conditon_sql = $this->conditon_sql;
		$limit_sql = $this->limit_sql;
		$order_sql = $this->order_sql;
		$sql = 'select * from '.$this->model_name.$conditon_sql.$order_sql.$limit_sql;

		$rs = mysql_query($sql,$this->conn);
		if(!$rs){  //查询结果为空
                  return array();

			}
		$result_array = array();
		while($row = mysql_fetch_array($rs)){
		   array_push($result_array,$row);
		}
		 //在返回数据之前进行一些操作（比如关闭连接释放资源等操作）
		mysql_free_result($rs);
		$this->after_dml();
		return $result_array;
	}
	
	
	//条件
	/**
	*@param $condition  查询条件的数组
	*/
	
	public function where($condition=null){
		if(!$condition){
		  echo 'error :查询条件不得为空';
		  exit;
		}
		$this->condition_arr = $condition;

		//拼接sql语句
		//先查询是否有条件
		 $conditon_sql = '';
		if($this->condition_arr){
			//以where 开始
			$conditon_sql = ' where ';
			$i = 0;
			$arr_count = count($this->condition_arr);
		   foreach($this->condition_arr as $k=>$v){
		     $conditon_sql .= $k.'='."'".$v."' ";
			 $i++;
			 //如果不是最后一个条件则加上 and
			 if($i<$arr_count){
					$conditon_sql.=' and ';
			 }
		   }
		}
		
		$this->conditon_sql =  $conditon_sql;
		
		return $this;
	}

	/**
	 * [find 查找单条记录]
	 * @param  [string] $id [查询的主键id]
	 * @return [array]     [查询的结果数组]
	 */
	public function find($id=null){
		//连接数据，返回查询语句等操作
		$this->pre_dml();//如果通过条件查询
        $conditon_sql = $this->conditon_sql;	


		if ($id) {  //通过主键查询
			
		
			  //需要手动找到primary key通过desc table
			$find_primary_key_sql = "desc ".$this->model_name;
			$find_primary_key_rs = mysql_query($find_primary_key_sql,$this->conn);
			
			//表主键名;
			$primary_key_name = null;
			while($row=mysql_fetch_array($find_primary_key_rs)){
			    if($row['Key']=='PRI'){
				   $primary_key_name = $row['Field'];
				   break;
				}
			
			}
			if(!$primary_key_name){
			   echo ':( sorry 查找出错了，数据库未指定主键';
			   exit;
			}
          $conditon_sql = " where ".$primary_key_name."=".$id;
		
		}elseif (!$conditon_sql) {  //查询条件和查询主键都为空则报错
             die('error：查询条件不足，无法查询');
			
		}
		


		$sql = 'select * from '.$this->model_name.$conditon_sql.' limit 1';
			$rs = mysql_query($sql,$this->conn);

			
			$result_array = array();
			if(!$rs){  //查询结果为空
                  return array();

			}
			while($row = mysql_fetch_array($rs)){
			   array_push($result_array,$row);
			}
			 //在返回数据之前进行一些操作（比如关闭连接释放资源等操作）
			mysql_free_result($rs);
			$this->after_dml();
			return $result_array;
		
		
		
	}

	//排序语句
	/**
	 * [order 排序语句]
	 * @param [string] $order_sql [排序语句]
	 * @return [type] [对象本身]
	 */
	public function order($order_sql=null){
		if (!$order_sql) {
			die('error: 排序参数不得为空,请检查');
		}
		$this->order_sql = ' order by '.$order_sql;
		return $this;
	}






	/**
	 * [limt 限制数目]
	 * @param  [int] $param1 [显示的记录数]  //如果第二个参数不为空则表示开始条数 
	 * @param  [int] $param2 [查询的条数]
	 * @return [$this]         [对象本身]
	 */
	public function limit($param1=null,$param2=null){
		if($param1===null){

			die('error: limit方法参数不得为空（至少传一个参数）');
		}
		if(!$param2){
			$param1 = intval($param1);
		   $this->limit_sql = ' limit '.$param1;

		}else{
			$param1 = intval($param1);
			$param2 = intval($param2);
		   $this->limit_sql = ' limit '.$param1.','.$param2;

		}
		return $this;

	}



	//dml操作之前
	public function pre_dml(){

		//连接数据
		$this->connect_db();
		//判断模型名字是否为空
			if(!$this->model_name){
			 echo "error: dml操作 M() 方法必须传模型名 参数 如M('table')";
			 exit;
			}

         //查询数据库中对应的表是否存在
         $table_exist_sql = " SHOW TABLES LIKE '%".$this->model_name."%'";
         
         if(!mysql_query($table_exist_sql)){
             die('error: 数据库中不存在此表或模型，请检查!');

         }

		
	}



	//dml处理之后
	public function after_dml(){
		mysql_close($this->conn);
	}

	//连接数据库
	public function connect_db(){

		//数据库名
		$db_name = $this->db_conf_array['DB_NAME'];

        //主机名
		if (!$this->db_conf_array['DB_HOST']) {
			$host = '127.0.0.1';
		} else {
			$host = $this->db_conf_array['DB_HOST'];
		}
		
		//端口号
		if (!$this->db_conf_array['DB_PORT']) {
			$db_port = 3306;
		}else{
		   $db_port = $this->db_conf_array['DB_PORT'];
		}
		//连接数据库
		$this->conn = mysql_connect(
			$host.':'.$db_port,
			$this->db_conf_array['DB_USER'],
			$this->db_conf_array['DB_PWD']
			);
		if(!$this->conn){
		   die(':( 连接数据库失败,请检查配置文件中用户名密码数据库名是否正确');
		}

		//选择数据库
		if(!mysql_select_db($db_name,$this->conn)){

				die('error: 没有选择数据库，或者数据库不存在');

		}
	}
}

?>