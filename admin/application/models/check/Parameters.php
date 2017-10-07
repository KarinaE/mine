<?php
defined('_ACCESS') or die;

class models_check_Parameters extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('author', '');
        $this->addData('name', '');
        $this->addData('type', '');
        $this->addData('half', '');
        $this->addData('image', '');
        $this->addData('options_array', '');
    }
    
    public function checkForm()
    {
        $action = $this->get('action');
        
        if($this->isPost() && $action)
        {
            $name = $this->get('name');
            $type = $this->get('type');
            
            $half = $this->get('half');
            $half = empty($half) ? 2 : 1;
            
            if (!$name)  $this->notices->addError($this->message['parameters_check_name']);
            if (!$type)  $this->notices->addError($this->message['parameters_check_type']);

            if ($type == 'slider' || $type == 'dblslider')
                $options_array = $this->serializeParams();
            
            $this->addData('author', $this->get('author'));
            $this->addData('name',   $name);
            $this->addData('type',   $this->get('type'));
            $this->addData('half',   $half);
            $this->addData('image',  $this->get('image'));
            if ($type == 'slider' || $type == 'dblslider')
                $this->addData('options_array',   $options_array);
            
            return true;
        }
    
        return false;
    }
    
    private function serializeParams()
    {
        return serialize(array('min'=>$this->get('min')*1,'max'=>$this->get('max')*1,'step'=>$this->get('step')*1,'multiply'=>$this->get('multiply')*1));
    }
}
?>