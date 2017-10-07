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

      if (!$login) $this->notices->addError('Поле "login" обязательное!');

      $passw  = $this->get('passw');
      $check  = $this->get('passw', FILTER_SANITIZE_STRING);

      if (!$passw) $this->notices->addError('Поле "Пароль" обязательное!') ;
      if (!$check) $this->notices->addError('Поле "Пароль" указано не корректно!');
      
      $this->addData('login', $login);
      $this->addData('passw', $passw);
      return true;
    }
    
    return false;
  }
}
?>