<?php
defined('_ACCESS') or die;

class models_Content extends models_BaseModel
{
    public function getOne()
    {
        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_CONTENT . '
            WHERE id = ' . $this->id . '
        ', null, Database::RETURN_DATA_ASSOC);

        if(models_helpers_Access::checkAccess('edit_your') || models_helpers_Access::checkAccess('edit') == 0)
            $res = $res[0]['author'] != $this->uinfo['id'] && models_helpers_Access::checkAccess('edit') == 0 ? exit : $res;

        return is_array($res) ? $res[0] : false;
    }

    public function getParams()
    {
        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_PARAMS . '
            WHERE status = 1'
            , null, Database::RETURN_DATA_ASSOC);
        return $res;
    }

    public function getLangs($mainLang)
    {
        $res = $this->db->select_full('
            SELECT name, short_name
            FROM ' .  self::TBL_LANGUAGE . '
            WHERE status = 1 AND short_name != "' . $mainLang . '"'
            , null, Database::RETURN_DATA_ASSOC);
        return $res;
    }

    public function getImages($id)
    {
        $res = $this->db->select_full('
            SELECT id,main,smallimg,image
            FROM ' .  self::TBL_IMAGES . '
            WHERE status = 1 AND type = 1 AND content_id = ' . $id
            , null, Database::RETURN_DATA_ASSOC);
        return $res;
    }

    public function getProductsList($data)
    {
        $res = $this->db->select_full('
            SELECT t1.id,t1.name,t2.smallimg AS img,t3.name AS brandname
            FROM ' .  self::TBL_PRODUCTS . ' AS t1
            LEFT JOIN ' . self::TBL_IMAGES . ' AS t2 ON t2.content_id = t1.id AND t2.type = 2 AND t2.main = 1
            LEFT JOIN ' . self::TBL_SUPPLIERS . ' AS t3 ON t3.id = t1.brand
            WHERE CONCAT(UPPER(t1.name),t1.vendor,UPPER(t3.name)) LIKE  UPPER("%' . $data . '%") ORDER BY t1.date_add DESC LIMIT 10'
            , null, Database::RETURN_DATA_ASSOC);

        return $res;
    }

    public function getAddedProductsList($id)
    {
        $res = $this->db->select_full('
            SELECT t1.id AS ident,t2.id,t2.name,t3.smallimg AS img,t4.name AS brandname
            FROM ' .  self::TBL_LOOKBOOK . ' AS t1
            LEFT JOIN ' .  self::TBL_PRODUCTS . ' AS t2 ON t2.id = t1.product_id
            LEFT JOIN ' . self::TBL_IMAGES . ' AS t3 ON t3.content_id = t2.id AND t3.type = 2 AND t3.main = 1
            LEFT JOIN ' . self::TBL_SUPPLIERS . ' AS t4 ON t4.id = t2.brand
            WHERE t1.content_id = ' . $id . ' ORDER BY t1.id DESC'
            , null, Database::RETURN_DATA_ASSOC);

        return $res;
    }

    public function getCollection($filter,$user = '')
    {
        $where = $this->prepareSql($filter);
        $filter = $filter->getData();

        $limit = $filter['onpage'];
        $offset = ($filter['page']-1) * $limit;

        $sql = '
            SELECT t1.id,t1.name,t1.category,t1.status,from_unixtime(t1.date_add, "%d.%c.%y %h:%i") as date, 
            t2.name AS author, t3.name as cat,t4.name as language
            FROM ' .  self::TBL_CONTENT . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            LEFT JOIN ' . self::TBL_CATEGORIES . ' AS t3 ON (t3.id = t1.category)
            LEFT JOIN ' . self::TBL_LANGUAGE . ' AS t4 ON (t4.short_name = t1.main_lang)
            WHERE t1.contype NOT IN ("ajax","wardrobe") ' . $where . '
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
            FROM ' .  self::TBL_CONTENT . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            LEFT JOIN ' . self::TBL_CATEGORIES . ' AS t3 ON (t3.id = t1.category)
            LEFT JOIN ' . self::TBL_LANGUAGE . ' AS t4 ON (t4.short_name = t1.main_lang)
            WHERE t1.contype NOT IN ("ajax","wardrobe") ' . $where . '
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
        {
            $res = $this->db->select_full('SELECT id,parent_id FROM ' . self::TBL_CATEGORIES, null, Database::RETURN_DATA_ASSOC);
            $sql .= ' AND t1.category IN (' . $filters['category'] . $this->childIds($res,$filters['category']) . ')';
        }

        if (!empty($filters['fulltext']))
            $sql .= " AND CONCAT(UPPER(t1.name), t1.url, UPPER(t2.name)) LIKE  UPPER('%$filters[fulltext]%')";

        return $sql;
    }

    // метод для выборки всех id элементов детей категории
    private function childIds($array,$id)
    {
        $list="";

        foreach ($array as $key=>$row)
        {
            // вывод дочерних элементов дерева
            if ($row['parent_id'] == $id)
            {
                $list .= ",".$row['id'];
                // рекурсия на случай наличия дочерних элементов
                $list .= self::childIds($array, $row['id']);
            }
        }

        return $list;
    }

    public function addItem(stdClass $data)
    {
        $arr = array(
            'author'     => $data->author,
            'name'       => $data->name,
            'show_title' => $data->show_title,
            'show_date'  => $data->show_date,
            'showCmnt'   => $data->showCmnt,
            'url'        => $data->url,
            'category'   => $data->category,
            'main_lang'  => $data->main_lang,
            'title'      => $data->title,
            'description'=> $data->description,
            'keywords'   => $data->keywords,
            'cat_img'    => $data->cat_img,
            'template'   => $data->template,
            'contype'    => $data->contype,
            'ordr'       => $data->ordr,
            'params'     => $data->params,
            'brand'      => $data->brand,
            'language'   => $data->language,
            'content'    => $data->content,
            'status'     => 1,
            'date_add'   => time(),
            'date_upd'   => time()
        );

        return $this->db->insert(self::TBL_CONTENT, $arr, true);
    }

    public function addImg($data)
    {
        $arr = array(
            'content_id' => $this->id,
            'type'       => 1,
            'main'       => $data['main'],
            'smallimg'   => $data['smallimg'],
            'image'      => $data['image'],
            'status'     => 1,
            'date_add'   => time(),
            'date_upd'   => time()
        );

        return $this->db->insert(self::TBL_IMAGES, $arr, true);
    }

    public function addProduct($data)
    {
        unset($data['action']);
        return $this->db->insert(self::TBL_LOOKBOOK, $data, true);
    }

    public function updateItem(stdClass $data)
    {
        $arr = array(
            'author'     => $data->author,
            'name'       => $data->name,
            'show_title' => $data->show_title,
            'show_date'  => $data->show_date,
            'showCmnt'   => $data->showCmnt,
            'url'        => $data->url,
            'category'   => $data->category,
            'main_lang'  => $data->main_lang,
            'title'      => $data->title,
            'description'=> $data->description,
            'keywords'   => $data->keywords,
            'cat_img'    => $data->cat_img,
            'template'   => $data->template,
            'contype'    => $data->contype,
            'ordr'       => $data->ordr,
            'params'     => $data->params,
            'brand'      => $data->brand,
            'language'   => $data->language,
            'content'    => $data->content,
            'date_upd'   => time()
        );

        if($data->language)
            $this->updateOtherLangs($data);

        return $this->db->update(self::TBL_CONTENT, $arr, 'WHERE id=' . $this->id);
    }

    private function updateOtherLangs(stdClass $data)
    {
        if(is_array(unserialize($data->language)))
            foreach(unserialize($data->language) as $k=>$v)
            {
                $res = $this->db->select_full('SELECT id,language FROM ' .self::TBL_CONTENT . ' WHERE url="'.$v.'"', null, Database::RETURN_DATA_ASSOC);

                $linkedLang = unserialize($res[0]['language']);
                $linkedLang[$data->main_lang] = $data->url;

                $arr = array (
                    'language'  => serialize($linkedLang),
                    'main_lang' => $k
                );

                $this->db->update(self::TBL_CONTENT, $arr, 'WHERE id=' . $res[0]['id']);
            }
    }

    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_CONTENT, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;

        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_CONTENT,  $status, 'WHERE id=' . $id);
        }

        return false;
    }


    public function multiStatus($ids,$val)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            $status['status'] = $val;
            return $this->db->update(self::TBL_CONTENT,  $status, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }

    public function deleteImage()
    {
        return $this->db->delete(self::TBL_IMAGES, 'WHERE id=' . $this->id);
    }

    public function deleteMulti($ids)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            return $this->db->delete(self::TBL_CONTENT, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }

    public function deleteProduct($id)
    {
        return $this->db->delete(self::TBL_LOOKBOOK, 'WHERE id=' . $id);
    }
}
?>