<?php

namespace BillowPHP;
use \BillowPHP\Model;
/**
* 视图模型类
*/
class ViewModel extends Model
{
	public $viewFields=array();  //联合查询数组 （由子类完善）
    //测试数据
   // public $viewFields;

	function __construct()
	{
		parent::__construct();
	}

    /**
     * 联合查询
     * [select 重写select方法]
     *
     */
	public function select()
	{
		

		$this->connect_db();
		//获取相关sql (条件，限制，排序) 
		$conditon_sql = $this->conditon_sql;
		$limit_sql = $this->limit_sql;
		$order_sql = $this->order_sql;
        
		//拼接sql（从viewFields数组中连接查询）
		//如果viewFields数组则直接返false
		if(!$this->viewFields){

			return false;
		}

        $table_sql=' '; //连接表sql(注意连接表前缀)
        $field_sql=' '; //查询字段sql;
        $db_prefix = $this->db_conf_array['DB_PREFIX'];
		foreach ($this->viewFields as $table => $fields) {
			if(isset($fields['_on'])){
				$table_sql = $table_sql.' '.$db_prefix.$table.' on '.$fields['_on'].' '.' join';
			}else{
				$table_sql = $table_sql.' '.$db_prefix.$table.' join';
			}
			 //获取查询字段sql
			 foreach ($fields as $key => $value) {
                //如果是连接字段则不用统计到查询sql中
			 	if($key=='_on') continue;
			 	if(!is_numeric($key)){//重复字段别名
			 	     $field_sql = $field_sql.$table.'.'.$key.' as '.$value.',';
			 	 }else{
			 	 	 $field_sql = $field_sql.$value.',';
			 	 }
			 }
						
		}

		 //截取多余的一个 join
		$table_sql = substr($table_sql,0,-4);
		//截取多余的一个 ','
		$field_sql = substr($field_sql,0,-1);
		
		$sql = 'select '.$field_sql.' from '.$table_sql.$conditon_sql.$order_sql.$limit_sql;
        mysql_query('set names utf8',$this->conn);
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
}