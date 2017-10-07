<?php
defined('_ACCESS') or die;

class models_check_Categories extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('author', '');
        $this->addData('name', '');
        $this->addData('main', '');
        $this->addData('page', '');
        $this->addData('ordr', '');
        $this->addData('parent_id', '');
        $this->addData('category', '');
    }
    
    public function checkForm()
    {
        $action = $this->get('action');
        
        if($this->isPost() && $action)
        {
            $name = $this->get('name');
            
            if (!$name)  $this->notices->addError($this->message['category_check_name']);
            
            $category = $this->get('new_category');
            $category = !$category ? $this->get('category') : $this->get('new_category');
            
            $main = $this->get('main');
            $main = empty($main) ? 2 : 1;
            
            $parent_id = $this->get('parent_id');
            $parent_id = empty($parent_id) ? 0 : $parent_id;
            
            $this->addData('author',    $this->get('author'));
            $this->addData('name',      $name);
            $this->addData('main',      $main);
            $this->addData('page',      $this->get('page'));
            $this->addData('ordr',      $this->get('ordr'));
            $this->addData('parent_id', $parent_id);
            $this->addData('category',  $category);
            
            return true;
        }
    
        return false;
    }
}
?>