<?php
defined('_ACCESS') or die;

class models_check_Product extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('name', '');
        $this->addData('image', '');
        $this->addData('author', '');

    }

    public function checkForm()
    {
        $action = $this->get('action');

        if($this->isPost() && $action)
        {
            $name = $this->get('name');

            if (!$name)  $this->notices->addError($this->message['product_check_name']);


            $this->addData('name',   $name);
            $this->addData('image',  $this->get('image'));
            $this->addData('author', $this->get('author'));

            return true;
        }

        return false;
    }
}
?>