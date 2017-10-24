<?
// no  direct access
defined('_ACCESS') or die;

class filterHelper
{
    ////////checking incoming ajax data against security risks///////////////
    public static function checkData($data)
    {
        if (is_array($data))
            foreach ($data as $key => $element)
                if(is_array($element))
                    $data[$key] = self::keyCycle($element);
                else
                    $data[$key] = self::keyCheck($element);

        elseif (is_object($data))
            foreach ($data as $key => $element)
                $data->{$key} = self::keyCheck($element);

        else
            $data = self::keyCheck($data);

        return $data;
    }

    private static function keyCycle($data)
    {
        foreach ($data as $key => $element)
        {
            if (is_int($element) || is_float($element))
                $element = (int)$element;
            else
                $element = trim(htmlspecialchars(strip_tags($element),ENT_NOQUOTES));

            $data[$key] = $element;
        }

        return $data;
    }

    private static function keyCheck($element)
    {
        if (is_int($element) || is_float($element))
            $element = (int)$element;
        else
            $element = trim(htmlspecialchars(strip_tags($element),ENT_NOQUOTES));

        return $element;
    }
}