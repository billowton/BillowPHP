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

	public function __construct($model_name){
		//加载配置项
		$this->db_conf_array =require_once(CONFIG_PATH.'config.php');
		$this->model_name = $model_name;
	}
    
	//增
	public function add(){
		
	}

	//删
	public function delete(){
		
	}
	//改
	public function save(){
		
	}

	//查
	public function select(){
		//连接数据库
		$this->conn = mysql_connect(
			$this->db_conf_array['DB_HOST'],
			$this->db_conf_array['DB_USER'],
			$this->db_conf_array['DB_PWD']
			);

		//选择数据库
		mysql_select_db($this->db_conf_array['DB_NAME'],$this->conn);
		

		$model_name  = $this->model_name;
		
		//拼接sql语句
		//先查询是否有条件
		$conditon_sql = '';
		if($this->condition_arr){
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
		$sql = 'select * from '.$model_name.$conditon_sql;
		$rs = mysql_query($sql,$this->conn);
		echo $sql.'-----sql';
		$result_array = array();
		while($row = mysql_fetch_array($rs)){
		    array_push($result_array,$row);
		}
		return $result_array;
		//echo 'select被调用----'.$this->model_name;
	}
	
	
	//条件
	public function where($condition=null){
		if(!$condition){
		  echo 'error :查询条件不得为空';
		  exit;
		}
		$this->condition_arr = $condition;
		return $this;
	}
}

?>