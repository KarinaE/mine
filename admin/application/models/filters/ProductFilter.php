<?php
defined('_ACCESS') or die;

class models_filters_ProductFilter extends models_filters_BaseFilter
{

    public function __construct()
    {
        // возможные значения фильтра сортировки
        $this->sortKeys = array('t1.date_add','id','name','author');

        // значения по умолчанию фильтра сортировки
        $this->sortKeysDefault = $this->sortKeys[1];
        $this->sortOrderDefault = 'asc';

        parent::__construct();
    }

    protected function parseFilter()
    {
        parent::parseFilter();

        $path = $this->request->getPathByName('status');
        if ($path)
        {
            $this->data['status'] = $path;
            $this->linkArray['status'] = 'status/' . $path . '/';
        }

        $path = $this->request->getPathByName('fulltext');
        if ($path)
        {
            $this->data['fulltext'] = urldecode ($path);
            $this->linkArray['fulltext'] = 'fulltext/' . $path . '/';
        }

    }
}
?>