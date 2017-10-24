<?php
defined('_ACCESS') or die;

class models_helpers_Url
{
    public static function getValidBaseUrl()
    {
        return  self::getUserPath().'/';
    }
    
    public static function getUserPath()
    {
        $res = Database::instance()->select_full('
            SELECT path,admin_folder
            FROM '.models_BaseModel::TBL_OPT.'
            WHERE id = 1'
        );

        return $res[0]->path.$res[0]->admin_folder;
    }
    
    public static function getAdmin()
    {
        $res = Database::instance()->select_full('
            SELECT admin_folder
            FROM '. models_BaseModel::TBL_OPT.'
            WHERE id = 1'
        );

        return $res[0]->admin_folder ;
    }

    public static function getDomain()
    {
        $res = Database::instance()->select_full('
            SELECT path
            FROM '. models_BaseModel::TBL_OPT.'
            WHERE id = 1'
        );
        return $res[0]->path;
    }

    public static function getSiteName()
    {
    $res = Database::instance()->select_full('
            SELECT sitename
            FROM '. models_BaseModel::TBL_OPT.'
            WHERE id = 1'
        );

        return $res[0]->sitename;
    }

    public static function getConfigLanguage()
    {
        $res = Database::instance()->select_full('
            SELECT admin_lang
            FROM '. models_BaseModel::TBL_OPT.'
            WHERE id = 1'
        );

        return is_array($res) ? $res[0]->admin_lang : false;
    }
}
