<?php
defined('_ACCESS') or die;
    
class models_Order extends models_BaseModel
{              
    public function getCollection($filter)
    {
        // creating conditions
        $where = $this->prepareSql($filter);
        $whereItems = $this->prepareItemsSql($filter);
        $filter = $filter->getData();
        
        $limit = $filter['onpage'];
        $offset = ($filter['page']-1) * $limit;
        
        // get orders 
        $res = $this->db->select_full('
            SELECT t1.*, DATE_FORMAT(t1.date_add,"%d-%m-%y %H:%i") AS date_add, t2.id AS t2id, t2.first_name, t2.last_name,
            t3.id AS t3id, t3.phone, t4.id AS t4id, t4.email, t5.id AS t5id, t5.address
            FROM ' .  self::TBL_ORDERS . ' AS t1 
            LEFT JOIN ' . self::TBL_CLIENTS_INFO . ' AS t2 ON t1.id_client = t2.id 
            LEFT JOIN ' . self::TBL_CLIENTS_PHONES . ' AS t3 ON (t3.id_client = t2.id) AND t1.id_tel = t3.id
            LEFT JOIN ' . self::TBL_CLIENTS_EMAILS . ' AS t4 ON (t4.id_client = t2.id) AND t4.is_main = 1
            LEFT JOIN ' . self::TBL_CLIENTS_ADDRESS . ' AS t5 ON (t5.id_client = t2.id) AND t1.id_addr = t5.id
            WHERE 1 ' . $where . '
            ORDER BY ' . $filter['sortKey'] . ' ' . $filter['sortOrder'] . '
            LIMIT ' . $offset . ',' . $limit, 
            null, null, Database::ENCODE_HTML);




        
        // get order items
        if($res)
            foreach ($res as $k => $v)
                $res[$k]['orderItems'] = $this->db->select_full('
                    SELECT t1.*, t2.name AS productname
                    FROM ' .  self::TBL_ORDER_ITEMS . ' AS t1
                    LEFT JOIN ' . self::TBL_PRODUCTS . " AS t2 ON (t2.id = t1.product_id) 
                    WHERE order_id='$v[id]'" . $whereItems, 
                    null, null, Database::ENCODE_HTML);

        return $res;
    }
    
    public function getCollectionPagesCount($filter)
    {
        $where = $this->prepareSql($filter);
        $filter = $filter->getData();
        
        $res = $this->db->select_full('
            SELECT COUNT(id) as cnt
            FROM ' .  self::TBL_ORDERS  . ' WHERE 1 ' . $where,
            null, null, Database::ENCODE_HTML);

        $res['cnt'] = is_array($res) && $res[0]['cnt'] > 0 ? ceil($res[0]['cnt']/$filter['onpage']) : 1;
        
        return $res;
    }
    
    private function prepareSql($filter)
    {
        $sql = '';

        $filters = $filter->getData();

        // date filter
        $date_filter = $filter->getDateFilter();
        if (is_array($date_filter) && count($date_filter) == 2)
            $sql .= ' AND date_add >= "' . $date_filter[0] . ' 00:00:00" AND date_add <= "' . $date_filter[1] . ' 23:59:59"';


        if (!empty($filters['status']))
            $sql .= ' AND status = ' . $filters['status'];

        if (!empty($filters['fulltext']))
            $sql .= " AND CONCAT(customer, phone, address, email, comment) LIKE  '%$filters[fulltext]%'";
            
        return $sql;
    }
    
    private function prepareItemsSql($filter)
    {
        $sql = '';
        $filters = $filter->getData();
        return $sql;
    }

    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_PRODUCTS, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;

        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_PRODUCTS, $status, 'WHERE id=' . $id);
        }

        return false;
    }

    public function updateValue($db)
    {
        return $this->db->update($db[0], $db[1], 'WHERE id=' . $this->id);
    }
}    
