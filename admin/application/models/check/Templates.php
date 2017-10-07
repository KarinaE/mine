<?php
defined('_ACCESS') or die;

class models_check_Templates extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('author', '');
        $this->addData('name', '');
    }
    
    public function checkForm()
    {
        $action = $this->get('action');
        
        if($this->isPost() && $action)
        {
            $name = $this->get('name');
            
            if (!$name)  $this->notices->addError($this->message['templates_check_name']);
                        
            $this->addData('author',   $this->get('author'));
            $this->addData('name',     $name);
            
            return true;
        }
    
        return false;
    }
}
?>