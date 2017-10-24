<?php
defined('_ACCESS') or die;
    
class models_filters_UsersFilter extends models_filters_BaseFilter
{

    public function __construct()
    {
        // возможные значения фильтра сортировки
        $this->sortKeys = array('id','name','users_group','reg_date');
        
        // значения по умолчанию фильтра сортировки
        $this->sortKeysDefault = $this->sortKeys[0];
        $this->sortOrderDefault = 'desc';
        
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
        
        $path = $this->request->getPathByName('users_group');
        if ($path)
        {
            $this->data['users_group'] = urldecode ($path);
            $this->linkArray['users_group'] = 'users_group/' . $path . '/';
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