<?php
defined('_ACCESS') or die;

class models_helpers_Options
{
    public static $getLevels = array(
        0 => '0',
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5'
    );

    public static $statusColors = array(
        0 => 'status',
        1 => 'new',
        2 => 'progress',
        3 => 'done',
        4 => 'sent',
        5 => 'delivered',
        6 => 'canceled'
    );

    public static function getGender()
    {
        return array(
            1 => models_helpers_Language::instance()->getOption('gender1'),
            2 => models_helpers_Language::instance()->getOption('gender2'),
        );
    }


    public static function statuses()
    {
        return array(
            1 => models_helpers_Language::instance()->getOption('active'),
            2 => models_helpers_Language::instance()->getOption('unactive'),
        );
    }

    public static function OrderStatuses($val = null)
    {
        $statuses = array(
            0 => models_helpers_Language::instance()->getOption('status'),
            1 => models_helpers_Language::instance()->getOption('new'),
            2 => models_helpers_Language::instance()->getOption('making'),
            3 => models_helpers_Language::instance()->getOption('ready'),
            4 => models_helpers_Language::instance()->getOption('sent'),
            5 => models_helpers_Language::instance()->getOption('delivered'),
            6 => models_helpers_Language::instance()->getOption('canceled')
        );

        return $val != null ? $statuses[$val] : $statuses;
    }

    public static function getContentType()
    {
        return array(
            'page' => models_helpers_Language::instance()->getOption('content'),
            'item' => models_helpers_Language::instance()->getOption('item'),
            'news' => models_helpers_Language::instance()->getOption('news'),
            'account' => models_helpers_Language::instance()->getOption('account'),
            'category' => models_helpers_Language::instance()->getOption('category'),
            'cart' => models_helpers_Language::instance()->getOption('cart'),
            'lookbook' => models_helpers_Language::instance()->getOption('lookbook')
        );
    }

    public static function moduleFiltersTypes()
    {
        return array(
            1 => models_helpers_Language::instance()->getOption('category'),
            2 => models_helpers_Language::instance()->getOption('brand'),
            3 => models_helpers_Language::instance()->getOption('option'),
            4 => models_helpers_Language::instance()->getOption('size'),
            5 => models_helpers_Language::instance()->getOption('price')
        );
    }

    public static function getModulesType()
    {
        return array(
            'menu' => models_helpers_Language::instance()->getOption('menu'),
            'search' => models_helpers_Language::instance()->getOption('search'),
            'slider' => models_helpers_Language::instance()->getOption('slider'),
            'links' => models_helpers_Language::instance()->getOption('links'),
            'comments' => models_helpers_Language::instance()->getOption('comments'),
            'pagelinks' => models_helpers_Language::instance()->getOption('pagelinks'),
            'products' => models_helpers_Language::instance()->getOption('products'),
            'lang' => models_helpers_Language::instance()->getOption('lang'),
            'currency' => models_helpers_Language::instance()->getOption('currency'),
            'filters' => models_helpers_Language::instance()->getOption('filters')
        );
    }

    public static function sortOrder()
    {
        return array(
            "ORDER BY c.views DESC" => models_helpers_Language::instance()->getOption('sort_views_desc'),
            "ORDER BY c.date_add DESC" => models_helpers_Language::instance()->getOption('sort_date_add_desc'),
            "ORDER BY c.date_add ASC" => models_helpers_Language::instance()->getOption('sort_date_add_asc'),
            "ORDER BY c.name DESC" => models_helpers_Language::instance()->getOption('sort_name_desc'),
            "ORDER BY c.name ASC" => models_helpers_Language::instance()->getOption('sort_name_asc'),
            "ORDER BY c.ordr ASC" => models_helpers_Language::instance()->getOption('sort_ordr_asc'),
            "ORDER BY c.comments DESC" => models_helpers_Language::instance()->getOption('sort_comments_desc'),
            "ORDER BY RAND()" => models_helpers_Language::instance()->getOption('sort_random')
        );
    }

    public static function monthFormat()
    {
        return array(
            "short" => models_helpers_Language::instance()->getOption('short'),
            "long" => models_helpers_Language::instance()->getOption('full')
        );
    }

    public static function imagePosition()
    {
        return array(
            "left" => models_helpers_Language::instance()->getOption('left'),
            "center" => models_helpers_Language::instance()->getOption('center'),
            "right" => models_helpers_Language::instance()->getOption('right')
        );
    }

    public static function descriptionPosition()
    {
        return array(
            "before" => models_helpers_Language::instance()->getOption('before'),
            "middle" => models_helpers_Language::instance()->getOption('middle'),
            "after" => models_helpers_Language::instance()->getOption('after')
        );
    }

    public static function getLanguages()
    {
        return array(
            "DE" => models_helpers_Language::instance()->getOption('DE'),
            "RU" => models_helpers_Language::instance()->getOption('RU'),
            "EN" => models_helpers_Language::instance()->getOption('EN')
        );
    }

    public static function getParamTypes()
    {
        return array(
            "slider" => models_helpers_Language::instance()->getOption('sngslider'),
            "dblslider" => models_helpers_Language::instance()->getOption('dblslider'),
            "select" => models_helpers_Language::instance()->getOption('select'),
            "radio" => models_helpers_Language::instance()->getOption('radio'),
            "imagebtn" => models_helpers_Language::instance()->getOption('imagebtn')
        );
    }

    public static function basicOptions($options, $selected = null, $empty_option = false, $empty_text = '')
    {
        if ($empty_text)
            $options = array($empty_option => $empty_text) + $options;

        return self::get_array_options(array_keys($options), array_values($options), $selected);
    }

    static public function get_array_options(array $vals, array $texts, $ids, $cats = '')
    {
        $opt = '';
        $count = sizeof($texts);

        if (is_string($ids))
            $ids = explode(',', $ids);

        for ($k = 0; $k < $count; $k++)
            $opt .= '<option value="' . $vals[$k] . '"' . ((is_array($ids) && in_array($vals[$k], $ids)) || $ids == $vals[$k] ? ' selected="selected"' : '') . ' style="' . (@$cats[$k] == 2 ? 'font-weight:bold;' : 'margin-left:10px') . '">' . $texts[$k] . '</option>';

        return $opt;
    }

    private static function sortAsTree($data, $parent)
    {
        foreach ($data as $k => $v)
            if ($v['parent_id'] == $parent) {
                $GLOBALS["arr"][] = $v;
                if ($v['child'] == 1)
                    self::sortAsTree($data, $v['id']);
            }

        return $GLOBALS["arr"];
    }

    static public function getLanguage()
    {
        $res = Database::instance()->select(models_BaseModel::TBL_LANGUAGE, 'name, short_name', 'WHERE status = 1 ORDER BY id ASC', null, null, Database::ENCODE_HTML);
        if (!empty($res)) {
            foreach ($res as $key => $val)
                $options[$val['short_name']] = $val['name'];
            return $options;
        } else
            return array(0 => models_helpers_Language::instance()->getOption('not_found'));
    }

    static public function getUsersGroups()
    {
        $res = Database::instance()->select(models_BaseModel::TBL_UTYPES, 'id,name', 'ORDER BY id ASC', null, null, Database::ENCODE_HTML);
        if (!empty($res)) {
            foreach ($res as $key => $val)
                $options[$val['id']] = '- ' . $val['name'];
            return $options;
        } else
            return array(0 => models_helpers_Language::instance()->getOption('not_found'));
    }

    static public function getOptions()
    {
        $res = Database::instance()->select(models_BaseModel::TBL_PARAMETERS, 'name, id', 'ORDER BY id ASC', null, null, Database::ENCODE_HTML);
        if (!empty($res)) {
            foreach ($res as $key => $val)
                $options[$val['id']] = $val['name'];
            return $options;
        } else
            return array(0 => models_helpers_Language::instance()->getOption('not_found'));
    }

    static public function getSizes()
    {
        $res = Database::instance()->select(models_BaseModel::TBL_CLOTH_TYPES, 'name, id', 'ORDER BY id ASC', null, null, Database::ENCODE_HTML);
        if (!empty($res)) {
            foreach ($res as $key => $val)
                $options[$val['id']] = $val['name'];
            return $options;
        } else
            return array(0 => models_helpers_Language::instance()->getOption('not_found'));
    }

    static public function getBrands()
    {
        $res = Database::instance()->select(models_BaseModel::TBL_SUPPLIERS, 'name, id', 'ORDER BY id ASC', null, null, Database::ENCODE_HTML);
        if (!empty($res)) {
            foreach ($res as $key => $val)
                $options[$val['id']] = $val['name'];
            return $options;
        } else
            return array(0 => models_helpers_Language::instance()->getOption('not_found'));
    }

    static public function sizeOptions($options, $selected = null, $empty_option = false, $empty_text = '',$type)
    {
        // if no size added to product - showing all size in additional case - only of product type
        foreach ($options as $key => $val)
            if($type == 0)
                $arr[$key] = $val['size'];
            else
                if($type == $val['type'])
                    $arr[$key] = $val['size'];

        // adding basic values
        if ($empty_text)
            $options = array($empty_option => $empty_text) + $arr;

        return self::get_array_options(array_keys($options), array_values($options), $selected);
    }

    static public function productOptions($options, $selected = null, $empty_option = false, $empty_text = '')
    {
        // array of options values
        foreach ($options as $key => $val)
            $arr[$key] = $val['val'];

        // adding basic values
        if ($empty_text)
            $options = array($empty_option => $empty_text) + $arr;

        return self::get_array_options(array_keys($options), array_values($options), $selected);
    }
}