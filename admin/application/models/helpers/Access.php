<?php
defined('_ACCESS') or die;

class models_helpers_Access
{
    static public function isUserLogged()
    {
        return (bool)Session::instance('default')->get('user_logged');
    }
    
    static public function getAuthInfoAdmin()
    {
        $res = Session::instance()->get('admin_userInfo');

        // dynamic change user type (for debug)
        if ($res && (!empty($_GET['set_utype_force']) || !empty($_COOKIE['utype_force'])))
        {
            $type = !empty($_GET['set_utype_force']) ? $_GET['set_utype_force'] : $_COOKIE['utype_force'] ;
            
            if (empty($_COOKIE['utype_force']) || $type != $_COOKIE['utype_force'])
                setcookie('utype_force', $type, null, '/');
            
            $res['type'] = $type;
        }
        
        return $res;
    }
    
    static public function pageAccess($controller = null)
    {
        $user_info = self::getAuthInfoAdmin();
        if (!$controller) $controller = Request::instance()->getController();
        
        if (!empty($user_info) && $controller)
        {
            $res = Database::instance()->select_full('
                SELECT t2.access 
                FROM ' . models_BaseModel::TBL_MENU . ' AS t1
                LEFT JOIN ' . models_BaseModel::TBL_MENU_REL . ' AS t2 ON (t2.menu_id = t1.id)
                WHERE t1.ident = "' . $controller . '" AND t2.user_types_id=' . $user_info['user_types_id']);

            return is_array($res) ? $res[0]->access : false;
        }
        
        return false;
    }
    
    static public function checkAccess($type,$controller = null)
    {
        $user_info = self::getAuthInfoAdmin();
        if (!$controller) $controller = Request::instance()->getController();
        
        if (!empty($user_info) && $controller)
        {
            $res = Database::instance()->select_full('
                SELECT t2.' . $type . ' 
                FROM ' . models_BaseModel::TBL_MENU . ' AS t1
                LEFT JOIN ' . models_BaseModel::TBL_MENU_REL . ' AS t2 ON (t2.menu_id = t1.id)
                WHERE t1.ident = "' . $controller . '" AND t2.user_types_id=' . $user_info['user_types_id']);

            return $res[0]->$type;
        }
        
        return 0;
    }
}
