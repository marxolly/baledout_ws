<?php

/**
 * Form controller
 *
 * processes all forms on thee site
 * @author     Mark Solly <mark@baledout.com.au>
 */

class FormController extends Controller {

    /**
     * Initialization method.
     * load components, and optionally assign their $config
     *
     */
    public function initialize(){
        $action = $this->request->param('action');
        //die('action '.$action);
        if($action == "procLogin" || $action == "procForgotPassword" || $action == "procUpdatePassword")
        {
             $this->loadComponents([
                 'Security'
             ]);
        }
        else
        {
             $this->loadComponents([
                 'Auth' => [
                         'authenticate' => ['User']
                     ],
                 'Security'
             ]);
        }

    }

    public function beforeAction(){

        parent::beforeAction();
        $action = $this->request->param('action');
        $actions = [
            'procCourierEdit',
            'procForgotPassword',
            'procJobStatusAdd',
            'procLogin',
            'procProfileUpdate',
            'procUpdatePassword',
            'procUserAdd',
            'procUserRoleAdd',
            'procUserRoleEdit'
        ];
        $this->Security->config("form", [ 'fields' => ['csrf_token']]);
        $this->Security->requirePost($actions);
    }

    public function procJobStatusAdd()
    {
        $db = Database::openConnection();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
                $post_data[$field] = $value;
            }
        }
        if( !$this->dataSubbed($name) )
        {
            Form::setError('name', 'A name is required');
        }
        elseif( ($db->fieldValueTaken('job_status', $name, 'name')) )
        {
            Form::setError('name', 'Names need to be unique');
        }
        if(Form::$num_errors > 0)		/* Errors exist, have user correct them */
        {
            Session::set('value_array', $_POST);
            Session::set('error_array', Form::getErrorArray());
        }
        else
        {
           //all good, add details
            if($this->jobstatus->addStatus($post_data))
            {
                Session::set('feedback', "Those details have been added to the system");
            }
            else
            {
                Session::set('errorfeedback', 'A database error has occurred. Please try again');
            }
        }
        return $this->redirector->to(PUBLIC_ROOT."site-settings/job-status");
    }

    public function procCourierEdit()
    {
        //echo "<pre>",print_r($this->request->data),"</pre>"; //die();
        $id = $this->request->data['line_id'];
        $post_data = array('id' => $id);
        foreach($this->request->data as $field => $value)
        {
            $field = strtok($field, "_");
            if(!is_array($value))
            {
                ${$field} = $value;
                $post_data[$field] = $value;
            }
        }

        if( !$this->dataSubbed($name) )
        {
            Form::setError('name_'.$id, 'A name is required');
        }
        if(Form::$num_errors > 0)		/* Errors exist, have user correct them */
        {
            Session::set('value_array', $_POST);
            Session::set('error_array', Form::getErrorArray());
        }
        else
        {
            //all good, add details
            if($this->courier->editCourier($post_data))
            {
                Session::set('feedback', "Those details have been updated");
            }
            else
            {
                Session::set('errorfeedback', 'A database error has occurred. Please try again');
            }
        }
        return $this->redirector->to(PUBLIC_ROOT."site-settings/couriers");
    }

    public function procUserAdd()
    {
        //echo "<pre>",print_r($this->request->data),"</pre>"; //die();
        $post_data = array();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
                $post_data[$field] = $value;
            }
        }

        if( !$this->dataSubbed($name) )
        {
            Form::setError('name', 'A name is required');
        }
        if(!$this->dataSubbed($email))
        {
            Form::setError('email', 'An email is required');
        }
        elseif( !$this->emailValid($email))
        {
            Form::setError('email', 'Please enter a valid email');
        }
        elseif( $this->user->emailTaken($email))
        {
            Form::setError('email', 'This email is already registered');
        }
        if($role_id == 0)
        {
            Form::setError('role_id', 'Please select a role');
        }
        if(Form::$num_errors > 0)		/* Errors exist, have user correct them */
        {
            Session::set('value_array', $_POST);
            Session::set('error_array', Form::getErrorArray());
        }
        else
        {
            //insert the user
            $this->user->addUser($post_data);
            Session::set('feedback', "<p>That user has been added to the system</p>");
            if(!isset($test_user))
            {
                //send the email
                Email::sendNewUserEmail($name, $email);
                $_SESSION['feedback'] .= "<p>password setup instructions have been emailed to $email</p>";
            }
        }
        return $this->redirector->to(PUBLIC_ROOT."user/add-user");
    }

    public function procProfileUpdate()
    {
        //echo "<pre>",print_r($this->request->data),"</pre>"; //die();
        $post_data = array();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
                $post_data[$field] = $value;
            }
        }
        if( !$this->dataSubbed($name) )
        {
            Form::setError('name', 'Your name is required');
        }
        //image uploads
        $field = "image";
        if($_FILES[$field]["size"] > 0)
        {
            if(getimagesize($this->request->data[$field]['tmp_name']) !== false)
            {
                $filename = pathinfo($this->request->data[$field]['name'], PATHINFO_FILENAME);
                $image_name = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);//strip out non alphanumeric characters
                $image_name = strtolower(str_replace(' ','_',$image_name));
                //main image
                $image_name = $this->uploadImage($field, 200, 200, $image_name, 'jpg', false, 'profile_pictures/');
                //thumbnail image
                //$this->uploadImage($field, 100, false, "tn_".$image_name, 'jpg', false, 'products/');
                $post_data['image_name'] = $image_name;
            }
            else
            {
                Form::setError($field, 'Only upload images here');
            }
        }
        if($this->dataSubbed($new_password))
        {
            if(!$this->dataSubbed($conf_new_password))
            {
                Form::setError('conf_new_password', 'Please retype new password for confirmation');
            }
            elseif($conf_new_password !== $new_password)
            {
                Form::setError('conf_new_password', 'Passwords do not match');
            }
            else
            {
                $post_data['hashed_password'] = password_hash($new_password, PASSWORD_DEFAULT, array('cost' => Config::get('HASH_COST_FACTOR')));
            }
        }
        if(Form::$num_errors > 0)		/* Errors exist, have user correct them */
        {
            Session::set('value_array', $_POST);
            Session::set('error_array', Form::getErrorArray());
            return $this->redirector->to(PUBLIC_ROOT . "login/resetPassword", ['id' => $this->request->data("id"), 'token' => $this->request->data("token")]);
        }
        else
        {
            $this->user->updateProfileInfo($post_data, Session::getUserId());
            //reset some session data
            Session::reset([
                "user_id"       => Session::getUserId(),
                "role"          => $this->user->getUserRoleName($role_id),
                "ip"            => $this->request->clientIp(),
                "user_agent"    => $this->request->userAgent(),
                "users_name"    => $name,
                "is_admin_user" => $this->user->isAdminUser()
            ]);
            //set the cookie to remember the user
            Cookie::reset(Session::getUserId());
        }
        return $this->redirector->to(PUBLIC_ROOT."user/profile");
    }

    public function procUserRoleEdit()
    {
        //echo "<pre>",print_r($this->request->data),"</pre>"; //die();
        $id = $this->request->data['line_id'];
        $post_data = array('id' => $id);
        foreach($this->request->data as $field => $value)
        {
            $field = strtok($field, "_");
            if(!is_array($value))
            {
                ${$field} = $value;
                $post_data[$field] = $value;
            }
        }
        //echo "<pre>",print_r($post_data),"</pre>"; die();
        $name = strtolower($name);
        $currentname = strtolower($currentname);
        if( !$this->dataSubbed($name) )
        {
            Form::setError('name_'.$id, 'A name is required');
        }
        elseif($this->user->getUserRoleId($name) && $name != $currentname)
        {
            Form::setError('name_'.$id, 'User roles need a unique name');
        }
        if(Form::$num_errors > 0)		/* Errors exist, have user correct them */
        {
            Session::set('value_array', $_POST);
            Session::set('error_array', Form::getErrorArray());
        }
        else
        {
            //all good, add details
            if($this->user->editUserRole($post_data))
            {
                Session::set('feedback', "Those details have been updated");
            }
            else
            {
                Session::set('errorfeedback', 'A database error has occurred. Please try again');
            }
        }
        return $this->redirector->to(PUBLIC_ROOT."site-settings/user-roles");
    }

    public function procUserRoleAdd()
    {
        //echo "<pre>",print_r($this->request->data),"</pre>"; //die();
        $post_data = array();
        foreach($this->request->data as $field => $value)
        {
            if(!is_array($value))
            {
                ${$field} = $value;
                $post_data[$field] = $value;
            }
        }
        $name = strtolower($name);
        if( !$this->dataSubbed($name) )
        {
            Form::setError('name', 'A name is required');
        }
        elseif($this->user->getUserRoleId($name))
        {
            Form::setError('name', 'User roles need a unique name');
        }
        if(Form::$num_errors > 0)		/* Errors exist, have user correct them */
        {
            Session::set('value_array', $_POST);
            Session::set('error_array', Form::getErrorArray());
        }
        else
        {
            //all good, add details
            if($role_id = $this->user->addUserRole($post_data))
            {
                Session::set('feedback', "That role has been added to the system");
            }
            else
            {
                Session::set('errorfeedback', 'A database error has occurred. Please try again');
            }
        }
        return $this->redirector->to(PUBLIC_ROOT."site-settings/user-roles");
    }

    public function procForgotPassword()
    {
        //echo "<pre>",print_r($this->request),"</pre>";
        $email      = $this->request->data('email');
        $userIp     = $this->request->clientIp();
        $userAgent  = $this->request->userAgent();
        Session::set('display-form', 'forgot-password');
        $db = Database::openConnection();
        if(!$this->dataSubbed($email))
        {
            Form::setError('email', 'Please enter your email address');
        }
        elseif(!$this->emailValid($email))
        {
            Form::setError('email', 'Please enter a valid email address');
        }
        if(Form::$num_errors > 0)		/* Errors exist, have user correct them */
		{
		    Session::set('value_array', $_POST);
            Session::set('error_array', Form::getErrorArray());
		}
        else
        {
            if($db->fieldValueTaken('users', $email, 'email'))
            {
                //only do stuf if the email exists in the system
                $user     = $db->queryRow("SELECT * FROM users WHERE email = :email", array('email' => $email));
                $forgottenPassword = $db->queryRow("SELECT * FROM forgotten_passwords WHERE user_id = ".$user['id']);
                $last_time = isset($forgottenPassword["password_last_reset"])? $forgottenPassword["password_last_reset"]: null;
                $count     = isset($forgottenPassword["forgotten_password_attempts"])? $forgottenPassword["forgotten_password_attempts"]: null;
                $block_time = (10 * 60);
                $time_elapsed = time() - $last_time;
                if ($count >= 5 && $time_elapsed < $block_time)
                {
                    Form::setError('general', "You exceeded number of possible attempts, please try again later after " .date("i", $block_time - $time_elapsed) . " minutes");
                    return $this->redirector->login();
                }
                $newPasswordToken = $this->login->generateForgottenPasswordToken($user["id"], $forgottenPassword);
                Email::sendPasswordReset($user['id'], $user['name'], $email, $newPasswordToken);
            }
            Session::set('feedback', "<p>An email has been sent with a reset password link. This link will remain valid for 24 hours</p>");
        }
        return $this->redirector->login();
    }

    public function procLogin()
    {
        //echo "<pre>",print_r($this->request),"</pre>";die();
        $email      = $this->request->data('email');
        $password   = $this->request->data('password');
        $userIp     = $this->request->clientIp();
        $redirect   = $this->request->data("redirect");
        $userAgent  = $this->request->userAgent();
        if($this->login->isIpBlocked($userIp))
        {
            Form::setError("general","Your IP Address has been blocked");
        }
        if(!$this->dataSubbed($email))
        {
            Form::setError('email', 'Please enter your email address');
        }
        elseif(!$this->emailValid($email))
        {
            Form::setError('email', 'Please enter a valid email address');
        }
        elseif( !$this->user->isUserActive($email) )
        {
            Form::setError('general', 'Sorry, your account has been deactivated');
        }
        elseif(!$this->login->isLoginAttemptAllowed($email))
        {
            Form::setError('general', "You exceeded number of possible attempts, please try again later after " .$this->login->getMinutesBeforeLogin($email) . " minutes");
        }
        else
        {
            $user = $this->user->getUserByEmail($email);
            $userId = isset($user["id"])? $user["id"]: null;
        }
        if(!$this->dataSubbed($password))
        {
            Form::setError('password', 'Please enter your password');
        }
        if(Form::$num_errors == 0):		/* No entry errors */
            if(password_verify($password, $user["hashed_password"]) === false)
            {
                Form::setError("general","Email and Password combination was not found");
                $this->login->handleIpFailedLogin($userIp, $email);
            }
        endif;
        if(Form::$num_errors > 0)		/* Errors exist, have user correct them */
		{
		    Session::set('value_array', $_POST);
            Session::set('error_array', Form::getErrorArray());
			return $this->redirector->login($redirect);
		}
        else
        {
            $reset_array = array(
                "user_id"       => $userId,
                "ip"            => $userIp,
                "user_agent"    => $userAgent,
                "users_name"    => $user['name'],
                "is_admin_user" => $this->user->isAdminUser($userId),
                "role"          => $this->user->getUserRoleName($user["role_id"]),
            );
            //echo "<pre>",print_r($reset_array),"</pre>";die();
            // reset session
            Session::reset($reset_array);
            //set the cookie to remember the user
            Cookie::reset($userId);

            $this->login->resetFailedLogins($email);
            $this->login->resetPasswordToken($userId);
            $redirect = ltrim($redirect, "/");
            //die('redirect');
            return $this->redirector->root($redirect);
        }
    }

    public function procUpdatePassword()
    {
        //echo "<pre>",print_r($this->request),"</pre>";
        $password        = $this->request->data("password");
        $confirmPassword = $this->request->data("confirm_password");
        $userId          = Session::get("user_id_reset_password");

        if(!$this->dataSubbed($password))
        {
            Form::setError('password', 'A new password is required');
        }
        if(!$this->dataSubbed($confirmPassword))
        {
            Form::setError('confirm_password', 'Please retype you password');
        }
        elseif($password !== $confirmPassword)
        {
            Form::setError('confirm_password', 'Passwords do not match');
        }
        if(Form::$num_errors > 0)		/* Errors exist, have user correct them */
		{
		    Session::set('value_array', $_POST);
            Session::set('error_array', Form::getErrorArray());
			return $this->redirector->to(PUBLIC_ROOT . "login/resetPassword", ['id' => $this->request->data("id"), 'token' => $this->request->data("token")]);
		}
        else
        {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT, array('cost' => Config::get('HASH_COST_FACTOR')));
            $this->login->updatePassword($hashedPassword, $userId);
            $this->login->resetPasswordToken($userId);
            // logout, and clear any existing session and cookies
            Session::remove();
            Cookie::remove($userId);
            //return $this->redirector->to(PUBLIC_ROOT."login/passwordUpdated");
            $this->view->renderWithLayouts(Config::get('VIEWS_PATH') . "layout/login/", Config::get('LOGIN_PATH') . 'passwordUpdated.php');
        }
    }

    /*********************************************************************************************************************************************************
    *   Helper functions below this
    **********************************************************************************************************************************************************/
    /*******************************************************************
    ** validates addresses
    ********************************************************************/
    private function validateAddress($address, $suburb, $state, $postcode, $country, $ignore_address_error)
    {
        if( !$this->dataSubbed($address) )
        {
            Form::setError('address', 'An address is required');
        }
        elseif( !$ignore_address_error )
        {
            if( (!preg_match("/(?:[A-Za-z].*?\d|\d.*?[A-Za-z])/i", $address)) && (!preg_match("/(?:care of)|(c\/o)|( co )/i", $address)) )
            {
                Form::setError('address', 'The address must include both letters and numbers');
            }
        }
        if(!$this->dataSubbed($postcode))
        {
            Form::setError('postcode', "A delivery postcode is required");
        }
        if(!$this->dataSubbed($country))
        {
            Form::setError('country', "A delivery country is required");
        }
        elseif(strlen($country) > 2)
        {
            Form::setError('country', "Please use the two letter ISO code");
        }
        elseif($country == "AU")
        {
            if(!$this->dataSubbed($suburb))
    		{
    			Form::setError('suburb', "A delivery suburb is required for Australian addresses");
    		}
    		if(!$this->dataSubbed($state))
    		{
    			Form::setError('state', "A delivery state is required for Australian addresses");
    		}
            $aResponse = $this->Eparcel->ValidateSuburb($suburb, $state, str_pad($postcode,4,'0',STR_PAD_LEFT));
            $error_string = "";
            if(isset($aResponse['errors']))
            {
                foreach($aResponse['errors'] as $e)
                {
                    $error_string .= $e['message']." ";
                }
            }
            elseif($aResponse['found'] === false)
            {
                $error_string .= "Postcode does not match suburb or state";
            }
            if(strlen($error_string))
            {
                Form::setError('postcode', $error_string);
            }
        }
    }

    /*******************************************************************
    ** validates empty data fields
    ********************************************************************/
	protected function dataSubbed($data)
	{
		if(!$data || strlen($data = trim($data)) == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
	}//end dataSubbed()

    /*******************************************************************
   ** validates email addresses
   ********************************************************************/
	private function emailValid($email)
	{
		if(!$email || strlen($email = trim($email)) == 0)
		{
         	return false;
      	}
      	else
		{
            return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        	 /* Check if valid email address
         	$regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
         	if(!preg_match($regex,$email))
			{
            	return false;
         	}
         	else
			{
				return true;
			}
            */
      	}
	}//end emailValid()

    /*******************************************************************
   ** Returns human readable errors for file uploads
   ********************************************************************/
	private function file_upload_error_message($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the maximum upload size allowed by the server';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the maximum upload size allowed by the server';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was selected for uploading';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        	}
	}



    private function uploadImage($field, $width, $height = false, $picturename = "image", $format = 'jpg', $overwrite = false, $dir = '/images/uploads/')
    {
        //namespace Verot\Upload;
        if ($_FILES[$field]['error']  === UPLOAD_ERR_OK)
        {//////////////////////////////////////////////////////////////////////only if entered?
                //$handle = new Upload($_FILES[$field]);
                $handle = new \Verot\Upload\Upload($_FILES[$field]);
                if($handle->uploaded)
                {
                    //file uploaded.
                    //die($field);
                        //Image settings
                        $handle->image_resize = true;
                        $handle->image_ratio = true;
                        $handle->file_auto_rename = !$overwrite;
                        $handle->file_overwrite = $overwrite;
                        $handle->image_x = $width;
                        if($height)
                        {
                            $handle->image_y = $height;
                            $handle->image_ratio = true;
                        }
                        else
                        {
                            $handle->image_ratio_y = true;
                        }
                        $handle->file_new_name_body = $picturename;
                        $handle->image_convert = $format;
                        $handle->Process(IMAGES.$dir);
                        if(!$handle->processed)
                        {
                            Form::setError($field, $handle->error);
                        }
                        return $handle->file_dst_name_body;
                }
                else
                {
                    //error uploading file
                    Form::setError($field, $handle->error);
                }
        }///end if picture uploaded
        else
        {
            //error uploading file
            $error_message = $this->file_upload_error_message($_FILES[$field]['error']);
            Form::setError($field, $error_message);
        }
    }//end function
}
?>