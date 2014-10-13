<?php

 if(!file_exists(APP_PATH)){
         mkdir(APP_PATH);  //创建应用目录
          if(!file_exists(APP_PATH.'Home/')){
            mkdir(APP_PATH.'Home/'); //创建Home模块
                if(!file_exists(APP_PATH.'Home/Controller/')){
                    mkdir(APP_PATH.'Home/Controller/'); //控制器
                       if(!file_exists(APP_PATH.'Home/Controller/IndexController.class.php')){
                              //创建IndexController.class.php
                              $fp = fopen(APP_PATH.'Home/Controller/IndexController.class.php','w+');
                              $string = '<?php

namespace Home\Controller;
use BillowPHP\Controller;
   class IndexController extends Controller{
      
    public function index(){
       
       $this->assign("hello","<h1>Hello BillowPHP</h1>
        <div class=\'body\'><p>欢迎使用BillowPHP v0.1Release版</p><p>如有问题欢迎指正</p><code>Author:billowton@foxmail.com</code></div>");
       $this->display();
    }

    
   }



';
                              fwrite($fp,$string);
                              fclose($fp);
                              
                       }
                }
                if(!file_exists(APP_PATH.'Home/Model/')){
                    mkdir(APP_PATH.'Home/Model/'); //模型
                }
                if(!file_exists(APP_PATH.'Home/View/')){
                    mkdir(APP_PATH.'Home/View/'); //视图
                    if(!file_exists(APP_PATH.'Home/View/Index/')){
                         mkdir(APP_PATH.'Home/View/Index/');
                         $fp = fopen(APP_PATH.'Home/View/Index/index.html',"w+");
                         $string = '<!doctype html>
<html lang=en>
 <head>
  <meta charset=UTF-8>
  <meta name=Author content=billowton@foxmail.com>
  <title>BillowPHP欢迎页</title>
   <style>
         h1 {
             color: #444;
            background-color: transparent;
            border-bottom: 1px solid #D0D0D0;
             font-size: 19px;
            font-weight: normal;
            margin: 0 0 14px 0;
            padding: 14px 15px 10px 15px;

         }
         .body{

          margin: 0 15px 0 15px;
          padding: 14px 15px 10px 15px;
         }
         p{
          display: block;
-webkit-margin-before: 1em;
-webkit-margin-after: 1em;
-webkit-margin-start: 0px;
-webkit-margin-end: 0px;
         }

         code{
          font-family: Consolas, Monaco, Courier New, Courier, monospace;
font-size: 12px;
background-color: #f9f9f9;
border: 1px solid #D0D0D0;
color: #002166;
display: block;
margin: 14px 0 14px 0;
padding: 12px 10px 12px 10px;
         }
   </style>
 </head>
 <body>
        <div style="margin: 30px;
border: 1px solid #D0D0D0;
-webkit-box-shadow: 0 0 8px #D0D0D0;">
           {$hello}
        </div>
 </body>
</html>
';
                              fwrite($fp,$string );
                              fclose($fp);

                    }
                }
            }
          }