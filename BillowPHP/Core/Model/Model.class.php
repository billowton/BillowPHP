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
	public $model_name;

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
	*param $data_arr  要插入数据库的数组
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
	*param $id  删除的主键（可以为空，则从条件where中判断）
	*/
	public function delete($id=null){
		
		//如果直接通过id删除
		if($id){
			$conditon_sql = $this->pre_dml();
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
			$conditon_sql = $this->pre_dml();
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
		$conditon_sql = $this->pre_dml();
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
		$conditon_sql = $this->pre_dml();
		$sql = 'select * from '.$this->model_name.$conditon_sql;
		$rs = mysql_query($sql,$this->conn);
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
	*param $condition  查询条件的数组
	*/
	
	public function where($condition=null){
		if(!$condition){
		  echo 'error :查询条件不得为空';
		  exit;
		}
		$this->condition_arr = $condition;
		
		return $this;
	}

	//查找单个
	public function find($id=null){
		
	}

	//排序
	public function order(){
		
	}

	//限制数目
	public function limt(){
		
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
		return $conditon_sql;
	}



	//dml处理之后
	public function after_dml(){
		mysql_close($this->conn);
	}

	//连接数据库
	public function connect_db(){
		//数据库名
		$db_name = $this->db_conf_array['DB_NAME'];
		//连接数据库
		$this->conn = mysql_connect(
			$this->db_conf_array['DB_HOST'],
			$this->db_conf_array['DB_USER'],
			$this->db_conf_array['DB_PWD']
			);
		if(!$this->conn){
		   die(':( 连接数据库失败,请检查配置文件中用户名密码数据库名是否正确');
		}
		

		//选择数据库
		mysql_select_db($db_name,$this->conn);
	}
}

?>