<?php
defined('_ACCESS') or die;


class models_helpers_Menu
{
    private static $instance;
    private $cfg;

    private function __construct()
    {
        $this->cfg = Settings::instance()->getParam('main_menu');

        if (!is_array($this->cfg) || !sizeof($this->cfg))
        {
            return 'Menu configuration cannot be initialized.';
        }
    }

    public static function instance()
    {
        if (!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    public function printMenu()
    {
        $res = $this->getMenuData();
      //print_r($res); die;

        if (is_array($res))
        {
            Viewer::instance()->assign('admmenu', $res);
            return Viewer::instance()->getBuffered($this->cfg['tpl']);
        }

        return false;
    }

    public function getMenuData($byIdent = null, $id_parent = 0)
    {
        $user_info = Session::instance()->get('admin_userInfo');
        $language  = models_helpers_Url::getConfigLanguage();

        if (isset ($user_info['user_types_id']) && $user_info['user_types_id'])
        {
            return Database::instance()->select_full('
                SELECT t1.*,t3.name
                FROM ' . models_BaseModel::TBL_MENU . ' AS t1
                JOIN ' . models_BaseModel::TBL_MENU_REL . ' AS t2 ON (t2.menu_id = t1.id)
                JOIN ' . models_BaseModel::TBL_MENU_NAME . ' AS t3 ON (t3.id = t1.id)
                WHERE t2.user_types_id = "' . $user_info['user_types_id'] . '" AND t2.access = 1
                AND t1.id_parent ' . ((int)$id_parent ? '= ' . (int)$id_parent : 'is NULL') . '
                AND t3.language = "' . $language . '"
                GROUP BY t1.id
                ORDER BY t1.sort DESC
            ', null, Database::RETURN_DATA_ASSOC);
        }

        return false;
    }
}