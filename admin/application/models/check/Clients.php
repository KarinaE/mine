<?php
defined('_ACCESS') or die;

class models_check_Clients extends models_check_BaseForm
{
    public function initDefaults()
    {
        $this->addData('first_name', '');
        $this->addData('last_name', '');
        $this->addData('gender', '');
        $this->addData('birth_date', '');
        $this->addData('comments', '');
        $this->addData('avatar', '');
        $this->addData('bonuces', '');
    }

    public function checkForm()
    {
        $action = $this->get('action');


        if($this->isPost() && $action)
        {
            $fname = $this->get('name');

            if (!$fname) $this->notices->addError($this->message['clients_check_name']);

            $this->addData('first_name',  $fname);
            $this->addData('last_name',   $this->get('lname'));
            $this->addData('gender',      $this->get('gender'));
            $this->addData('birth_date',  $this->get('birth_date'));
            $this->addData('comments',    $this->get('comments'));
            $this->addData('avatar',      $this->get('avatar'));
            $this->addData('bonuces',     $this->get('bonuces'));
            return true;
        }

        return false;
    }
}
?>