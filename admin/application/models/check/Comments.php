<?php
defined('_ACCESS') or die;

class models_check_Comments extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('author', '');
        $this->addData('name', '');
        $this->addData('parent_id', '');
        $this->addData('chain', '');
        $this->addData('email', '');
        $this->addData('comment', '');
        $this->addData('page_id', '');
    }
    
    public function checkForm()
    {
        $action = $this->get('action');
        
        if($this->isPost() && $action)
        {
            $name = $this->get('name');
            $comment = $this->get('comment');
            
            if (!$name)  $this->notices->addError($this->message['comments_check_author']);
            if (!$comment)  $this->notices->addError($this->message['comments_check_text']);
                        
            $this->addData('name',$name);
            $this->addData('parent_id',$this->get('parent_id'));
            $this->addData('chain',$this->get('chain'));
            $this->addData('email',$this->get('email'));
            $this->addData('comment',$comment);
            $this->addData('page_id',$this->get('page_id'));
            
            return true;
        }
    
        return false;
    }
}
?>