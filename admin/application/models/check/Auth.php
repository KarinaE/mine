<?php
defined('_ACCESS') or die;

class models_check_Auth extends models_check_BaseForm
{
    
  public function  initDefaults()
  {
    
  }
  public function checkForm()
  {
        if( $this->isPost() )
        {
            $login = $this->get('login');
            $salt = 'aExVwKYfa77xTZC';
            if (!$login) $this->notices->addError($this->message['auth_check_login']);

            $passw  = crypt($this->get('passw'),$salt);
            $check  = $this->get('passw', FILTER_SANITIZE_STRING);

            if (!$passw) $this->notices->addError($this->message['auth_check_passw']) ;
            if (!$check) $this->notices->addError($this->message['auth_check_passw_error']);

            $this->addData('login', $login);
            $this->addData('passw', $passw);
            return true;
        }

        return false;
  }
}
?>