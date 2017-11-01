<?php
defined('_ACCESS') or die;

class models_Clients extends models_BaseModel
{

    public function getOne()
    {
        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_CLIENTS_INFO . '
            WHERE id = ' . $this->id
        , null, Database::RETURN_DATA_ASSOC);

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
            SELECT t1.id, t1.first_name, t1.last_name, t1.bonuses, t1.birth_date, t1.gender, t1. avatar, t1.comments, 
            from_unixtime(t1.date_add, "%d.%c.%y %h:%i") as date_add, 
            t2.phone, t3.email , t4.address, t5.soc_id
            FROM ' .  self::TBL_CLIENTS_INFO . ' AS t1
            LEFT JOIN ' . self::TBL_CLIENTS_PHONES . ' AS t2 ON (t2.id_client = t1.id) AND t2.is_main = 1
            LEFT JOIN ' . self::TBL_CLIENTS_EMAILS . ' AS t3 ON (t3.id_client = t1.id) AND t3.is_main = 1
            LEFT JOIN ' . self::TBL_CLIENTS_ADDRESS . ' AS t4 ON (t4.id_client = t1.id) AND t4.is_main = 1
            LEFT JOIN ' . self::TBL_CLIENTS_SOC_ACC . ' AS t5 ON (t5.id_client = t1.id)
            WHERE 1 ' . $where . '
            GROUP BY id
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
             FROM ' .  self::TBL_CLIENTS_INFO . ' AS t1
            LEFT JOIN ' . self::TBL_CLIENTS_PHONES . ' AS t2 ON (t2.id_client = t1.id)
            LEFT JOIN ' . self::TBL_CLIENTS_EMAILS . ' AS t3 ON (t3.id_client = t1.id)
            LEFT JOIN ' . self::TBL_CLIENTS_ADDRESS . ' AS t4 ON (t4.id_client = t1.id)
            WHERE 1 ' . $where , null, Database::RETURN_DATA_ASSOC);

        $res['cnt'] = is_array($res) && $res[0]['cnt'] > 0 ? ceil($res[0]['cnt']/$filter['onpage']) : 1;

        return $res;
    }

    public function getOrders()
    {
        // get orders
        $res = $this->db->select_full('
            SELECT t1.*, DATE_FORMAT(t1.date_add,"%d-%m-%y %H:%i") AS date_add, t2.first_name, t2.last_name, t3. address, t4.phone
            FROM ' .  self::TBL_ORDERS  . ' as t1
            LEFT JOIN ' . self::TBL_CLIENTS_INFO . ' AS t2 ON (t1.id_client = t2.id)
            LEFT JOIN ' . self::TBL_CLIENTS_ADDRESS . ' AS t3 ON (t1.id_client = t3.id_client)
            LEFT JOIN ' . self::TBL_CLIENTS_PHONES . ' AS t4 ON (t1.id_client = t4.id_client)
            WHERE t1.id_client = ' . $this->id
            ,null, null, Database::ENCODE_HTML);

        // get order items
        if($res)
            foreach ($res as $k => $v)
                $res[$k]['orderItems'] = $this->db->select_full('
                    SELECT t1.*, t2.fashion_name AS productname
                    FROM ' .  self::TBL_ORDER_ITEMS . ' AS t1
                    LEFT JOIN ' . self::TBL_PARAMETERS . " AS t2 ON (t2.id = t1.product_id) 
                    WHERE order_id='$v[id]'"
                    ,null, null, Database::ENCODE_HTML);

        return $res;
    }

    public function getPhones($id)
    {
        $res = $this->db->select_full('
            SELECT id, first_name, phone, is_main
            FROM ' .  self::TBL_CLIENTS_PHONES . '
            WHERE id_client = ' . $id
            , null, Database::RETURN_DATA_ASSOC);
        return $res;
    }
    public function getEmails($id)
    {
        $res = $this->db->select_full('
            SELECT id, first_name, email, is_main
            FROM ' .  self::TBL_CLIENTS_EMAILS . '
            WHERE id_client = ' . $id
            , null, Database::RETURN_DATA_ASSOC);
        return $res;
    }
    public function getAddress($id)
    {
        $res = $this->db->select_full('
            SELECT id, address
            FROM ' .  self::TBL_CLIENTS_ADDRESS . '
            WHERE id_client = ' . $id
            , null, Database::RETURN_DATA_ASSOC);
        return $res;
    }
    public function getSocial($id)
    {
        $res = $this->db->select_full('
            SELECT id, soc_id
            FROM ' .  self::TBL_CLIENTS_SOC_ACC . '
            WHERE id_client = ' . $id
            , null, Database::RETURN_DATA_ASSOC);
        return $res;
    }

    public function addItem(stdClass $data)
    {
        $arr = array(
            'first_name' => $data->first_name,
            'last_name'  => $data->last_name,
            'gender'     => $data->gender,
            'birth_date' => $data->birth_date,
            'comments'   => $data->comments,
            'avatar'     => $data->avatar,
            'bonuces'    => $data->bonuces,
            'date_add'   => time()
        );
        return $this->db->insert(self::TBL_CLIENTS_INFO, $arr, true);
    }

    public function addPhone($data)
    {

        $arr = array(
            'id_client' => $data['id_client'],
            'first_name'=> $data['first_name'],
            'phone'     => $data['phone'],
            'is_main'   => $data['is_main']
        );
        return $this->db->insert(self::TBL_CLIENTS_PHONES, $arr, true);
    }

    public function addEmail($data){
        $arr = array(
            'id_client' => $data['id_client'],
            'first_name'=> $data['first_name'],
            'email'     => $data['email'],
            'is_main'   => $data['is_main']
        );
        return $this->db->insert(self::TBL_CLIENTS_EMAILS, $arr, true);
    }

    public function addAddress($data){
        $arr = array(
            'id_client' => $data['id_client'],
            'address'   => $data['address']
        );
        return $this->db->insert(self::TBL_CLIENTS_ADDRESS, $arr, true);
    }

    public function addSocial($data){
        $arr = array(
            'id_client' => $data['id_client'],
            'soc_id'   => $data['soc_acc']
        );
        return $this->db->insert(self::TBL_CLIENTS_SOC_ACC, $arr, true);
    }

    public function updateItem(stdClass $data)
    {
        $arr = array(
            'first_name' => $data->first_name,
            'last_name'  => $data->last_name,
            'gender'     => $data->gender,
            'birth_date' => $data->birth_date,
            'comments'   => $data->comments,
            'avatar'     => $data->avatar,
            'bonuces'    => $data->bonuces
        );
        return $this->db->update(self::TBL_CLIENTS_INFO, $arr, 'WHERE id=' . $this->id);
    }

    public function deleteMulti($ids)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            return $this->db->delete(self::TBL_CLIENTS_INFO, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }

    public function deletePhone($id)
    {
        return $this->db->delete(self::TBL_CLIENTS_PHONES, 'WHERE id=' . $id);
    }

    public function deleteEmail($id)
    {
        return $this->db->delete(self::TBL_CLIENTS_EMAILS, 'WHERE id=' . $id);
    }

    public function deleteAddress($id)
    {
        return $this->db->delete(self::TBL_CLIENTS_ADDRESS, 'WHERE id=' . $id);
    }

    public function deleteSocial($id)
    {
        return $this->db->delete(self::TBL_CLIENTS_SOC_ACC, 'WHERE id=' . $id);
    }

    public function checkPhone($id)
    {
        return $this->db->select_full('
            SELECT id
            FROM ' .  self::TBL_CLIENTS_PHONES . '
            WHERE id_client = ' . $id .' AND is_main=1 '
            , null, Database::RETURN_DATA_ASSOC);
    }

    public function checkEmail($id)
    {
        return $this->db->select_full('
            SELECT id
            FROM ' .  self::TBL_CLIENTS_EMAILS . '
            WHERE id_client = ' . $id .' AND is_main=1 '
            , null, Database::RETURN_DATA_ASSOC);
    }

    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_CLIENTS_INFO, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;

        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_CLIENTS_INFO,  $status, 'WHERE id=' . $id);
        }

        return false;
    }

    private function prepareSql($filters)
    {
        $sql = ' ';


        $filters = $filters->getData();

        if(models_helpers_Access::checkAccess('edit_your'))
            $sql .= ' AND t1.author = ' . $this->uinfo['id'];

        if (!empty($filters['status']))
            $sql .= ' AND t1.status = ' . $filters['status'];

        if (!empty($filters['category']))
        {
            $res = $this->db->select_full('SELECT id,parent_id FROM ' . self::TBL_CATEGORIES, null, Database::RETURN_DATA_ASSOC);
            $sql .= ' AND t1.category IN (' . $filters['category'] . $this->childIds($res,$filters['category']) . ')';
        }

        if (!empty($filters['fulltext']))
            $sql .= " AND CONCAT(t1.first_name, t1.last_name,t2.phone,t3.email,t4.address) LIKE ('%$filters[fulltext]%')";

        return $sql;
    }
}