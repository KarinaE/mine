<?php
defined('_ACCESS') or die;
    
class models_Templates extends models_BaseModel
{    
    
    public function addItem(stdClass $data)
    {    
        
        $arr = array(
            'author'   => $data->author,
            'name'     => $data->name,
            'category' => $data->category,
            'scripts'  => $data->scripts,
            'code'     => $data->code,
            'status'   => 1,
            'date_add' => time(),
            'date_upd' => time()
        );
        
        return $this->db->insert(self::TBL_TEMPLATES, $arr, true);
    }
    
    public function updateItem(stdClass $data)
    {
        $arr = array(
            'author'   => $data->author,
            'name'     => $data->name,
            'category' => $data->category,
            'scripts'  => $data->scripts,
            'code'     => $data->code,
            'date_upd'   => time()
        );

        return $this->db->update(self::TBL_TEMPLATES, $arr, 'WHERE id=' . $this->id);
    }
    
    public function getOne()
    {
    
        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_TEMPLATES . '
            WHERE id = ' . $this->id . '
        ', null, Database::RETURN_DATA_ASSOC);

        if(models_helpers_Access::checkAccess('edit_your') || models_helpers_Access::checkAccess('edit') == 0)
            $res = $res[0]['author'] != $this->uinfo['id'] && models_helpers_Access::checkAccess('edit') == 0 ? exit : $res;
        
        return is_array($res) ? $res[0] : false;
    }
      
    public function getCollection($filter,$user = '')
    {      
        $where = $this->prepareSql($filter);
        $filter = $filter->getData();
        
        $limit = $filter['onpage'];
        $offset = ($filter['page']-1) * $limit;

        $sql = '
            SELECT t1.id,t1.name,t1.pos_array,t1.status,from_unixtime(t1.date_add, "%d.%c.%y %h:%i") as date,t2.name AS author
            FROM ' .  self::TBL_TEMPLATES . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            WHERE 1 ' . $where . '
            ORDER BY ' . $filter['sortKey'] . ' ' . $filter['sortOrder'] . '
            LIMIT ' . $offset . ',' . $limit . '
          ';
          
        $res = $this->db->select_full($sql, null, null, Database::ENCODE_HTML);

        return $res;
    }
    
    public function getCollectionPagesCount($filter,$user = ''){
    
        $where = $this->prepareSql($filter);
        $filter = $filter->getData();
        
        $res = $this->db->select_full('
            SELECT COUNT("t1.id") AS cnt
            FROM ' .  self::TBL_TEMPLATES . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            WHERE 1 ' . $where . '
        ', null, Database::RETURN_DATA_ASSOC);

        $res['cnt'] = is_array($res) && $res[0]['cnt'] > 0 ? ceil($res[0]['cnt']/$filter['onpage']) : 1;
        
        return $res;
    }
    
    private function prepareSql($filters)
    {
        $sql = '';
        
        $filters = $filters->getData();
        
        if(models_helpers_Access::checkAccess('edit_your'))
            $sql .= ' AND t1.author = ' . $this->uinfo['id'];
        
        if (!empty($filters['status']))
            $sql .= ' AND t1.status = ' . $filters['status'];
        
        if (!empty($filters['fulltext']))
            $sql .= " AND UPPER(t1.name) LIKE UPPER('%$filters[fulltext]%')";
            
        return $sql;
    }
    
    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_TEMPLATES, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;
        
        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_TEMPLATES,  $status, 'WHERE id=' . $id);
        }
        
        return false;    
    }

    
    public function deleteMulti($ids)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);  
            
            return $this->db->delete(self::TBL_TEMPLATES, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }
    
    public function multiStatus($ids,$val)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            $status['status'] = $val;
            return $this->db->update(self::TBL_TEMPLATES,  $status, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    } 
    
    public function getModules($id)
    {
        $res = $this->db->select_full('
            SELECT pos_array
            FROM ' .  self::TBL_TEMPLATES . '
            WHERE id = ' . $id
            , null, Database::RETURN_DATA_ASSOC);
        return is_array($res) ? $res[0] : false;
    }
    
    public static function getModuleName($id)
    {
        $res = Database::instance()->select(models_BaseModel::TBL_MODULES,'name', 'WHERE id = ' . $id,null, null, Database::ENCODE_HTML);
        return is_array($res) ? $res[0]['name'] : false;    
    } 
    
    public function addModules($id,$array)
    {
        $arr = array(
            'pos_array' => serialize($array)
        );

        return $this->db->update(self::TBL_TEMPLATES, $arr, 'WHERE id=' . $id);  
    }  
}
    
?>