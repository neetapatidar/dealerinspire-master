<?php
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

/* These configurations need to be updated as per the environment set-up */
define('SITE_URL','http://localhost:8080/dealerinspire-master/public/');
define('HOST','localhost');
define('USER_NAME','root');
define('PASSWORD','');
define('DBNAME','contact_form_submission');

function __autoload($className){  
   $path = dirname(__FILE__).'/';   
   if(!empty($className) && file_exists($path.$className.'.php')){
     
       require_once($path.$className.'.php');
   }
}
if(isset($_POST['submit']) && isset($_POST['controller']) && isset($_POST['task']) && $_POST['controller']!='' && $_POST['task']!='')
{
   switch(trim($_POST['controller']))
   {
      case "formController":
         $task = trim($_POST['task']);
         $obj = new formController($task);
      break;      

      default:
      echo "Controller not found";
   }   
}


?>