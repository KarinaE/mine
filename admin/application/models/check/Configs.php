<?php
defined('_ACCESS') or die;

class models_check_Configs extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('admin_folder', '');
        $this->addData('path', '');
        $this->addData('sitename', '');
        $this->addData('users_config', '');
        $this->addData('admin_alias', '');
        $this->addData('admin_lang', '');
    }
    
    public function checkForm()
    {
        $action = $this->get('action');
        
        if($this->isPost() && $action)
        {
            $sitename = $this->get('sitename');
            
            if (!$sitename)  $this->notices->addError($this->message['configs_check_name']);

            $this->addData('admin_folder', $this->get('admin_folder'));
            $this->addData('path', $this->get('path'));
            $this->addData('sitename', $sitename);
            $this->addData('users_config', $this->get('users_config'));
            $this->addData('admin_alias', $this->get('admin_alias'));
            $this->addData('admin_lang', $this->get('admin_lang'));
            
            return true;
        }
    
        return false;
    }
}
?>