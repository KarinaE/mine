<?php
defined('_ACCESS') or die;
    
class models_filters_OrderFilter extends models_filters_BaseFilter
{
    public function __construct()
    {
        // sort by filters
        $this->sortKeys = array('date_add','id','country','price','status');
        
        // base sort
        $this->sortKeysDefault = $this->sortKeys[1];
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
        
        $path = $this->request->getPathByName('category');
        if ($path)
        {
            $this->data['category'] = urldecode ($path);
            $this->linkArray['category'] = 'category/' . $path . '/';
        }
        
        $path = $this->request->getPathByName('dtfrom');
        if ($path)
        {
            $this->data['dtfrom'] = $path;
            $this->linkArray['dtfrom'] = 'dtfrom/' . $path . '/';
        }
        
        $path = $this->request->getPathByName('dtbefore');
        if ($path)
        {
            $this->data['dtbefore'] = $path;
            $this->linkArray['dtbefore'] = 'dtbefore/' . $path . '/';
        }
        
        $path = $this->request->getPathByName('fulltext');
        if ($path)
        {
            $this->data['fulltext'] = urldecode ($path);
            $this->linkArray['fulltext'] = 'fulltext/' . $path . '/';
        }
        
    }
    
    public function getDateFilter()
    {
        $ret = null;
        if (isset($this->data['dtfrom'], $this->data['dtbefore']) && $this->data['dtbefore'] && $this->data['dtfrom'])
        {
            $ret = array($this->data['dtfrom'], $this->data['dtbefore']);
        }
        
        return is_array($ret) ? $ret : array();
    }
} 
?>