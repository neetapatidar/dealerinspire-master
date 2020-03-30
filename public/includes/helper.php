<?php
if (!class_exists("Helper")):
    class Helper
    {
        public function __construct(){}
        public static function isValidEmail($email)
        {
            if(!empty(trim($email)))
            {
                if (filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            return false;
        }
        public static function isNonEmpty($string)
        {
            if(!empty(trim($string)))
            {
                return true;
            }
            return false;
        }
        public static function checkLength($string,$number_of_character)
        {
            if(!empty(trim($string)) && strlen(filter_var($string, FILTER_SANITIZE_STRING)) <= $number_of_character)
            {
                return true;
            }
            return false;
        }
        public static function validateContactFormData($postedArray)
        {
            $returnRespond = array();
            if(isset($postedArray['sender_name']) && self::isNonEmpty($postedArray['sender_name']) == false && self::checkLength($postedArray['sender_name'],50)==false)
            {
                $returnRespond['error']['sender_name'] = true;
                $returnRespond['msg'][] = "Full name should not be empty and not more than 50 characters";            
            }
            if(isset($postedArray['sender_email']) && self::isNonEmpty($postedArray['sender_email']) == false)
            {
                $returnRespond['error']['sender_email'] = true;
                $returnRespond['msg'][] = "Email should not be empty";            
            }
            if(isset($postedArray['sender_email']) && self::isValidEmail($postedArray['sender_email'])==false)
            {
                $returnRespond['error']['sender_email'] = true;
                $returnRespond['msg'][] = "Email should be valid";            
            }
            if(isset($postedArray['sender_message']) && self::isNonEmpty($postedArray['sender_message']) == false)
            {
                $returnRespond['error']['sender_message'] = true;
                $returnRespond['msg'][] = "Message should not be empty";            
            }
            return $returnRespond;
        }
        public static function getCurrentURL()
        {
            $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
            $currentURL .= $_SERVER["SERVER_NAME"];
        
            if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
            {
                $currentURL .= ":".$_SERVER["SERVER_PORT"];
            }         
            $currentURL .= $_SERVER["REQUEST_URI"];
            return $currentURL;
        }
        public static function getBaseUrl() 
        {
            // output: /myproject/index.php
            $currentPath = $_SERVER['PHP_SELF']; 
            
            // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
            $pathInfo = pathinfo($currentPath); 
            
            // output: localhost
            $hostName = $_SERVER['HTTP_HOST']; 
            
            // output: http://
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
            
            // return: http://localhost/myproject/
            return $protocol.$hostName.$pathInfo['dirname']."/";
        }
        public static function validateEmailData($postedArray)
        {
            $returnRespond = array();
            if(isset($postedArray['to']) && self::isNonEmpty($postedArray['to']) == false)
            {
                $returnRespond['error']['to'] = true;
                $returnRespond['msg'][] = "'To' Email is missing";            
            }
            if(isset($postedArray['from']) && self::isNonEmpty($postedArray['from']) == false)
            {
                $returnRespond['error']['from'] = true;
                $returnRespond['msg'][] = "'From' Name / Site name is missing";            
            }
            if(isset($postedArray['from_email']) && self::isNonEmpty($postedArray['from_email']) == false)
            {
                $returnRespond['error']['from_email'] = true;
                $returnRespond['msg'][] = "'From Email' is missing";            
            }
            if(isset($postedArray['from_email']) && self::isValidEmail($postedArray['from_email'])==false)
            {
                $returnRespond['error']['from_email'] = true;
                $returnRespond['msg'][] = "'From Email' should be valid";            
            }            
            return $returnRespond;
        }
        public static function SendEMail($emailData)
        {
            $validateEmail = self::validateEmailData($emailData);
            if(isset($validateEmail['error']) && !empty($validateEmail['error']))
            {
                return $validateEmail;
            }
            else
            {
                $headers = array();
                $adminemail = $emailData['from_email'];
                $site_name = $emailData['from'];
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=iso-8859-1';
                // Additional headers                
                $headers[] = 'From: '.$site_name.' <'.$adminemail.'>';
                if(isset($emailData['cc']) && is_array($emailData['cc']))
                {
                    $headers[] = 'Cc: '.implode(', ',$emailData['cc']);
                }
                if(isset($emailData['bcc']) && is_array($emailData['bcc']))
                {
                    $headers[] = 'Bcc: '.implode(', ',$emailData['cc']);
                } 
                $to = $emailData['to'];
                $subject = filter_var($emailData['subject'], FILTER_SANITIZE_STRING);                
                $body = $emailData['body'];
                if(mail($to, $subject, $body, implode("\r\n", $headers)))
                {                    
                    return true;
                }
                else
                {
                    return false;
                }                
            }
        }

    }
endif;
?>