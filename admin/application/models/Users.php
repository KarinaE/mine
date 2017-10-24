<?php
defined('_ACCESS') or die;
    
class models_Users extends models_BaseModel
{                
    public function userExistsByEmail($email)
    {
        $res = $this->db->select(self::TBL_USERS, 'id', 'WHERE login = "' . trim($email) . '"');
        return is_array($res) ? true : false;
    }
    
    public function restorePassw($email)
    {
        if ($email)
        {
            $new_passw = substr(md5(rand(0,99999)),0,10);
            $hash = models_Authorization::generateHash($new_passw, $salt);
            
            require_once 'lib/phpmailer/class.phpmailer.php';
            $mail = new PHPMailer();
            
            $mail->AddAddress($email);
            $mail->SetFrom('', $this->message['users_passw_restore_header'], 1);
            $mail->Subject = $this->message['users_passw_restore_subject'];
            $mail->MsgHTML('
            <div>'.$this->message['users_passw_restore_text1'].' <strong>' . $email . '</strong></div>
            <div>'.$this->message['users_passw_restore_text2'].' <strong>' . $new_passw . '</strong></div>
            ');
            
            if ($mail->Send() && $this->changePasswByLogin($email, $hash, $salt))
            {
                return true;
            }
        }
        
        return false;
    }
    
    /**
    * Get first user project
    * @return array
    */
    public function getUserProject()
    {
        $prjs = $this->getUserProjects();
        return !empty($prjs[0]) ? $prjs[0] : null;
    }
    
    public function getUserProjects()
    {
        $res = $this->db->select_full('
        SELECT  t.id user_type_id, t.name user_type, t.ident user_type_ident
        FROM '.self::TBL_USERS.' u
        JOIN '.self::TBL_UTYPES_REL.' r ON (r.user_id = u.id)
        JOIN '.self::TBL_UTYPES.' t ON (t.id = r.type_id)
        WHERE u.id = ' . $this->id . '
        ');
        
        return !empty($res[0]) ? $res : null;
    }
    
    public function getUserTypes()
    {
        return $this->db->select_full('
        SELECT u.id user_id, t.*
        FROM '.self::TBL_USERS.' u
        JOIN '.self::TBL_UTYPES_REL.' r ON (r.user_id = u.id)
        JOIN '.self::TBL_UTYPES.' t ON (t.id = r.type_id)
        WHERE u.id = ' . $this->id . '
        ');
    }
    
    private function changePasswByLogin($email, $passw, $salt)
    {
        return $this->db->update(self::TBL_USERS, array('passw' => $passw, 'salt' => $salt), 'WHERE login = "' . $email . '"');
    }
    
    public function addItem(stdClass $data)
    {    
        $hash = models_Authorization::generateHash($data->passw, $data->salt);
        $arr = array(
            'name'         => $data->name,
            'reg_date'     => $data->reg_date,
            'login'        => $data->login,
            'passw'        => $hash,
            'salt'         => $data->salt,
            'status'       => 1
        );
        
        $uid = $this->db->insert(self::TBL_USERS, $arr, true);
        return $this->db->query("INSERT INTO " . self::TBL_UTYPES_REL . " (user_id, type_id) VALUES ($uid, $data->utype)");
    }
    
    public function updateItem(stdClass $data)
    {
        $arr = array(
            'name'         => $data->name,
            'reg_date'     => $data->reg_date,
            'login'        => $data->login
        );
        
        if($data->passw)
        {
            $hash = models_Authorization::generateHash($data->passw,$data->salt);
            $arr['passw'] = $hash;
            $arr['salt']  = $data->salt;
        }
        
        $this->db->delete(self::TBL_UTYPES_REL, 'WHERE user_id = ' . $this->id);
        $this->db->query("INSERT INTO " . self::TBL_UTYPES_REL . " (user_id, type_id) VALUES ($this->id, $data->utype)");
        
        return $this->db->update(self::TBL_USERS, $arr, 'WHERE id=' . $this->id);
    }
    
    public function getOne()
    {
        $res = $this->db->select_full('
        SELECT t1.*, GROUP_CONCAT(t2.type_id) types
        FROM ' . self::TBL_USERS . ' AS t1
        JOIN ' . self::TBL_UTYPES_REL . ' AS t2 ON (t2.user_id = t1.id)
        WHERE  t1.id = ' . $this->id
        , null, Database::RETURN_DATA_ASSOC);
        
        return is_array($res) ? $res[0] : false;
    }
      
    public function getCollection($filter,$user = '')
    {  
        $where = $this->prepareSql($filter);
        $filter = $filter->getData();
        
        $limit = $filter['onpage'];
        $offset = ($filter['page']-1) * $limit;

        $sql = '
            SELECT t1.id,t1.name,t1.reg_date,t1.status,t3.name AS users_group
            FROM ' .  self::TBL_USERS . ' AS t1
            JOIN ' . self::TBL_UTYPES_REL . ' AS t2 ON (t2.user_id = t1.id)
            JOIN ' . self::TBL_UTYPES . ' AS t3 ON (t3.id = t2.type_id)
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
            FROM ' .  self::TBL_USERS . ' AS t1
            LEFT JOIN ' . self::TBL_UTYPES_REL . ' AS t2 ON (t2.user_id = t1.id)
            RIGHT JOIN ' . self::TBL_UTYPES . ' AS t3 ON (t3.id = t2.type_id)
            WHERE 1 ' . $where . '
        ', null, Database::RETURN_DATA_ASSOC);

        $res['cnt'] = is_array($res) && $res[0]['cnt'] > 0 ? ceil($res[0]['cnt']/$filter['onpage']) : 1;
        
        return $res;
    }
    
    private function prepareSql($filters)
    {
        $sql = '';
        
        $filters = $filters->getData();
        
        if (!empty($filters['status']))
            $sql .= ' AND t1.status = ' . $filters['status'];
            
        if (!empty($filters['users_group']))
            $sql .= ' AND t3.id = "' . $filters['users_group'] . '"';
        
        if (!empty($filters['fulltext']))
            $sql .= " AND UPPER(t1.name) LIKE UPPER('%$filters[fulltext]%')";
            
        return $sql;
    }
    
    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_USERS, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;
        
        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_USERS,  $status, 'WHERE id=' . $id);
        }
        
        return false;    
    }
    
    public function deleteMulti($ids){
    
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            return $this->db->delete(self::TBL_USERS, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }
    
    public function multiStatus($ids,$val){
    
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            $status['status'] = $val;
            return $this->db->update(self::TBL_USERS,  $status, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }    
}
    
?>