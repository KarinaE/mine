<?php
defined('_ACCESS') or die;
    
class models_Product extends models_BaseModel
{
    public function getOne()
    {
        $res = $this->db->select_full('
            SELECT t1.*, GROUP_CONCAT(DISTINCT t2.src SEPARATOR "|") AS src, GROUP_CONCAT(DISTINCT t2.type SEPARATOR "|") AS type
            FROM ' .  self::TBL_PRODUCTS . ' AS t1
            LEFT JOIN ' . self::TBL_PROD_WARDROBE . ' AS t2 ON t2.product_id = t1.id
            WHERE t1.id = ' . $this->id . ' GROUP BY t1.id
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

    public function getImages($id,$option = null)
    {
        // check for options images
        $option = $option ? " AND image LIKE '%$option%'" : '';
        // check for main images
        $type = $option ? 3 : 2;

        $res = $this->db->select_full('
            SELECT id,main,smallimg,image
            FROM ' .  self::TBL_IMAGES . '
            WHERE status = 1 AND type = ' . $type . ' 
            AND content_id = ' . $id . $option . '
            ORDER BY date_add ASC',
            null, Database::RETURN_DATA_ASSOC);

        return $res;
    }

    public function getOptions($id)
    {
        $res = $this->db->select_full('
            SELECT options_array
            FROM ' .  self::TBL_PRODUCTS . '
            WHERE id = ' . $id
            , null, Database::RETURN_DATA_ASSOC);
        return is_array($res) ? $res[0] : false;
    }

    public function getOptionName($id)
    {
        $res = Database::instance()->select(models_BaseModel::TBL_PARAMETERS,'name', 'WHERE id = ' . $id,null, null, Database::ENCODE_HTML);
        return is_array($res) ? $res[0]['name'] : false;
    }

    public function getProductOptions($array)
    {
        $options = unserialize($array['options_array']);

        if($options)
            foreach($options as $k)
                $res[] = $this->db->select_full('SELECT * FROM ' . self::TBL_PARAMETERS . ' WHERE id=' . $k['option']);

        return $res;
    }

    public function getSize($id)
    {
        $res = $this->db->select_full('
            SELECT t3.id,t3.size
            FROM ' .  self::TBL_PRODUCTS . ' AS t1
            LEFT JOIN ' . self::TBL_CLOTH_TYPES . ' AS t2 ON t2.id = t1.type_size
            LEFT JOIN ' . self::TBL_CLOTH_SIZES . ' AS t3 ON t3.type_id = t2.id
            WHERE t1.id = ' . $id
            , null, Database::RETURN_DATA_ASSOC);

        return is_array($res) ? $res : false;
    }

    public function getQuant($id,$params)
    {
        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_PROD_REMAIN . '
            WHERE product_id = ' . $id . ' AND params = "' . $params . '"'
            , null, Database::RETURN_DATA_ASSOC);

        // if availible data in DB makin associative array
        if(is_array($res))
            foreach ($res as $val)
                $arr[$val['size_id']] = $val['value'];

        return is_array($arr) ? $arr : false;
    }

    public function getTotalQuant($id)
    {
        $res = $this->db->select_full('
            SELECT *
            FROM ' .  self::TBL_PROD_REMAIN . '
            WHERE product_id = ' . $id . ' AND params is null'
            , null, Database::RETURN_DATA_ASSOC);

        // if availible data in DB makin associative array
        if(is_array($res))
        {
            $arr = array();
            // total summ of products by sizes
            foreach ($res as $val)
                $arr[$val['size_id']] += $val['value'];
        }

        return is_array($arr) ? $arr : false;
    }

    public function getCollection($filter,$user = '')
    {
        $where = $this->prepareSql($filter);
        $filter = $filter->getData();

        $limit = $filter['onpage'];
        $offset = ($filter['page']-1) * $limit;

        $sql = '
            SELECT t1.id,t1.name,t1.category,t1.status,t1.price,t1.vendor,
            from_unixtime(t1.date_add, "%d.%c.%y %h:%i") as date, t2.name AS author, t3.name as cat,t4.name as language
            FROM ' .  self::TBL_PRODUCTS . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            LEFT JOIN ' . self::TBL_CATEGORIES . ' AS t3 ON (t3.id = t1.category)
            LEFT JOIN ' . self::TBL_LANGUAGE . ' AS t4 ON (t4.short_name = t1.main_lang)
            WHERE t1.contype = "product" ' . $where . '
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
            FROM ' .  self::TBL_PRODUCTS . ' AS t1
            LEFT JOIN ' . self::TBL_USERS . ' AS t2 ON (t2.id = t1.author)
            LEFT JOIN ' . self::TBL_CATEGORIES . ' AS t3 ON (t3.id = t1.category)
            LEFT JOIN ' . self::TBL_LANGUAGE . ' AS t4 ON (t4.short_name = t1.main_lang)
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

        if (!empty($filters['size']))
            $sql .= ' AND t1.type_size != 0';

        if (!empty($filters['category']))
        {
            $res = $this->db->select_full('SELECT id,parent_id FROM ' . self::TBL_CATEGORIES, null, Database::RETURN_DATA_ASSOC);
            $sql .= ' AND t1.category IN (' . $filters['category'] . $this->childIds($res,$filters['category']) . ')';
        }

        if (!empty($filters['fulltext']))
            $sql .= " AND CONCAT(UPPER(t1.name),UPPER(t1.url),t1.price,UPPER(t2.name),UPPER(t1.vendor)) LIKE  UPPER('%$filters[fulltext]%')";

        return $sql;
    }

    public function checkValue($arr)
    {
        if(!is_null($arr['params']))
            $res = $this->db->select_full('
                SELECT id
                FROM ' .  self::TBL_PROD_REMAIN . '
                WHERE size_id = ' . $arr['size_id'] . '
                AND params = "' . $arr['params'] . '"
                AND product_id = ' . $arr['product_id']
                , null, Database::RETURN_DATA_ASSOC);
        else
            $res = $this->db->select_full('
                SELECT id
                FROM ' .  self::TBL_PROD_REMAIN . '
                WHERE size_id = ' . $arr['size_id'] . '
                AND params is null
                AND product_id = ' . $arr['product_id']
                , null, Database::RETURN_DATA_ASSOC);

        return is_array($res) ? $res[0]['id'] : false;
    }

    public function checkOption($arr)
    {
        $res = $this->db->select_full('
                SELECT id
                FROM ' .  self::TBL_PROD_PAR_ACT . '
                WHERE product_id = ' . $arr['product_id'] . '
                AND option_value = "' . $arr['option_value'] . '"'
                , null, Database::RETURN_DATA_ASSOC);

        return is_array($res) ? $res[0]['id'] : false;
    }

    public function addItem(stdClass $data)
    {    
        $arr = array(
            'author'        => $data->author,
            'name'          => $data->name,
            'show_title'    => $data->show_title,
            'show_date'     => $data->show_date,
            'showCmnt'      => $data->showCmnt,
            'url'           => $data->url,
            'category'      => $data->category,
            'main_lang'     => $data->main_lang,
            'title'         => $data->title,
            'description'   => $data->description,
            'keywords'      => $data->keywords,
            'cat_img'       => $data->cat_img,
            'template'      => $data->template,
            'contype'       => $data->contype,
            'ordr'          => $data->ordr,
            'params'        => $data->params,
            'language'      => $data->language,
            'content'       => $data->content,
            'price'         => $data->price,
            'oldprice'      => $data->oldprice,
            'brand'         => $data->brand,
            'type_size'     => $data->type_size,
            'bonus'         => $data->bonus,
            'vendor'        => $data->vendor,
            'status'        => 1,
            'date_add'      => time(),
            'date_upd'      => time()
        );
        
        return $this->db->insert(self::TBL_PRODUCTS, $arr, true);
    }
    
    public function addImg($data)
    {
        $arr = array(
            'content_id' => $this->id,
            'type'       => $data['type'],
            'main'       => $data['main'],
            'smallimg'   => $data['smallimg'],
            'image'      => $data['image'],
            'status'     => 1,
            'date_add'   => time(),
            'date_upd'   => time()
        );
        
        return $this->db->insert(self::TBL_IMAGES, $arr, true);
    }
    
    public function addOption($id,$array)
    {
        $arr = array(
            'options_array' => serialize($array)
        );

        return $this->db->update(self::TBL_PRODUCTS, $arr, 'WHERE id=' . $id);
    }

    public function addValue($data)
    {
        $this->db->insert(self::TBL_PROD_REMAIN, $data, true);

        // if params = NULL - don't need to count
        if(!is_null($data['params']))
            return $this->updateTotal($data);
    }

    public function addOptionValue($data)
    {
        if($this->db->insert(self::TBL_PROD_PAR_ACT, $data, true))
            return 'product_form_option_value_added';
    }

    public function addWardrobeImage($data)
    {
        if($this->db->insert(self::TBL_PROD_WARDROBE, $data, true))
            return 'product_form_image_look_added';
    }

    public function updateValue($data,$id)
    {
        $this->db->update(self::TBL_PROD_REMAIN, $data, 'WHERE id=' . $id);

        // if params = NULL - don't need to count
        if(!is_null($data['params']))
            return $this->updateTotal($data);
    }

    private function updateTotal($data)
    {
        // getting total value
        $total = $this->db->select_full('
            SELECT SUM(value) AS total
            FROM ' .  self::TBL_PROD_REMAIN . '
            WHERE size_id = ' . $data['size_id'] . '
            AND product_id = ' . $data['product_id'] .'
            AND params is not null'
            , null, Database::RETURN_DATA_ASSOC);

        // making new array for total update
        unset($data['params']);
        $data['value'] = $total[0]['total'];

        // check if total quantity exists
        $res = $this->db->select_full('
            SELECT id
            FROM ' .  self::TBL_PROD_REMAIN . '
            WHERE size_id = ' . $data['size_id'] . '
            AND product_id = ' . $data['product_id'] . '
            AND params is null'
            , null, Database::RETURN_DATA_ASSOC);

        if($res === false)
            return $this->db->insert(self::TBL_PROD_REMAIN, $data, true);
        else
            return $this->db->update(self::TBL_PROD_REMAIN, $data, 'WHERE id=' . $res[0]['id']);
    }

    public function updateItem(stdClass $data)
    {
        $arr = array(
            'author'        => $data->author,
            'name'          => $data->name,
            'show_title'    => $data->show_title,
            'show_date'     => $data->show_date,
            'showCmnt'      => $data->showCmnt,
            'url'           => $data->url,
            'category'      => $data->category,
            'main_lang'     => $data->main_lang,
            'title'         => $data->title,
            'description'   => $data->description,
            'keywords'      => $data->keywords,
            'cat_img'       => $data->cat_img,
            'template'      => $data->template,
            'contype'       => $data->contype,
            'ordr'          => $data->ordr,
            'params'        => $data->params,
            'language'      => $data->language,
            'content'       => $data->content,
            'price'         => $data->price,
            'oldprice'      => $data->oldprice,
            'brand'         => $data->brand,
            'type_size'     => $data->type_size,
            'bonus'         => $data->bonus,
            'vendor'        => $data->vendor,
            'date_upd'      => time()
        );

        if($data->language)
            $this->updateOtherLangs($data);

        return $this->db->update(self::TBL_PRODUCTS, $arr, 'WHERE id=' . $this->id);
    }

    private function updateOtherLangs(stdClass $data)
    {
        foreach(unserialize($data->language) as $k=>$v)
        {
            $res = $this->db->select_full('SELECT id,language FROM ' .self::TBL_PRODUCTS . ' WHERE url="'.$v.'"', null, Database::RETURN_DATA_ASSOC);

            $linkedLang = unserialize($res['language']);
            $linkedLang[$data->main_lang] = $data->url;

            $arr = array (
                'language'  => serialize($linkedLang),
                'main_lang' => $k
            );

            return $this->db->update(self::TBL_PRODUCTS, $arr, 'WHERE id=' . $res[0]['id']);
        }
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
    
    public function changeStatus($id)
    {
        $res = $this->db->select(self::TBL_PRODUCTS, 'status',"WHERE id=$id",'','RETURN_DATA_ARR');
        $status['status'] = $res[0]['status'] == 1 ? 2 : 1;
        
        if (is_array($status) && count($status))
        {
            return $this->db->update(self::TBL_PRODUCTS,  $status, 'WHERE id=' . $id);
        }
        
        return false;    
    }
    
    // deleting optional product images
    public function deleteImages($name)
    {
        $res = $this->db->select(self::TBL_IMAGES, 'image,smallimg','WHERE content_id=' . $this->id . ' AND image LIKE "%' . $name . '%"','','RETURN_DATA_ARR');

        // check if previous image exists
        if(!is_array($res))
            return false;
            
        // base root to images
        $root = $_SERVER['DOCUMENT_ROOT'].models_helpers_Url::getDomain();
        
        //deleting all variations of images
        $this->delete_img($root,substr($res[0]['image'],1));
        $this->delete_img($root,substr($res[0]['smallimg'],1));
        $this->delete_img($root,substr($res[0]['smallimg'],1,-4).'-75'.substr($res[0]['smallimg'],-4));
        $this->delete_img($root,substr($res[0]['smallimg'],1,-4).'-125'.substr($res[0]['smallimg'],-4));
        $this->delete_img($root,substr($res[0]['smallimg'],1,-4).'-230'.substr($res[0]['smallimg'],-4));
        
        // deleting from DB
        return $this->db->delete(self::TBL_IMAGES, 'WHERE content_id=' . $this->id . ' AND image LIKE "%' . $name . '%"');
    }

    public function deleteOptionValue($id)
    {
        if($this->db->delete(self::TBL_PROD_PAR_ACT, 'WHERE id=' . $id))
            return 'product_form_option_value_deleted';
    }

    public function deleteWardrobeImage($arr)
    {
        return $this->db->delete(self::TBL_PROD_WARDROBE, 'WHERE product_id = ' . $arr['product_id'] . ' AND type = ' . $arr['type']);
    }
    
    public function deleteMulti($ids)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            return $this->db->delete(self::TBL_PRODUCTS, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }
    
    public function multiStatus($ids,$val)
    {
        if (is_array($ids) && count($ids))
        {
            $ids = filter_var_array($ids, FILTER_VALIDATE_INT);
            $status['status'] = $val;
            return $this->db->update(self::TBL_PRODUCTS,  $status, 'WHERE id IN (' . implode(',', $ids) . ')');
        }
        return false;
    }    
}
    
?>