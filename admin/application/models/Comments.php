<?php
defined('_ACCESS') or die;
    
class models_Comments extends models_BaseModel
{   
    public function addItem(stdClass $data)
    {
        $parent = array(
            'child'  => 1,
            'answer' => 1
        );            
                
        $this->db->update(self::TBL_COMMENTS, $parent, 'WHERE id=' . $data->parent_id);   

        $arr = array(
            'parent_id' => $data->parent_id,
            'chain'     => $data->chain,
            'name'      => $data->name,
            'comment'   => $data->comment,
            'page_id'   => $data->page_id,
            'status'    => 1,
            'date_add'  => time()
        );
                
        return $this->db->insert(self::TBL_COMMENTS, $arr, true);
    }
    
    public function updateItem(stdClass $data)
    {        
        $arr = array(
            'parent_id' => $data->parent_id,
            'name'      => $data->name,
            'email'     => $data->email,
            'comment'   => $data->comment,
            'page_id'   => $data->page_id,
            'status'    => 1,
            'date_upd'  => time()
        );

        return $this->db->update(self::TBL_COMMENTS, $arr, 'WHERE id=' . $this->id);
    }
    
    public function getOne()
    {
        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_COMMENTS . '
            WHERE id = ' . $this->id . '
        ', null, Database::RETURN_DATA_ASSOC);
        
        $res[0]['tree'] = $this->answerTree($res[0]['parent_id'],$res[0]);
        ksort($res[0]['tree']);

        if(models_helpers_Access::checkAccess('edit_your') || models_helpers_Access::checkAccess('edit') == 0)
            $res = $res[0]['name'] != $this->uinfo['id'] && models_helpers_Access::checkAccess('edit') == 0 ? exit : $res;
        
        return is_array($res) ? $res[0] : false;
    }
      
    public function getCollection($filter,$user = '')
    {      
        $where = $this->prepareSql($filter);
        $filter = $filter->getData();
        
        $limit = $filter['onpage'];
        $offset = ($filter['page']-1) * $limit;

        $sql = '
            SELECT t1.id,SUBSTRING_INDEX(t1.comment," ",12) AS comment,t1.name,t1.status,t1.page_id,t1.status,t1.date_add,t1.parent_id,t1.email,t1.answer,t2.url
            FROM ' .  self::TBL_COMMENTS . ' AS t1
            LEFT JOIN ' . self::TBL_CONTENT . ' AS t2 ON (t2.id = t1.page_id)
            WHERE t1.name NOT IN (' . $this->getAdmins() . ') ' . $where . '
            ORDER BY ' . $filter['sortKey'] . ' ' . $filter['sortOrder'] . '
            LIMIT ' . $offset . ',' . $limit . '
          ';
        $res = $this->db->select_full($sql, null, null, Database::ENCODE_HTML);
        
        $res = $this->getAnswers($res,$where);

        return $res;
    }
    
    public function getAnswers($arr,$where)
    {
        if($arr)
            foreach($arr as $key => $val)
                if($val['answer'] == 1)
                    $arr[$key]['answers'] = $this->db->select_full('
                        SELECT t1.id,SUBSTRING_INDEX(t1.comment," ",12) AS comment,t1.name,t1.status,t1.page_id,t1.status,t1.date_add,t1.parent_id,t1.email,t2.url
                        FROM ' .  self::TBL_COMMENTS . ' AS t1
                        LEFT JOIN ' . self::TBL_CONTENT . ' AS t2 ON (t2.id = t1.page_id)
                        WHERE t1.parent_id = ' . $val['id'] .' ' . $where . '
                        ORDER BY t1.date_add
                    ', null, null, Database::ENCODE_HTML); 
                    

            return $arr;
    }
    
    public function getCollectionPagesCount($filter,$user = ''){
    
        $where = $this->prepareSql($filter);
        $filter = $filter->getData();
        
        $res = $this->db->select_full('
            SELECT COUNT("t1.id") AS cnt
            FROM ' .  self::TBL_COMMENTS . ' AS t1
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
            $sql .= ' AND t1.name = ' . $this->uinfo['id'];
        
        if (!empty($filters['status']))
            $sql .= ' AND t1.status = ' . $filters['status'];
        
        if (!empty($filters['fulltext']))
            $sql .= " AND t1.comment LIKE '%$filters[fulltext]%'";
            
        return $sql;
    }
    
    private function getAdmins()
    {
        $res   = $this->db->select(self::TBL_USERS, 'name','','','RETURN_DATA_ARR');
        $alias = $this->db->select(self::TBL_OPTIONS, 'admin_alias','','','RETURN_DATA_ARR');
        
        foreach ($res as $k=>$v)
            $names .= strripos($v["name"],' ')==true ? ',"'.substr($v['name'],0,strpos($v['name'],' ')).' '. $alias[0]['admin_alias'].'"':',"'.$v['name'].' '. $alias[0]['admin_alias'].'"';  
        
        return substr($names,1);        
    }                
    
    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_COMMENTS, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;

        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_COMMENTS,  $status, 'WHERE id=' . $id);
        }
        
        return false;    
    }

    
    public function deleteMulti($ids)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);  
            $res = $this->db->select(self::TBL_COMMENTS, 'parent_id',"WHERE parent_id !=0 AND id IN (" . implode(',', $ids) . ")",'','RETURN_DATA_ARR');
            
            $answerIds = '';
            foreach($res as $val)
                $answerIds .= $val['parent_id'].',';
                
            $this->db->update(self::TBL_COMMENTS,  0, 'WHERE id IN (' . substr($answerIds,0,-1) . ')');
            
            return $this->db->delete(self::TBL_COMMENTS, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }
    
    public function multiStatus($ids,$val)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            $status['status'] = $val;
            return $this->db->update(self::TBL_COMMENTS,  $status, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    } 
    
    // метод для формирования древовидной структуры комментария
    public function answerTree($parent,$array,$comments='')
    {   
        $comments[$parent == null ? 0 : $parent] = $array;
        
        if($parent != 0)
        {
            $res = $this->db->select(self::TBL_COMMENTS,'comment,parent_id,name,email,date_add',"WHERE id=$parent",'','RETURN_DATA_ARR');
            $comments = self::answerTree($res[0]['parent_id'],$res[0],$comments);   
        }

        return $comments;  
    } 
    
    public function getAdminPrefix()
    {
        $res = Database::instance()->select_full('SELECT admin_alias FROM '. models_BaseModel::TBL_OPTIONS.' WHERE id = 1');    
        return $res[0]->admin_alias;
    }
}   
?>