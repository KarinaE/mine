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
        2 => 'payed',
        3 => 'confirmed',
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
            2 => models_helpers_Language::instance()->getOption('payed'),
            3 => models_helpers_Language::instance()->getOption('confirmed'),
            4 => models_helpers_Language::instance()->getOption('sent'),
            5 => models_helpers_Language::instance()->getOption('delivered'),
            6 => models_helpers_Language::instance()->getOption('canceled')
        );

        return $val != null ? $statuses[$val] : $statuses;
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