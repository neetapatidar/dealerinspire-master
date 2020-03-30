<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!class_exists("messageHandler")):
class messageHandler{
	
	public static $_message = null; 
	public function __construct($task=null)
	{}
	public	static function set($arr)
	{		
		if(isset($arr["message"])){			
			$_SESSION["notify"] = $arr["message"];
			self::$_message["notify"]=$_SESSION["notify"];
		}
		if(isset($arr["class"])){
			$_SESSION["class"] = $arr["class"];
			self::$_message["class"]=$_SESSION["class"];
		}	
	}
	public	static function setVars($arr)
	{		
		if(isset($arr) ){			
			$_SESSION["fields"] = $arr;	
			
		}
	}
	public	static function getVars()
	{		
		if(isset($_SESSION["fields"]) ){			
			$arr = $_SESSION["fields"];	
			unset($_SESSION["fields"]);	
			return $arr;		
		}
		return false;
	}
	public	static function get()
	{
		if(isset($_SESSION["notify"])){
		self::$_message["notify"]=$_SESSION["notify"];	
		}
		if(isset($_SESSION["class"])){
		self::$_message["class"]=$_SESSION["class"];	
		}	
		return self::$_message;
	}
	public static function clear()
	{
		unset($_SESSION["notify"]);
		unset($_SESSION["class"]);
		self::$_message=null;
	}
	public static function show()
	{
		$output="";
		$class="jb_success";
		
		$msg = messageHandler::get();
		if(isset($msg["class"])){
			$class=$msg["class"];			
		}
		if(isset($msg["notify"]) && !empty($msg["notify"])){
            
            if(is_array($msg["notify"]))
            {
                $output.="<div class='$class'>";
                $output.='<ul class="jbulli">';
                foreach($msg["notify"] as $singlemessage)
                {
                    $output.='<li>'.$singlemessage.'</li>';
                }
                $output.='</ul>';
            }
            else
            {
				$output.="<div class='$class'>";
                $output.='<div class="'.$class.'">'.$msg["notify"].'</div>';
            }
            $output.="</div>";			
			$output.="<script type='text/javascript'>";
			$output.="$( document ).ready(function() {";
			$output.="$('.{$class}').fadeOut(40000);";			
			$output.="});</script><style>div.jb_success{background:#70DF79;color: #ffffff;width: 98%;padding: 1%;}div.jb_error{background:#F5A9A9;color: #ffffff;width:100%;padding:1%;}.message_upper{min-height:40px;padding-bottom: 40px;}</style>";
		}
		echo $output;
		messageHandler::clear();
	}
	
}//end of class
endif;
?>