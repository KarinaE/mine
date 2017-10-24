<?php
defined('_ACCESS') or die;
    
class models_Configs extends models_BaseModel
{    
    public function updateItem(stdClass $data)
    {
        $arr = array(
            'admin_folder' => $data->admin_folder,
            'path'         => $data->path,
            'sitename'     => $data->sitename,
            'users_config' => $data->users_config,
            'admin_alias'  => $data->admin_alias,
            'admin_lang'   => $data->admin_lang
        );
        
        return $this->db->update(self::TBL_OPTIONS, $arr, 'WHERE id=1');
    }
    
    public function getOne()
    {
    
        $res = $this->db->select_full('
            SELECT
            *
            FROM
            ' .  self::TBL_OPTIONS . '
        ', null, Database::RETURN_DATA_ASSOC);
        return is_array($res) ? $res[0] : false;
    }
    
    public function getLanguages()
    {
        $res = $this->db->select_full('
            SELECT id,name,short_name,smallimg
            FROM ' .  self::TBL_LANGUAGE
            , null, Database::RETURN_DATA_ASSOC);
        return is_array($res) ? $res : false;
    }
    
    public function getParams()
    {
        $res = $this->db->select_full('
            SELECT id,name
            FROM ' .  self::TBL_PARAMS
            , null, Database::RETURN_DATA_ASSOC);
        return is_array($res) ? $res : false;
    }
    
    public function getUsers()
    {
        $res = $this->db->select_full('SELECT * FROM ' .  self::TBL_UTYPES, null, null, Database::ENCODE_HTML);
        $res = $this->usersData($res);

        return is_array($res) ? $res : false;
    }
    
    private function usersData($data)
    {
        foreach($data as $key => $val)
        {
            $data[$key]['access'] = $this->db->select_full('
                SELECT t1.id,t2.name,t3.*
                FROM ' . self::TBL_MENU . ' t1 
                JOIN ' . self::TBL_MENU_NAME . ' AS t2 ON (t2.id = t1.id)         
                LEFT JOIN ' . self::TBL_MENU_REL . ' AS t3 ON (t3.menu_id = t1.id)
                WHERE t1.type = "module" AND t1.enable = "y" AND t3.user_types_id = ' . $val['id'] . '
                AND t2.language = "' . models_helpers_Url::getConfigLanguage() . '"
            ', null, null, Database::ENCODE_HTML); 
        }
        
        return $data;
    }
    
    public function addLanguage($data)
    {
        $arr = array(
            'name'       => $data['name'],
            'short_name' => $data['short_name'],
            'smallimg'   => models_helpers_Url::getDomain().$data['image'],
            'status'     => 1,
            'date_add'   => time()
        );
        
        return $this->db->insert(self::TBL_LANGUAGE, $arr, true);
    }
    
    public function addParam($data)
    {
        $arr = array(
            'name'       => $data['name'],
            'status'     => 1,
            'date_add'   => time()
        );
        
        return $this->db->insert(self::TBL_PARAMS, $arr, true);
    }
    
    public function addGroup($data)
    {
        $arr = array(
            'id'         => $data['id'],
            'name'       => $data['name'],
            'ident'      => $data['ident'],
            'id_project' => 1
        );

        $this->db->insert(self::TBL_UTYPES, $arr, true);
    
        $res  = $this->db->select_full('SELECT id FROM ' .  self::TBL_MENU, null, Database::RETURN_DATA_ASSOC);
        
        foreach ($res as $s)
            $sql .= '(' . (int)$s['id']. ',' . (int)$arr['id'] . '),';
        
        return $this->db->query('INSERT INTO ' . self::TBL_MENU_REL . ' (menu_id,user_types_id) VALUES ' . substr($sql, 0, -1));
    }
    
    public function deleteLanguage()
    { 
        $res = $this->db->select_full('
            SELECT smallimg
            FROM ' .  self::TBL_LANGUAGE . ' WHERE id=' . $this->id
            , null, Database::RETURN_DATA_ASSOC);
   
        $this->delete_img($_SERVER['DOCUMENT_ROOT'].models_helpers_Url::getDomain(),$res[0]['smallimg']);
        
        return $this->db->delete(self::TBL_LANGUAGE, 'WHERE id=' . $this->id);
    }

    public function deleteParam()
    {
        return $this->db->delete(self::TBL_PARAMS, 'WHERE id=' . $this->id);
    }

    public function deleteGroup()
    {         
        return $this->db->delete(self::TBL_UTYPES, 'WHERE id=' . $this->id);
    }
    // обновление доступо по компонентам
    public function updateGroup($data)
    {   
        // компоненты (меню))
        $menu = $this->db->select_full('
            SELECT id,id_parent 
            FROM ' .  self::TBL_MENU . ' 
            WHERE type = "module" 
            AND enable = "y"', 
            null, Database::RETURN_DATA_ASSOC);
        $i=1;
        // прогон циклом по уаждому компоненту
        foreach($menu as $key=>$val)
        {
            // формирование массива для обновления 
            $arr = array(
                'add'           => $data["add$i"] == 'false' ? 0 : 1,
                'edit'          => $data["edit$i"] == 'false' ? 0 : 1,
                'edit_your'     => $data["edit_your$i"] == 'false' ? 0 : 1,
                'change_status' => $data["change_status$i"] == 'false' ? 0 : 1
            );
            // если хот бы один из видов доступа активен - элемент становиться доступен
            $arr['access'] = $arr['add'] == 0 && $arr['edit'] == 0 && $arr['edit_your'] == 0 && $arr['change_status'] == 0 ? 0 : 1;
            // обновление компонентов
            $this->db->update(self::TBL_MENU_REL, $arr, 'WHERE menu_id=' . $val['id'] . ' AND user_types_id='. $this->id); 
            // выборка всех родительских элементов(пунктов меню)
            $parent = $this->db->select_full('
                SELECT t1.access 
                FROM ' .  self::TBL_MENU_REL . ' AS t1
                LEFT JOIN ' .  self::TBL_MENU . ' AS t2 ON (t2.id = t1.menu_id)
                WHERE t2.id_parent=' . $val['id_parent'] .' 
                AND t1.user_types_id='. $this->id, 
                null, Database::RETURN_DATA_ASSOC);
            // если активен хотя бы один дочерний элемент - родительский становиться доступен
            $res['access'] = array_search(1, $parent[0]) == false ? 0 : 1;
            // обновление доступа к родительскому элементу
            $this->db->update(self::TBL_MENU_REL, $res, 'WHERE menu_id=' . $val['id_parent'] . ' AND user_types_id='. $this->id);
            $i++;
        }
    }
}  
?>