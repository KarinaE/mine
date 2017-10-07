<?php
defined('_ACCESS') or die;

class models_Sizes extends models_BaseModel
{
    public function getOne()
    {

        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_CLOTH_TYPES . '
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
            SELECT t1.id, t1.name,t1.status,t1.image, from_unixtime(t1.date_add, "%d.%c.%y %h:%i") as date,from_unixtime(t1.date_upd, "%d.%c.%y %h:%i") as date_upd, t2.name AS author
            FROM ' .  self::TBL_CLOTH_TYPES . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            WHERE 1 ' . $where . '
            ORDER BY ' . $filter['sortKey'] . ' ' . $filter['sortOrder'] . '
            LIMIT ' . $offset . ',' . $limit . '
          ';

        $res = $this->db->select_full($sql, null, null, Database::ENCODE_HTML);
        $res = $this->getSizesList($res);

        return $res;
    }

    private function getSizesList($res)
    {
        foreach ($res as $k => $v){
            $res[$k]['s_types'] = $this->db->select_full(
                                    'SELECT size FROM '. self::TBL_CLOTH_SIZES .' WHERE type_id = '. $v['id'],
                                    null,
                                    null,
                                    Database::ENCODE_HTML);
        }

        return $res;
    }

    public function getCollectionPagesCount($filter,$user = ''){

        $where = $this->prepareSql($filter);
        $filter = $filter->getData();

        $res = $this->db->select_full('
            SELECT COUNT("t1.id") AS cnt
            FROM ' .  self::TBL_CLOTH_TYPES . ' AS t1
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

    public function getSizes($type_id)
    {
        $res = $this->db->select_full('
            SELECT * FROM ' .  self::TBL_CLOTH_SIZES . ' WHERE type_id = ' . $type_id .' ORDER BY ordr'
            , null, Database::RETURN_DATA_ASSOC);

        return is_array($res) ? $res : false;
    }

    public function getOptions($type_id)
    {
        $res = $this->db->select_full('
            SELECT * FROM '. self::TBL_CLOTH_OPTIONS .' WHERE type_id = ' . $type_id .' ORDER BY ordr'
            , null, Database::RETURN_DATA_ASSOC);

        return is_array($res) ? $res : false;
    }

    public function addItem(stdClass $data)
    {
        $arr = array(
            'name'     => $data->name,
            'image'    => $data->image,
            'author'   => $data->author,
            'status'    => 1,
            'date_add' => time(),
            'date_upd' => time()
        );
        return $this->db->insert(self::TBL_CLOTH_TYPES, $arr, true);
    }

    public function addSize($data)
    {
        $arr = array(
            'size'    => $data['size'],
            'type_id' => $data['type_id'],
            'ordr'    => $data['ordr']
        );
        return $this->db->insert(self::TBL_CLOTH_SIZES, $arr, true);
    }

    public function addOption($data)
    {
        $arr = array(
            'size_option' => $data['size_option'],
            'type_id'     => $data['type_id'],
            'ordr'        => $data['ordr']
        );
        return $this->db->insert(self::TBL_CLOTH_OPTIONS, $arr, true);
    }

    public function updateItem(stdClass $data)
    {
        $arr = array(
            'name'     => $data->name,
            'image'    => $data->image,
            'author'   => $data->author,
            'date_upd' => time()
        );

        return $this->db->update(self::TBL_CLOTH_TYPES, $arr, 'WHERE id=' . $this->id);
    }

    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_CLOTH_TYPES, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;

        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_CLOTH_TYPES,  $status, 'WHERE id=' . $id);
        }

        return false;
    }

    public function multiStatus($ids,$val)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            $status['status'] = $val;
            return $this->db->update(self::TBL_CLOTH_TYPES,  $status, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }

    public function deleteMulti($ids)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);

            // deleting related
            $this->db->delete(self::TBL_CLOTH_SIZES, 'WHERE type_id IN (' . implode(',', $ids) . ')');
            $this->db->delete(self::TBL_CLOTH_OPTIONS, 'WHERE type_id IN (' . implode(',', $ids) . ')');
            $this->db->delete(self::TBL_SUPP_SIZES, 'WHERE size_type_id IN (' . implode(',', $ids) . ')');

            // delete item
            return $this->db->delete(self::TBL_CLOTH_TYPES, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }

    public function deleteSize($id)
    {
        // delete related
        $this->db->delete(self::TBL_SUPP_VALUES, 'WHERE size_id='. $id);
        // delete item
        return $this->db->delete(self::TBL_CLOTH_SIZES, 'WHERE id='. $id);
    }

    public function deleteOption($id)
    {
        // delete related
        $this->db->delete(self::TBL_SUPP_VALUES, 'WHERE 	size_option_id='. $id);
        // delete item
        return $this->db->delete(self::TBL_CLOTH_OPTIONS, 'WHERE id='. $id);
    }
}