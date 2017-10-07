<?php
defined('_ACCESS') or die;
    
class models_Categories extends models_BaseModel
{     
    public function addItem(stdClass $data)
    {    
        
        $arr = array(
            'author'    => $data->author,
            'name'      => $data->name,
            'main'      => $data->main,
            'page'      => $data->page,
            'ordr'      => $data->ordr,
            'status'    => 1,
            'parent_id' => $data->parent_id,
            'category'  => $data->category,
            'level'     => $this->updateLevel($data->parent_id),
            'date_add'  => time(),
            'date_upd'  => time()
        );
        
        return $this->db->insert(self::TBL_CATEGORIES, $arr, true);
    }
    
    public function updateItem(stdClass $data)
    {
        $arr = array(
            'name'      => $data->name,
            'main'      => $data->main,
            'page'      => $data->page,
            'ordr'      => $data->ordr,
            'parent_id' => $data->parent_id,
            'category'  => $data->category,
            'level'     => $this->updateLevel($data->parent_id),
            'date_upd'  => time()
        );
        
        $this->checkChilds($arr['level'],$data->category,$this->id);
        
        return $this->db->update(self::TBL_CATEGORIES, $arr, 'WHERE id=' . $this->id);
    }
    
    private function updateLevel($parent)
    {
        if(!empty($parent))
            $res = $this->db->select_full('
                SELECT level 
                FROM ' .  self::TBL_CATEGORIES . ' 
                WHERE id = ' . $parent
            , null, Database::RETURN_DATA_ASSOC);    
        
        if(is_array($res))
        {
            $this->db->update(self::TBL_CATEGORIES, array('child'=> 1), 'WHERE id=' . $parent);
            
            return $res[0]['level']+1;   
        }
        else
            return false;   
    }
    
    private function checkChilds($level,$category,$id)
    {
        $res = $this->db->select_full('SELECT id,child FROM ' .  self::TBL_CATEGORIES . ' WHERE parent_id = ' . $id,null,Database::RETURN_DATA_ASSOC);
        
        if(is_array($res))
            foreach ($res as $k=>$v)  
            {
                $this->db->update(self::TBL_CATEGORIES,array('level' => $level+1,'category' => $category),'WHERE id=' . $v['id']);
                
                if($v['child'] == 1)
                    self::checkChilds($level+1,$category,$v['id']);  
            }  
            
    }
    
    public function getOne()
    {
    
        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_CATEGORIES . '
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
            SELECT t1.id,t1.name,t1.category,t1.level,t1.parent_id,t1.child,t1.status,t1.ordr,from_unixtime(t1.date_add, "%d.%c.%y %h:%i") as date, t2.name AS author
            FROM ' .  self::TBL_CATEGORIES . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            WHERE 1 ' . $where . '
            ORDER BY ' . $filter['sortKey'] . ' ' . $filter['sortOrder'] . '
            LIMIT ' . $offset . ',' . $limit . '
          ';

        $res = $this->db->select_full($sql, null, null, Database::ENCODE_HTML);
        if(empty($filter['fulltext']))
            $res = $this->sortAsTree($res,0);                
        
        return $res;
    }
    
    private function sortAsTree($data,$parent)
    {
        if($data)
            foreach($data as $k=>$v)
                if($v['parent_id'] == $parent)
                {
                    $GLOBALS["arr"][] = $v;
                    if($v['child'] == 1)
                        self::sortAsTree($data,$v['id']);  
                }
   
        return $GLOBALS["arr"];          
    }
    
    public function getCollectionPagesCount($filter,$user = ''){
    
        $where = $this->prepareSql($filter);
        $filter = $filter->getData();
        
        $res = $this->db->select_full('
          SELECT COUNT("t1.id") AS cnt
          FROM ' .  self::TBL_CATEGORIES . ' AS t1
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
            
        if (!empty($filters['category']))
            $sql .= ' AND t1.category = "' . $filters['category'] . '"';
        
        if (!empty($filters['fulltext']))
            $sql .= " AND CONCAT(UPPER(t1.name), t1.page, UPPER(t2.name)) LIKE  UPPER('%$filters[fulltext]%')";
   
        return $sql;
    }
    
    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_CATEGORIES, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;
        
        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_CATEGORIES,  $status, 'WHERE id=' . $id);
        }
        
        return false;    
    }
    
    public function deleteMulti($ids){
    
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            return $this->db->delete(self::TBL_CATEGORIES, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }
    
    public function multiStatus($ids,$val){
    
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            $status['status'] = $val;
            return $this->db->update(self::TBL_CATEGORIES,  $status, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }    
}
    
?>