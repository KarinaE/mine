<?php
defined('_ACCESS') or die;

class models_check_Suppliers extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('name', '');
        $this->addData('contact_person', '');
        $this->addData('phone', '');
        $this->addData('email', '');
        $this->addData('image', '');
        $this->addData('author', '');
    }

    public function checkForm()
    {
        $action = $this->get('action');

        if($this->isPost() && $action)
        {
            $name = $this->get('name');

            if (!$name) $this->notices->addError($this->message['suppliers_check_name']);

            $this->addData('name',  $name);
            $this->addData('contact_person', $this->get('contact_person'));
            $this->addData('phone',          $this->get('phone'));
            $this->addData('birth_date',     $this->get('birth_date'));
            $this->addData('email',          $this->get('email'));
            $this->addData('image',          $this->get('image'));
            $this->addData('author',      $this->get('author'));
            return true;
        }

        return false;
    }
}
?>