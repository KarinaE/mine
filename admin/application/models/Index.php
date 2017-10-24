<?php
defined('_ACCESS') or die;
    
class models_Index extends models_BaseModel
{
    public function getComponents()
    {
        $language  = models_helpers_Url::getConfigLanguage();
        $res = $this->db->select_full('
            SELECT * FROM ' . self::TBL_COMPONENTS . ' WHERE language = "' . $language . '" ORDER BY id DESC', null, Database::RETURN_DATA_ASSOC
        );

        return $res;
    }
    
    public function getPopularContent()
    {
        $res = $this->db->select_full('
            SELECT name,id,views,from_unixtime(date_add, "%d.%c.%y") as date_add 
            FROM ' . self::TBL_CONTENT .' 
            ORDER BY views DESC
            LIMIT 7' , null, Database::RETURN_DATA_ASSOC
        );

        return $res;
    }
    
    public function getLastContent()
    {
        $res = $this->db->select_full('
            SELECT t1.name,t1.id,from_unixtime(t1.date_add, "%d.%c.%y") as date_add, t2.name AS author_name 
            FROM ' . self::TBL_CONTENT .' as t1
            LEFT JOIN ' . self::TBL_USERS .' as t2 ON (t1.author = t2.id)
            ORDER BY t1.date_add DESC
            LIMIT 5' , null, Database::RETURN_DATA_ASSOC
        );
        
        return $res;
    }
    
    public function getEditedContent()
    {
        $res = $this->db->select_full('
            SELECT t1.name,t1.id,from_unixtime(t1.date_upd, "%d.%c.%y %h:%i") as date_edit, t2.name AS author_edit 
            FROM ' . self::TBL_CONTENT .' as t1
            JOIN ' . self::TBL_USERS .' as t2 ON (t1.author = t2.id) 
            ORDER BY date_upd DESC
            LIMIT 7' , null, Database::RETURN_DATA_ASSOC
        );

        return $res;
    }
    
//    public function getUserStats($id)
//    {
//        $res = $this->db->select_full('
//            (SELECT COUNT(id) as quntity FROM ' . self::TBL_CONTENT .' WHERE author = ' . $id . ')
//            UNION
//            (SELECT COUNT(id) FROM ' . self::TBL_CATEGORIES .' WHERE author = ' . $id . ')
//            UNION
//            (SELECT COUNT(id) FROM ' . self::TBL_MODULES .' WHERE author = ' . $id . ')
//            UNION
//            (SELECT COUNT(id) FROM ' . self::TBL_TEMPLATES .' WHERE author = ' . $id . ')
//            ' , null, Database::RETURN_DATA_ASSOC
//        );
//
//        return $res;
//    }
    
    public function getUsersLogs()
    {
        $res = $this->db->select_full('
            SELECT id,name,last_visit 
            FROM ' . self::TBL_USERS . '
            ORDER BY DATE(last_visit) DESC
            LIMIT 21
            ', null, Database::RETURN_DATA_ASSOC
        );

        return $res;
    }
    
}
?>