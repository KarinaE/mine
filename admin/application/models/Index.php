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