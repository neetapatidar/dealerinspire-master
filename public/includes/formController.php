<?php
if (!class_exists("formController")):
class formController
{
    public function __construct($task=null)
	{			
        if(!$task)
        {			
            $this->callErrorview();
        }
      else
      {
           if(method_exists($this,$task))
           {
              $this->$task(array_merge($_REQUEST,$_POST));
           }
           else
           {
              $this->callErrorview();
           }              
      }
	}
    public function submitForm($requestedVars)
    {
        unset($requestedVars['controller']);
        unset($requestedVars['task']);
        unset($requestedVars['submit']);        
        $returnRespond  = Helper::validateContactFormData($requestedVars);        
        if(isset($returnRespond['error']) && is_array($returnRespond['error']) && !empty($returnRespond['error']))
        {
            $message["message"] = $returnRespond['msg'];
            $message['class'] = 'jb_error';
            messageHandler::set($message);  
            messageHandler::setVars($requestedVars);  /*setting up the form variables if there is any error */                  
        }
        else
        {
            $this->dbObject = new DBHandler();  
            $table_name = 'form_submission';
            $return  = $this->dbObject->save($requestedVars,$table_name);    
            
            if($return)
            {
                $message["message"] = "Form submitted successfully";
                $message['class'] = 'jb_success';
                messageHandler::set($message); 
                $body = '<html>
                <head>
                  <title>Contact form submission</title>
                </head>
                <body>
                  <p>Hi Admin,</p>
                  <p>Following are the entries: </p>
                  <table>
                    <tr>
                        <td><strong>Full Name: </strong>'.$requestedVars['sender_name'].'</td>
                    </tr>
                    <tr>
                        <td><strong>Email: </strong>'.$requestedVars['sender_email'].'</td>
                    </tr>
                    <tr>
                        <td><strong>Phone: </strong>'.$requestedVars['sender_phone'].'</td>
                    </tr>
                    <tr>
                        <td><strong>Message: </strong>'.$requestedVars['sender_message'].'</td>
                    </tr>
                  </table>
                </body>
                </html>';
              
                $emailData = array('to'=>'guy-smiley@example.com','from_email'=>'info@site.com','from'=>'Dealer Inspire','subject'=>'New form submission','cc'=>array(),'bcc'=>array(),'body'=>$body);
                $emailResponse = Helper::SendEMail($emailData) ;  
                if(isset($emailResponse['error']) && is_array($emailResponse['error']) && !empty($emailResponse['error']))
                {
                    $message["message"] = $emailResponse['msg'];
                    $message['class'] = 'jb_error';
                    messageHandler::set($message); 
                }
                else if($emailResponse == false)
                {
                    $message["message"] = "Error in sending email";
                    $message['class'] = 'jb_error';
                    messageHandler::set($message); 
                }
                else
                {
                    $message["message"] = "Mail sent successfully";
                    $message['class'] = 'jb_success';
                    messageHandler::set($message); 
                }                       
            }
            else
            {
                $message["message"] = "Error in processing please try again";
                $message['class'] = 'jb_error';
                messageHandler::set($message);
                messageHandler::setVars($requestedVars);  /*setting up the form variables if there is any error */      
            }
        } 
        
        $redirect_url =  SITE_URL.'#contact';
        ob_start();            
        header('Location: '.$redirect_url);exit;           
    }
    public function callErrorview($requested_vars)
	{
		echo "error...! Task not found.";exit;
	}
}
endif;
?>