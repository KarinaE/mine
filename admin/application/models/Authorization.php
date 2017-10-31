<?php
defined('_ACCESS') or die;

class models_Authorization extends models_BaseModel
{
    public function checkAuthAdmin(models_check_Auth $data)
    {
        $data = $data->getData();
        
        $res = $this->db->select(self::TBL_USERS, '*, DATE_FORMAT(last_visit, "%d.%m.%y %H:%i") AS visit', 'WHERE login = "' . $data->login . '"', null, Database::RETURN_DATA_ARR);

        if(is_array($res))
        {
            if($res[0]['status'] == 0)
                $this->notices->addError($this->message['auth_blocked']);

            if(!self::checkPassw($res[0]['passw'], $data->passw))
                $this->notices->addError($this->message['auth_notfound']);

            if(!$this->notices->hasError())
            {
                $userInfo = array(
                    'id' => $res[0]['id'],
                    'login' => $res[0]['login'],
                    'name' => $res[0]['name'],
                   // 'vizapassw' => $data->passw,
                   // 'passw' => $res[0]['passw'],
                    'last_visit' => $res[0]['visit']
                );


                Session::instance('default')->set('user_logged', 1);
                Session::instance()->set('admin_userInfo', $userInfo);
                $this->db->update(self::TBL_USERS, array('last_visit' => date('Y-m-d H:i:s')), 'id = ' . $res[0]['id']);
                return true;
            }
        } else $this->notices->addError($this->message['auth_login_notfound']);

        return false;
    }

    /**
    * Проверяет залогинен ли админ
    * @return bool
    */
    public function loggedAdmin()
    {
        $info = $this->session->get('admin_userInfo');
        return $info && is_array($info) ? true : false;
    }

    public function unsetAdmin()
    {
        Session::instance()->delete('admin_userInfo');

        Session::instance()->setNamespace('default')->delete('user_logged');
        return true;
    }

    static public function checkPassw($passw, $input_password)
    {
        return models_helpers_Access::hash_equals($passw, $input_password);
    }

//    static public function generateHash($passw, &$salt, $nosalt = false)
//    {
//        if(!$salt && !$nosalt)
//            $salt = substr(md5(rand()), 0,8);
//
//        return md5(md5($passw) . $salt);
//    }
}