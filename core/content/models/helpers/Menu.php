<?php
defined('_ACCESS') or die;

class models_helpers_Menu
{
    private static $instance;
    protected $menu;

    public static function instance()
    {
        if (!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    public function getMenu()
    {
        return $this->getMenuData();
    }

    public function getMenuData()
    {
        $language  = models_helpers_Url::getConfigLanguage();
        return Database::instance()->select_full('SELECT name, anchor FROM ' . models_BaseModel::TBL_WME . ' WHERE language = "' . $language . '"
                                                  GROUP BY id', null, Database::RETURN_DATA_ASSOC);
    }
}