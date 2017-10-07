<?php
defined('_ACCESS') or die;

class models_Suppliers extends models_BaseModel
{

    public function getOne()
    {
        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_SUPPLIERS . '
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
            SELECT t1.id, t1.name, t1.contact_person,t1.phone,t1.email,
            from_unixtime(t1.date_add, "%d.%c.%y %h:%i") as date_add, t2.name AS author 
            FROM ' .  self::TBL_SUPPLIERS . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON t2.id = t1.author
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
            FROM ' .  self::TBL_SUPPLIERS . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON t2.id = t1.author
            WHERE 1 ' . $where , null, Database::RETURN_DATA_ASSOC);

        $res['cnt'] = is_array($res) && $res[0]['cnt'] > 0 ? ceil($res[0]['cnt']/$filter['onpage']) : 1;

        return $res;
    }

    private function prepareSql($filters)
    {
        $sql = ' ';

        $filters = $filters->getData();

        if(models_helpers_Access::checkAccess('edit_your'))
            $sql .= ' AND t1.author = ' . $this->uinfo['id'];

        if (!empty($filters['status']))
            $sql .= ' AND t1.status = ' . $filters['status'];

        if (!empty($filters['fulltext']))
            $sql .= " AND CONCAT(t1.name, t1.contact_person,t1.phone,t1.email) LIKE ('%$filters[fulltext]%')";

        return $sql;
    }

    public function getSizesOptions($id)
    {
        $res = $this->db->select_full('
            SELECT t1.size_type_id,t1.id,t2.size,t2.id AS size_id,t3.size_option,t3.id AS option_id
            FROM ' .  self::TBL_SUPP_SIZES . ' as t1
            LEFT JOIN ' . self::TBL_CLOTH_SIZES . ' as t2 ON t2.type_id = t1.size_type_id
            LEFT JOIN ' . self::TBL_CLOTH_OPTIONS . ' as t3 ON t3.type_id = t1.size_type_id
            WHERE t1.supplier_id = ' . $id . ' ORDER BY t2.ordr, t3.ordr'
            , null, Database::RETURN_DATA_ASSOC);

        $arr = array();
        // group sizes array by size_type_id
        if($res)
            foreach ($res as $k => $v)
                if($arr[$v['size_type_id']])
                    $arr[$v['size_type_id']][] = $v;
                else
                    $arr[$v['size_type_id']][] = $v;

        return $arr;
    }

    public function getValues($id)
    {
        $res = $this->db->select_full('
            SELECT *
            FROM ' . self::TBL_SUPP_VALUES . '
            WHERE supplier_size_id = ' . $id
            , null, Database::RETURN_DATA_ASSOC);

        return is_array($res) ? $res : false;
    }

    public function getSizeNames($id)
    {
        $res = $this->db->select_full('
            SELECT t2.id, t2.name
            FROM ' .  self::TBL_SUPP_SIZES . ' as t1
            LEFT JOIN ' . self::TBL_CLOTH_TYPES. ' as t2 ON t2.id = t1.size_type_id
            WHERE t1.supplier_id = ' . $id . ' AND t2.status = 1'
            , null, Database::RETURN_DATA_ASSOC);

        // making associative array for headers
        $arr = array();
        if($res)
            foreach ($res as $k => $v)
                $arr[$v['id']] = $v['name'];

        return $arr;
    }

    public function checkValue($arr)
    {
        $res = $this->db->select_full('
            SELECT id
            FROM ' .  self::TBL_SUPP_VALUES . '
            WHERE supplier_size_id = "' . $arr['supplier_size_id'] . '" 
            AND size_id = "' . $arr['size_id'] . '" 
            AND size_option_id = "' . $arr['size_option_id']. '"'
            , null, Database::RETURN_DATA_ASSOC);

        return is_array($res) ? $res[0]['id'] : false;
    }

    public function addItem(stdClass $data)
    {
        $arr = array(
            'name'           => $data->name,
            'contact_person' => $data->contact_person,
            'phone'          => $data->phone,
            'email'          => $data->email,
            'image'          => $data->image,
            'status'         => 1,
            'date_add'       => time(),
            'author'     => $data->author,
        );
        return $this->db->insert(self::TBL_SUPPLIERS, $arr, true);
    }

    public function addSize($data)
    {

        // check if value already added
        $res = $this->db->select_full('
            SELECT id FROM ' .  self::TBL_SUPP_SIZES . ' 
            WHERE supplier_id = "' . $data['supplier_id'] . '"
            AND size_type_id = "' . $data['size_type'] .'"'
            , null, Database::RETURN_DATA_ASSOC);

        // if size added - exit
        if($res)
            exit($this->message['supplier_check_add_size_error']);

        $arr = array(
            'supplier_id'  => $data['supplier_id'],
            'size_type_id' => $data['size_type']
        );

        return $this->db->insert(self::TBL_SUPP_SIZES, $arr, true);
    }

    public function addValue($data)
    {
        return $this->db->insert(self::TBL_SUPP_VALUES, $data, true);
    }

    public function updateItem(stdClass $data)
    {
        $arr = array(
            'name'           => $data->name,
            'contact_person' => $data->contact_person,
            'phone'          => $data->phone,
            'email'          => $data->email,
            'image'          => $data->image,
        );
        return $this->db->update(self::TBL_SUPPLIERS, $arr, 'WHERE id=' . $this->id);
    }

    public function updateValue($data,$id)
    {
        return $this->db->update(self::TBL_SUPP_VALUES, $data, 'WHERE id=' . $id);
    }

    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_SUPPLIERS, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;

        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_SUPPLIERS,  $status, 'WHERE id=' . $id);
        }

        return false;
    }

    public function deleteMulti($ids)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);

            // delete related
            $this->db->delete(self::TBL_SUPP_SIZES, 'WHERE supplier_id IN (' . implode(',', $ids) . ')');

            // delete item
            return $this->db->delete(self::TBL_SUPPLIERS, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }

    public function deleteSize($id)
    {
        return $this->db->delete(self::TBL_SUPP_SIZES, 'WHERE id=' . $id);
    }
}