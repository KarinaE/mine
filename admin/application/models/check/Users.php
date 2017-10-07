<?php
defined('_ACCESS') or die;

class models_check_Users extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('name', '');
        $this->addData('reg_date', '');
        $this->addData('login', '');
        $this->addData('passw', '');
        $this->addData('salt', '');
        $this->addData('utype', '');
    }
    
    public function checkForm()
    {
        $action = $this->get('action');
        
        if($this->isPost() && $action)
        {
            $name = $this->get('name');
            if (!$name)  $this->notices->addError($this->message['users_check_name']);
            
            $login = $this->get('login');            
            if (!$login)  $this->notices->addError($this->message['users_check_login']);
            
            $passw  = $this->get('passw');
            $cpassw = '';
            if ($action == 'add' || $passw)
            {
                $cpassw = $this->get('cpassw');
                if (!$passw)  $this->notices->addError($this->message['users_check_passw']);
                if (!$cpassw) $this->notices->addError($this->message['users_check_passw_repeate']);
                
                if($passw !== $cpassw)
                  $this->notices->addError($this->message['users_check_passw_error']);
            }
            
            $user_types_id = $this->get('user_types_id');
            if (!$user_types_id)  $this->notices->addError($this->message['users_check_user_type']);
            
            $this->addData('name',     $name);
            $this->addData('reg_date', $reg_date);   
            $this->addData('passw',    $passw);
            $this->addData('login',    $login);
            $this->addData('salt',     $this->get('salt'));
            $this->addData('utype',    $user_types_id);
            
            return true;
        }
    
        return false;
    }
}
?>