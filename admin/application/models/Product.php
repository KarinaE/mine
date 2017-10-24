<?php
defined('_ACCESS') or die;

class models_Product extends models_BaseModel
{
    public function getOne()
    {

        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_BRANDS . '
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
            FROM ' .  self::TBL_BRANDS . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            WHERE 1 ' . $where . '
            ORDER BY ' . $filter['sortKey'] . ' ' . $filter['sortOrder'] . '
            LIMIT ' . $offset . ',' . $limit . '
          ';

        $res = $this->db->select_full($sql, null, null, Database::ENCODE_HTML);
        $res = $this->getFashionTypes($res);

        return $res;
    }
    public function getFashionTypes($res)
    {
        foreach ($res as $k=>$v){
            $res[$k]['f_types']=$this->db->select_full( 'SELECT fashion_name 
                                                        FROM '. self::TBL_BRAND_OPTIONS .' 
                                                        WHERE brand_id = '. $v['id'], null, null, Database::ENCODE_HTML);
        }

        return $res;

    }

    public function getCollectionPagesCount($filter,$user = ''){

        $where = $this->prepareSql($filter);
        $filter = $filter->getData();

        $res = $this->db->select_full('
            SELECT COUNT("t1.id") AS cnt
            FROM ' .  self::TBL_BRANDS . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            WHERE 1 ' . $where . '
        ', null, Database::RETURN_DATA_ASSOC);

        $res['cnt'] = is_array($res) && $res[0]['cnt'] > 0 ? ceil($res[0]['cnt']/$filter['onpage']) : 1;

        return $res;
    }

    public function getBrandProducts($brand_id)
    {
        $res = $this->db->select_full('
            SELECT t1.id, t1.fashion_name, t2.name as category, t1.price, t1.discount, t1.ordr as `order`, t1.image
            FROM ' .  self::TBL_BRAND_OPTIONS . ' AS t1
            LEFT JOIN '. self::TBL_CATEGORIES .' AS t2 on t1.category_id=t2.id
            WHERE brand_id = ' . $brand_id .' ORDER BY t1.ordr'
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
        return $this->db->insert(self::TBL_BRANDS, $arr, true);
    }

    public function addOption($data)
    {
        $arr = array(
            'fashion_name'  => $data['fashion_name'],
            'brand_id'   => $data['brand_id'],
            'category_id'   => $data['category_id'],
            'price'=> $data['price'],
            'discount'      => $data['discount'],
            'ordr'         => $data['order'],
            'image'         => $data['image']
        );
        return $this->db->insert(self::TBL_BRAND_OPTIONS, $arr, true);
    }

    public function updateItem(stdClass $data)
    {
        $arr = array(
            'name'     => $data->name,
            'image'    => $data->image,
            'author'   => $data->author,
            'date_upd' => time()
        );

        return $this->db->update(self::TBL_BRANDS, $arr, 'WHERE id=' . $this->id);
    }

    public function deleteMulti($ids)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);

            return $this->db->delete(self::TBL_BRANDS, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }

    public function deleteProduct($id)
    {
        return $this->db->delete(self::TBL_BRAND_OPTIONS, 'WHERE id='. $id);
    }

    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_BRANDS, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;

        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_BRANDS,  $status, 'WHERE id=' . $id);
        }

        return false;
    }

    public function multiStatus($ids,$val)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            $status['status'] = $val;
            return $this->db->update(self::TBL_BRANDS,  $status, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
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
}