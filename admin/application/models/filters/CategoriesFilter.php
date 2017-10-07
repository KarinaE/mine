<?php
defined('_ACCESS') or die;
    
class models_filters_CategoriesFilter extends models_filters_BaseFilter
{

    public function __construct()
    {
        // возможные значения фильтра сортировки
        $this->sortKeys = array('date_add','id','name','author','category','ordr');
        
        // значения по умолчанию фильтра сортировки
        $this->sortKeysDefault = $this->sortKeys[5];
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
        
        $path = $this->request->getPathByName('category');
        if ($path)
        {
            $this->data['category'] = urldecode ($path);
            $this->linkArray['category'] = 'category/' . $path . '/';
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